import os
import json
import sys
from datetime import date

import pymysql


def get_connection():
    return pymysql.connect(
        host=os.environ["DB_HOST"],
        user=os.environ["DB_USER"],
        password=os.environ["DB_PASSWORD"],
        database=os.environ["DB_NAME"],
        port=int(os.environ.get("DB_PORT", 3306)),
        cursorclass=pymysql.cursors.DictCursor,
    )


QUERY = """
SELECT
    t.track_ID,
    t.name AS track_name,
    t.aerial_image_url,
    l.latitude,
    l.longitude,
    l.city,
    s.abbreviation AS state_abbr,
    p.promotion_ID,
    p.season,
    p.name AS promotion_name,
    tpe.track_promotion_event_ID,
    tpe.name AS event_name,
    tpe.event_date,
    tpes.name AS event_status,
    re.money_for_win,
    CASE
        WHEN tpe.event_date < CURDATE() THEN 'past'
        WHEN tpe.event_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 'this_week'
        ELSE 'future'
    END AS time_status,
    CASE
        WHEN p.name LIKE '%%Late Model%%' THEN 'late_model'
        ELSE 'sprint_car'
    END AS series
FROM track t
JOIN company co
    ON co.company_ID = t.company_ID
LEFT JOIN location l
    ON l.location_ID = co.physical_location_ID
LEFT JOIN state s
    ON s.state_ID = l.state_ID
JOIN promotion p
    ON p.track_ID = t.track_ID
JOIN track_promotion_event tpe
    ON tpe.promotion_ID = p.promotion_ID
JOIN track_promo_event_status tpes
    ON tpes.track_promo_event_status_ID = tpe.track_promo_event_status_ID
LEFT JOIN race_event re
    ON re.track_promotion_event_ID = tpe.track_promotion_event_ID
WHERE t.is_closed = 0
  AND (p.name LIKE '%%World of Outlaws%%' OR p.name LIKE '%%World of Oultaws%%')
  AND tpe.event_date BETWEEN %s AND %s
  AND tpes.name NOT IN ('Draft', 'Cancelled')
ORDER BY tpe.event_date
"""


def fetch_season_rows(season_start, season_end):
    conn = get_connection()
    try:
        with conn.cursor() as cur:
            cur.execute(QUERY, (season_start, season_end))
            return cur.fetchall()
    finally:
        conn.close()


def build_feature_collection(rows):
    seen_event_ids = set()
    features = []
    skipped_missing_coords = []

    for row in rows:
        event_id = row["track_promotion_event_ID"]
        if event_id in seen_event_ids:
            continue
        seen_event_ids.add(event_id)

        if row["latitude"] is None or row["longitude"] is None:
            skipped_missing_coords.append((row["track_name"], row["event_name"]))
            continue

        features.append({
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [float(row["longitude"]), float(row["latitude"])],
            },
            "properties": {
                "track": row["track_name"],
                "event": row["event_name"],
                "date": row["event_date"].isoformat(),
                "city": row["city"],
                "state": row["state_abbr"],
                "purse": str(row["money_for_win"]) if row["money_for_win"] is not None else None,
                "image": row["aerial_image_url"],
                "status": row["time_status"],
                "series": row["series"],
            },
        })

    features.sort(key=lambda f: f["properties"]["date"])

    if skipped_missing_coords:
        print(f"WARNING: skipped {len(skipped_missing_coords)} rows with missing coordinates:", file=sys.stderr)
        for track_name, event_name in skipped_missing_coords:
            print(f"  {track_name} - {event_name}", file=sys.stderr)

    return {"type": "FeatureCollection", "features": features}


def main():
    season_start = os.environ.get("SEASON_START", "2026-01-01")
    season_end = os.environ.get("SEASON_END", "2026-12-31")
    output_path = os.environ.get("OUTPUT_PATH", "data/woo-season.geojson")

    rows = fetch_season_rows(season_start, season_end)
    fc = build_feature_collection(rows)

    sprint_count = sum(1 for f in fc["features"] if f["properties"]["series"] == "sprint_car")
    late_model_count = sum(1 for f in fc["features"] if f["properties"]["series"] == "late_model")

    os.makedirs(os.path.dirname(output_path) or ".", exist_ok=True)
    with open(output_path, "w") as f:
        json.dump(fc, f, separators=(",", ":"))

    print(f"Wrote {len(fc['features'])} stops to {output_path}")
    print(f"  sprint_car: {sprint_count}")
    print(f"  late_model: {late_model_count}")


if __name__ == "__main__":
    main()
