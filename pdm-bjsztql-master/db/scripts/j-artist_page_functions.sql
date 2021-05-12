-- Function 18
CREATE OR REPLACE FUNCTION get_albums_by_artist(
    desired_artist VARCHAR(40)
)
    RETURNS TABLE
            (
                album        VARCHAR(40),
                release_date TIMESTAMP,
                genres       VARCHAR(100),
                play_count   BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    alb.album,
    alb.release_date,
    alb.genres,
    alb.play_count
FROM get_albums() AS alb
WHERE alb.artist = desired_artist
$$;

-- Function ##
CREATE OR REPLACE FUNCTION get_albums_by_artist_alpha(
    desired_artist VARCHAR(40), num INTEGER, batch INTEGER
)
    RETURNS TABLE
            (
                album        VARCHAR(40),
                release_date TIMESTAMP,
                genres       VARCHAR(100),
                play_count   BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    aa.album,
    aa.release_date,
    aa.genres,
    aa.play_count
FROM get_albums_by_artist(desired_artist) AS aa
ORDER BY aa.album
LIMIT num OFFSET batch * num
$$;
--new func SAM
CREATE OR REPLACE FUNCTION get_albums_by_artist_release_date(
    desired_artist VARCHAR(40), num INTEGER, batch INTEGER
)
    RETURNS TABLE
            (
                album        VARCHAR(40),
                release_date TIMESTAMP,
                genres       VARCHAR(100),
                play_count   BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    aa.album,
    aa.release_date,
    aa.genres,
    aa.play_count
FROM get_albums_by_artist(desired_artist) AS aa
ORDER BY aa.release_date DESC
LIMIT num OFFSET batch * num
$$;

-- Function 20
CREATE OR REPLACE FUNCTION get_albums_by_artist_play_count(
    desired_artist VARCHAR(40), num INTEGER, batch INTEGER
)
    RETURNS TABLE
            (
                album        VARCHAR(40),
                release_date TIMESTAMP,
                genres       VARCHAR(100),
                play_count   BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    aa.album,
    aa.release_date,
    aa.genres,
    aa.play_count
FROM get_albums_by_artist(desired_artist) AS aa
ORDER BY aa.play_count DESC, aa.album
LIMIT num OFFSET batch * num
$$;
