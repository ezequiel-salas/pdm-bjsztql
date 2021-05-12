-- Function 12
CREATE OR REPLACE FUNCTION get_artists()
    RETURNS TABLE
            (
                artist_id  INTEGER,
                artist_name VARCHAR(40),
                play_count  BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    art.artist_id,
    art.name,
    CAST(COALESCE(COUNT(sp.song_id), 0) AS BIGINT) AS play_count
FROM artist AS art
LEFT JOIN song_artist sa
    ON art.artist_id = sa.artist_id
LEFT JOIN song_play sp
    ON sa.song_id = sp.song_id
GROUP BY art.name, art.artist_id
$$;

-- Function 13
CREATE OR REPLACE FUNCTION get_artists_alpha(
    num INTEGER,
    batch INTEGER
)
    RETURNS TABLE
            (
                artist     VARCHAR(40),
                play_count BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    a.artist_name AS name,
    a.play_count
FROM get_artists() AS a
ORDER BY a.artist_name
LIMIT num
OFFSET batch * num
$$;

-- Function 14
/*
 * Get a batch of <num> artists ordered by play count. Retrieves the <batch> numbered batch of records.
 */
CREATE OR REPLACE FUNCTION get_artists_play_count(
    num INTEGER,
    batch INTEGER
)
    RETURNS TABLE
            (
                artist     VARCHAR(40),
                play_count BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    a.artist_name AS artist,
    a.play_count
FROM get_artists() AS a
ORDER BY a.play_count DESC
LIMIT num
OFFSET batch * num
$$;
