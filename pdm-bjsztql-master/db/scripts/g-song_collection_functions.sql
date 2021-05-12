CREATE OR REPLACE FUNCTION get_song_play_count_users(
)
    RETURNS TABLE
            (
                uid             INTEGER,
                song_id         INTEGER,
                play_count_user BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    cs.uid,
    cs.song_id,
    COUNT(sp.ts) AS play_count_user
FROM collection_song AS cs
INNER JOIN song_play AS sp
           ON cs.song_id = sp.song_id AND cs.uid = sp.uid
GROUP BY cs.song_id, cs.uid
$$;

CREATE OR REPLACE FUNCTION get_song_play_count_total(
)
    RETURNS TABLE
            (
                song_id          INTEGER,
                play_count_total BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    sp.song_id,
    COUNT(sp.ts) AS play_count_total
FROM song_play AS sp
GROUP BY sp.song_id
$$;
CREATE OR REPLACE FUNCTION get_song_play_count_users(
)
    RETURNS TABLE
            (
                uid             INTEGER,
                song_id         INTEGER,
                play_count_user BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    sp.uid,
    sp.song_id,
    COUNT(*) AS play_count_user
FROM song_play AS sp
GROUP BY sp.song_id, sp.uid
$$;

CREATE OR REPLACE FUNCTION get_song_play_count_total(
)
    RETURNS TABLE
            (
                song_id          INTEGER,
                play_count_total BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    sp.song_id,
    COUNT(sp.ts) AS play_count_total
FROM song_play AS sp
GROUP BY sp.song_id
$$;

CREATE OR REPLACE FUNCTION get_collection_songs(
    user_id INTEGER
)
    RETURNS TABLE
            (
                song             VARCHAR(40),
                song_length      INTERVAL,
                album            VARCHAR(40),
                artist           VARCHAR(40),
                play_count_user  BIGINT,
                play_count_total BIGINT,
                genre            VARCHAR(40)
            )
    LANGUAGE sql
AS
$$
SELECT
    s.name                            AS song,
    s.duration                        AS song_length,
    alb.name                          AS album,
    art.name                          AS artist,
    CAST(COALESCE(spu.play_count_user, 0) AS BIGINT)  AS play_count_user,
    CAST(COALESCE(spt.play_count_total, 0) AS BIGINT) AS play_count_total,
    g.name                            AS genre
FROM collection_song AS cs
INNER JOIN song AS s
           ON cs.song_id = s.song_id
LEFT JOIN song_album AS sa
          ON cs.song_id = sa.song_id
LEFT JOIN album AS alb
          ON sa.album_id = alb.album_id
INNER JOIN song_artist AS sa2
           ON cs.song_id = sa2.song_id
INNER JOIN artist AS art
           ON sa2.artist_id = art.artist_id
INNER JOIN song_genre AS sg
           ON cs.song_id = sg.song_id
INNER JOIN genre AS g
           ON sg.genre_id = g.genre_id
LEFT JOIN get_song_play_count_users() AS spu
          ON cs.uid = spu.uid AND cs.song_id = spu.song_id
LEFT JOIN get_song_play_count_total() AS spt
          ON cs.song_id = spt.song_id
WHERE cs.uid = user_id
$$;

CREATE OR REPLACE FUNCTION get_collection_songs_alpha(
    num INTEGER, batch INTEGER, user_id INTEGER
)
    RETURNS TABLE
            (
                song             VARCHAR(40),
                song_length      INTERVAL,
                album            VARCHAR(40),
                artist           VARCHAR(40),
                play_count_user  BIGINT,
                play_count_total BIGINT,
                genre            VARCHAR(40)
            )
    LANGUAGE sql
AS
$$
SELECT *
FROM get_collection_songs(user_id)
ORDER BY song
LIMIT num OFFSET num * batch
$$;

CREATE OR REPLACE FUNCTION get_collection_songs_play_count(
    num INTEGER, batch INTEGER, user_id INTEGER
)
    RETURNS TABLE
            (
                song             VARCHAR(40),
                song_length      INTERVAL,
                album            VARCHAR(40),
                artist           VARCHAR(40),
                play_count_user  BIGINT,
                play_count_total BIGINT,
                genre            VARCHAR(40)
            )
    LANGUAGE sql
AS
$$
SELECT *
FROM get_collection_songs(user_id)
ORDER BY play_count_user DESC, song
LIMIT num OFFSET num * batch
$$;
