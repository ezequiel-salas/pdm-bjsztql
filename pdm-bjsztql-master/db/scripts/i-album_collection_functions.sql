CREATE OR REPLACE FUNCTION get_collection_albums(
    user_id INTEGER
)
    RETURNS TABLE
            (
                album            VARCHAR(40),
                artist           VARCHAR(40),
                release_date     TIMESTAMP,
                play_count_user  BIGINT,
                play_count_total BIGINT,
                genres           VARCHAR(100)
            )
    LANGUAGE sql
AS
$$
WITH
    apcu   AS (
        SELECT
            ca.album_id,
            ca.uid,
            CAST(SUM(COALESCE(spu.play_count_user, 0)) AS BIGINT) AS album_play_count_user
        FROM collection_album AS ca
        INNER JOIN song_album AS sa
                   ON ca.album_id = sa.album_id
        LEFT JOIN get_song_play_count_users() AS spu
                  ON sa.song_id = spu.song_id and ca.uid = spu.uid
        GROUP BY ca.album_id, ca.uid
    ),
    apct   AS (
        SELECT
            sa.album_id,
            CAST(SUM(COALESCE(spt.play_count_total, 0)) AS BIGINT) AS album_play_count_total
        FROM song_album AS sa
        LEFT JOIN get_song_play_count_total() AS spt
                  ON sa.song_id = spt.song_id
        GROUP BY sa.album_id
    ),
    genres AS (
        SELECT
            ca.album_id,
            STRING_AGG(DISTINCT g.name, ', ') AS genres
        FROM collection_album AS ca
        INNER JOIN song_album AS s
                   ON ca.album_id = s.album_id
        INNER JOIN song_genre AS sg
                   ON s.song_id = sg.song_id
        INNER JOIN genre AS g
                   ON sg.genre_id = g.genre_id
        GROUP BY ca.album_id
    )
SELECT
    alb.name                    AS album,
    art.name                    AS artist,
    alb.release_date,
    apcu.album_play_count_user  AS play_count_user,
    apct.album_play_count_total AS play_count_total,
    g.genres
FROM collection_album AS calb
INNER JOIN album AS alb
           ON calb.album_id = alb.album_id
INNER JOIN album_artist AS aa
           ON calb.album_id = aa.album_id
INNER JOIN artist AS art
           ON aa.artist_id = art.artist_id
INNER JOIN apcu
           ON calb.album_id = apcu.album_id AND calb.uid = apcu.uid
INNER JOIN apct
           ON calb.album_id = apct.album_id
INNER JOIN genres AS g
           ON calb.album_id = g.album_id
WHERE calb.uid = user_id
$$;

CREATE OR REPLACE FUNCTION get_collection_albums_alpha(
    num INTEGER, batch INTEGER, user_id INTEGER
)
       RETURNS TABLE
            (
                album            VARCHAR(40),
                artist           VARCHAR(40),
                release_date     TIMESTAMP,
                play_count_user  BIGINT,
                play_count_total BIGINT,
                genres           VARCHAR(100)
            )
    LANGUAGE sql
AS
$$
SELECT *
FROM get_collection_albums(user_id)
ORDER BY album
LIMIT num OFFSET num * batch
$$;

CREATE OR REPLACE FUNCTION get_collection_albums_play_count(
    num INTEGER, batch INTEGER, user_id INTEGER
)
       RETURNS TABLE
            (
                album            VARCHAR(40),
                artist           VARCHAR(40),
                release_date     TIMESTAMP,
                play_count_user  BIGINT,
                play_count_total BIGINT,
                genres           VARCHAR(100)
            )
    LANGUAGE sql
AS
$$
SELECT *
FROM get_collection_albums(user_id)
ORDER BY play_count_user DESC, album
LIMIT num OFFSET num * batch
$$;

CREATE OR REPLACE FUNCTION get_collection_albums_release_date(
    num INTEGER, batch INTEGER, user_id INTEGER
)
       RETURNS TABLE
            (
                album            VARCHAR(40),
                artist           VARCHAR(40),
                release_date     TIMESTAMP,
                play_count_user  BIGINT,
                play_count_total BIGINT,
                genres           VARCHAR(100)
            )
    LANGUAGE sql
AS
$$
SELECT *
FROM get_collection_albums(user_id)
ORDER BY release_date DESC, album
LIMIT num OFFSET num * batch
$$;
