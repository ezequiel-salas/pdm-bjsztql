CREATE OR REPLACE FUNCTION get_collection_artists(
    user_id INTEGER
)
    RETURNS TABLE
            (
                artist           VARCHAR(40),
                play_count_user  BIGINT,
                play_count_total BIGINT
            )
    LANGUAGE sql
AS
$$
WITH
    apcu AS (
        SELECT
            ca.artist_id,
            ca.uid,
            CAST(SUM(COALESCE(pcu.play_count_user, 0)) AS BIGINT) AS artist_play_count_user
        FROM collection_artist AS ca
        INNER JOIN song_artist AS sa
                   ON ca.artist_id = sa.artist_id
        LEFT JOIN get_song_play_count_users() AS pcu
                  ON sa.song_id = pcu.song_id AND ca.uid = pcu.uid
        GROUP BY ca.artist_id, ca.uid
    ),
    apct AS (
        SELECT
            sa.artist_id,
            CAST(SUM(COALESCE(pct.play_count_total, 0)) AS BIGINT) AS artist_play_count_total
        FROM song_artist AS sa
        LEFT JOIN get_song_play_count_total() AS pct
                  ON sa.song_id = pct.song_id
        GROUP BY sa.artist_id
    )
SELECT
    art.name                     AS artist,
    apcu.artist_play_count_user  AS play_count_user,
    apct.artist_play_count_total AS play_count_total
FROM collection_artist AS cart
INNER JOIN artist AS art
           ON cart.artist_id = art.artist_id
INNER JOIN apcu
           ON cart.artist_id = apcu.artist_id AND cart.uid = apcu.uid
INNER JOIN apct
           ON cart.artist_id = apct.artist_id
WHERE cart.uid = user_id
$$;

CREATE OR REPLACE FUNCTION get_collection_artists_alpha(
    num INTEGER, batch INTEGER, user_id INTEGER
)
       RETURNS TABLE
            (
                artist           VARCHAR(40),
                play_count_user  BIGINT,
                play_count_total BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT *
FROM get_collection_artists(user_id)
ORDER BY artist
LIMIT num OFFSET num * batch
$$;

CREATE OR REPLACE FUNCTION get_collection_artists_play_count(
    num INTEGER, batch INTEGER, user_id INTEGER
)
 RETURNS TABLE
            (
                artist           VARCHAR(40),
                play_count_user  BIGINT,
                play_count_total BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT *
FROM get_collection_artists(user_id)
ORDER BY play_count_user DESC, artist
LIMIT num OFFSET num * batch
$$;
