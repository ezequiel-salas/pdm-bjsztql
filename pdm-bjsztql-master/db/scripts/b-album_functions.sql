-- Function 8
CREATE OR REPLACE FUNCTION get_albums(
)
    RETURNS TABLE
            (
                album_id     INTEGER,
                album        VARCHAR(40),
                artist       VARCHAR(40),
                release_date TIMESTAMP,
                play_count   BIGINT,
                genres       VARCHAR(100)
            )
    LANGUAGE sql
AS
$$
SELECT
    alb.album_id,
    alb.name as album,
    art.name as artist,
    alb.release_date,
    CAST(COALESCE(sp.play_count, 0) AS BIGINT) as play_count,
    COALESCE(sp.genres, 'N/A') as genres
FROM album AS alb
INNER JOIN album_artist AS aa
    ON aa.album_id = alb.album_id
INNER JOIN artist AS art
    ON art.artist_id = aa.artist_id
LEFT JOIN (
    SELECT
        s.album,
        SUM(s.play_count)                AS play_count,
        STRING_AGG(DISTINCT s.genre, ', ') AS genres
    FROM get_songs() s
    GROUP BY s.album, s.artist
) AS sp
    on sp.album = alb.name
$$;

-- Function 9
CREATE OR REPLACE FUNCTION get_albums_alpha(
    num INTEGER, batch INTEGER
)
    RETURNS TABLE
            (
                album        VARCHAR(40),
                artist       VARCHAR(40),
                release_date TIMESTAMP,
                genres       VARCHAR(100),
                play_count   BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    a.album,
    a.artist,
    a.release_date,
    a.genres,
    a.play_count
FROM get_albums() AS a
ORDER BY a.album
LIMIT num OFFSET batch * num
$$;

-- Function 9 3/4
/*
 * Get a batch of <num> albums and order by their play count. Retrieves the <batch>th batch of records.
 */
CREATE OR REPLACE FUNCTION get_albums_play_count(
    num INTEGER, batch INTEGER
)
    RETURNS TABLE
            (
                album        VARCHAR(40),
                artist       VARCHAR(40),
                release_date TIMESTAMP,
                genres       VARCHAR(100),
                play_count   BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    a.album,
    a.artist,
    a.release_date,
    a.genres,
    a.play_count
FROM get_albums() AS a
ORDER BY a.play_count DESC, a.album
LIMIT num OFFSET batch * num
$$;

--New function inserted by Sam
CREATE OR REPLACE FUNCTION get_albums_release_date(
    num INTEGER, batch INTEGER
)
    RETURNS TABLE
            (
                album        VARCHAR(40),
                artist       VARCHAR(40),
                release_date TIMESTAMP,
                genres       VARCHAR(100),
                play_count   BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    a.album,
    a.artist,
    a.release_date,
    a.genres,
    a.play_count
FROM get_albums() AS a
ORDER BY a.release_date DESC, a.album
LIMIT num OFFSET batch * num
$$;
