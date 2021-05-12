-- Function 18
CREATE OR REPLACE FUNCTION get_albums_by_genre(
    genre VARCHAR(40)
)
    RETURNS TABLE
            (
                album        VARCHAR(40),
                artist       VARCHAR(40),
                release_date TIMESTAMP,
                play_count   BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    alb.album,
    alb.artist,
    alb.release_date,
    alb.play_count
FROM get_albums() AS alb
WHERE alb.genres LIKE CONCAT('%', genre, '%')
$$;

-- Function 19
CREATE OR REPLACE FUNCTION get_albums_by_genre_alpha(
    genre VARCHAR(40), num INTEGER, batch INTEGER
)
    RETURNS TABLE
            (
                album        VARCHAR(40),
                artist       VARCHAR(40),
                release_date TIMESTAMP,
                play_count   BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    ag.album,
    ag.artist,
    ag.release_date,
    ag.play_count
FROM get_albums_by_genre(genre) AS ag
ORDER BY ag.album
LIMIT num OFFSET batch * num
$$;
-- NEW FUNC SAM
CREATE OR REPLACE FUNCTION get_albums_by_genre_release_date(
    genre VARCHAR(40), num INTEGER, batch INTEGER
)
    RETURNS TABLE
            (
                album        VARCHAR(40),
                artist       VARCHAR(40),
                release_date TIMESTAMP,
                play_count   BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    ag.album,
    ag.artist,
    ag.release_date,
    ag.play_count
FROM get_albums_by_genre(genre) AS ag
ORDER BY ag.release_date DESC
LIMIT num OFFSET batch * num
$$;
-- Function 20
CREATE OR REPLACE FUNCTION get_albums_by_genre_play_count(
    genre VARCHAR(40), num INTEGER, batch INTEGER
)
    RETURNS TABLE
            (
                album        VARCHAR(40),
                artist       VARCHAR(40),
                release_date TIMESTAMP,
                play_count   BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    ag.album,
    ag.artist,
    ag.release_date,
    ag.play_count
FROM get_albums_by_genre(genre) AS ag
ORDER BY ag.play_count DESC, ag.album
LIMIT num OFFSET batch * num
$$;
