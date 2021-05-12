-- Function 1
CREATE OR REPLACE FUNCTION get_songs_and_play_count(
)
    RETURNS TABLE
            (
                song_id     INTEGER,
                song_name   VARCHAR(40),
                song_length INTERVAL,
                play_count  BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    s2.song_id,
    s2.name     AS song_name,
    s2.duration AS song_length,
    CAST(COALESCE(pc.play_count, 0) AS BIGINT)
FROM song AS s2
LEFT JOIN (
    SELECT
        pci.song_id,
        pci.play_count
    FROM (
        SELECT
            sp.song_id,
            COUNT(*) AS play_count
        FROM song_play AS sp
        GROUP BY sp.song_id
    ) AS pci
) AS pc
    ON s2.song_id = pc.song_id
$$;

-- Function 2
CREATE OR REPLACE FUNCTION get_songs(
)
    RETURNS TABLE
            (
                song_id     INTEGER,
                song        VARCHAR(40),
                song_length INTERVAL,
                play_count  BIGINT,
                album       VARCHAR(40),
                artist      VARCHAR(40),
                genre       VARCHAR(40)
            )
    LANGUAGE sql
AS
$$
SELECT
    sp.song_id,
    sp.song_name AS song,
    sp.song_length,
    sp.play_count,
    alb.name     AS album,
    art.name     AS artist,
    g.name       AS genre
FROM get_songs_and_play_count() AS sp
LEFT JOIN song_album sa
    ON sa.song_id = sp.song_id
LEFT JOIN album alb
    ON alb.album_id = sa.album_id
INNER JOIN song_artist s
    ON sa.song_id = s.song_id
INNER JOIN artist AS art
    ON art.artist_id = s.artist_id
INNER JOIN song_genre AS sg
    ON sg.song_id = sp.song_id
INNER JOIN genre AS g
    ON g.genre_id = sg.genre_id
$$;

-- Function 3
CREATE OR REPLACE FUNCTION get_songs_alpha(
    num INTEGER, batch INTEGER
)
    RETURNS TABLE
            (
                song        VARCHAR(40),
                song_length INTERVAL,
                artist      VARCHAR(40),
                album       VARCHAR(40),
                genre       VARCHAR(40),
                play_count  BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    s.song,
    s.song_length,
    s.artist,
    s.album,
    s.genre,
    s.play_count
FROM get_songs() AS s
ORDER BY s.song
LIMIT num OFFSET batch * num
$$;

-- Function 4
/*
 * Get songs and order them by their play count. Result set contains enough information to uniquely identify a song without its actual id
 */
CREATE OR REPLACE FUNCTION get_songs_play_count(
    num INTEGER, batch INTEGER
)
    RETURNS TABLE
            (
                song        VARCHAR(40),
                song_length INTERVAL,
                artist      VARCHAR(40),
                album       VARCHAR(40),
                genre       VARCHAR(40),
                play_count  BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    s.song,
    s.song_length,
    s.artist,
    s.album,
    s.genre,
    s.play_count
FROM get_songs() AS s
ORDER BY s.play_count DESC, s.song
LIMIT num OFFSET batch * num
$$;
