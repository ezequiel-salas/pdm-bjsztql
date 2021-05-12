-- Function 15
/*
 * Get genres with their play count
 */
CREATE OR REPLACE FUNCTION get_genres(
)
    RETURNS TABLE
            (
                genre_id   INTEGER,
                genre      VARCHAR(40),
                play_count BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    g.genre_id,
    g.name as genre,
    CAST(SUM(COALESCE(s.play_count, 0)) AS BIGINT) AS play_count
FROM genre AS g
LEFT JOIN get_songs() AS s
    ON s.genre = g.name
GROUP BY g.name, g.genre_id
$$;

CREATE OR REPLACE FUNCTION get_genres_alpha(
)
    RETURNS TABLE
            (
                genre      VARCHAR(40),
                play_count BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    g.genre,
    g.play_count
FROM get_genres() AS g
ORDER BY g.genre
$$;

/*
 * Get the genres ordered by play count
 */
CREATE OR REPLACE FUNCTION get_genres_play_count(
)
    RETURNS TABLE
            (
                genre      VARCHAR(40),
                play_count BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    g.genre,
    g.play_count
FROM get_genres() AS g
ORDER BY g.play_count DESC, g.genre
$$;
