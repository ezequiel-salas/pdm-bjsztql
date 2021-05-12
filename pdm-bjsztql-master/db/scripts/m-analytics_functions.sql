-- Get the genre that a user has played the most
CREATE OR REPLACE FUNCTION get_user_most_played_genre(
    user_id INT
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
    g.name                                              AS genre,
    CAST(SUM(COALESCE(s.play_count_user, 0)) AS BIGINT) AS play_count
FROM genre AS g
INNER JOIN get_collection_songs(user_id) AS s
           ON s.genre = g.name
GROUP BY g.name
ORDER BY play_count DESC, g.name
LIMIT 1
$$;

-- Get the genre that a user has played the most
CREATE OR REPLACE FUNCTION get_user_most_played_genre(
    username VARCHAR(40)
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
    mpg.genre,
    mpg.play_count
FROM get_user_most_played_genre((
    SELECT
        uid
    FROM account
    WHERE user_name = username
)) AS mpg
$$;

-- Get the artists that have been played the most for a genre
CREATE OR REPLACE FUNCTION get_top_artists_for_genre(
    genre_name VARCHAR(40)
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
    aa.artist_name,
    aa.play_count /*artist's total play count*/
FROM get_artists() AS aa
INNER JOIN song_artist AS sa
           ON sa.artist_id = aa.artist_id
INNER JOIN song_genre AS sg
           ON sg.song_id = sa.song_id
INNER JOIN genre AS g
           ON g.genre_id = sg.genre_id
WHERE g.name = genre_name
GROUP BY aa.artist_name, aa.play_count
$$;

CREATE OR REPLACE FUNCTION get_top_songs_for_genre(
    genre_name VARCHAR(40)
)
    RETURNS TABLE
            (
                song       VARCHAR(40),
                artist     VARCHAR(40),
                album      VARCHAR(40),
                duration   INTERVAL,
                play_count BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    s.song,
    s.artist,
    s.album,
    s.song_length,
    CAST(SUM(COALESCE(s.play_count, 0)) AS BIGINT) AS play_count
FROM get_songs() AS s
WHERE s.genre = genre_name
GROUP BY s.song, s.artist, s.album, s.song_length, s.genre
$$;

-- Get the albums that have been played the most for a genre
CREATE OR REPLACE FUNCTION get_top_albums_for_genre(genre_name VARCHAR(40)
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
SELECT * FROM get_albums_by_genre(genre_name)
        as ca
$$;

-- Get the top <num> artists that have created a song in the user's most played genre
CREATE OR REPLACE FUNCTION get_recommended_artist_from_genre(
    num INTEGER, username VARCHAR(40)
)
    RETURNS TABLE
            (
                artist_name VARCHAR(40)
            )
    LANGUAGE sql
AS
$$
SELECT
    tag.artist
FROM get_top_artists_for_genre((
    SELECT
        genre
    FROM get_user_most_played_genre(username)
)) AS tag
WHERE NOT EXISTS
    (
        SELECT
            ca.artist
        FROM get_collection_artists((
            SELECT
                uid
            FROM account
            WHERE user_name = username
        )) AS ca
        WHERE ca.artist = tag.artist
    )
ORDER BY tag.play_count DESC, tag.artist
LIMIT num
$$;

-- Get the top <num> songs in the user's most played genre
CREATE OR REPLACE FUNCTION get_recommended_songs_from_genre(
    num INTEGER, username VARCHAR(40)
)
    RETURNS TABLE
            (
                song   VARCHAR(40),
                artist VARCHAR(40),
                album  VARCHAR(40),
                duration INTERVAL
            )
    LANGUAGE sql
AS
$$
SELECT
    tsg.song,
    tsg.artist,
    tsg.album,
    tsg.duration
FROM get_top_songs_for_genre((
    SELECT
        genre
    FROM get_user_most_played_genre(username)
)) AS tsg
WHERE NOT EXISTS
    (
        SELECT *
        FROM get_collection_songs((
            SELECT
                uid
            FROM account
            WHERE user_name = username
        )) AS sa
        WHERE sa.song = tsg.song
          AND sa.artist = tsg.artist
    )
ORDER BY tsg.play_count DESC, tsg.song
LIMIT num
$$;

-- Get the top <num> albums in the user's most played genre
CREATE OR REPLACE FUNCTION get_recommended_albums_from_genre( num INTEGER, username VARCHAR(40))
 RETURNS TABLE
            (
                album        VARCHAR(40),
                artist       VARCHAR(40),
                release_date TIMESTAMP
            )
    LANGUAGE sql
AS
$$
SELECT
    tag.album,
    tag.artist,
    tag.release_date
FROM get_top_albums_for_genre((
    SELECT
        genre
    FROM get_user_most_played_genre(username)
)) AS tag
WHERE NOT EXISTS
    (
        SELECT *
        FROM get_collection_albums((
            SELECT
                uid
            FROM account
            WHERE user_name = username
        )) AS sa
        WHERE sa.album = tag.album
          AND sa.artist = tag.artist
          AND sa.release_date = tag.release_date
    )
ORDER BY tag.play_count DESC, tag.album
LIMIT num
$$;

/*
 * Time Series Data
 */

-- Get the play count of a genre over the months in a year.
CREATE OR REPLACE FUNCTION get_monthly_genre_plays(
    genre VARCHAR(40)
)
    RETURNS TABLE
            (
                month      VARCHAR(3),
                play_count BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    TO_CHAR(TO_TIMESTAMP(months::TEXT, 'MM'), 'TMmon') AS month,
    CAST(COALESCE(data.play_count, 0) AS BIGINT)       AS play_count
FROM generate_series(1, 12) AS months
LEFT JOIN (
    SELECT
        DATE_PART('month', sp.ts) AS month,
        COUNT(*)                  AS play_count
    FROM song_play AS sp
    INNER JOIN song_genre AS sg
               ON sp.song_id = sg.song_id
    INNER JOIN genre AS g
               ON sg.genre_id = g.genre_id
    WHERE g.name = genre
    GROUP BY DATE_PART('year', sp.ts), DATE_PART('month', sp.ts)
    ORDER BY DATE_PART('month', sp.ts)
) AS data
          ON data.month = months
$$;

-- Get the play count of an artist over the months in a year.
CREATE OR REPLACE FUNCTION get_monthly_artist_plays(
    artist VARCHAR(40)
)
    RETURNS TABLE
            (
                month      VARCHAR(3),
                play_count BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    TO_CHAR(TO_TIMESTAMP(months::TEXT, 'MM'), 'TMmon') AS month,
    CAST(COALESCE(data.play_count, 0) AS BIGINT)       AS play_count
FROM generate_series(1, 12) AS months
LEFT JOIN (
    SELECT
        DATE_PART('month', sp.ts) AS month,
        COUNT(*)                  AS play_count
    FROM song_play AS sp
    INNER JOIN song_artist AS sa
               ON sp.song_id = sa.song_id
    INNER JOIN artist AS art
               ON sa.artist_id = art.artist_id
    WHERE art.name = artist
    GROUP BY DATE_PART('year', sp.ts), DATE_PART('month', sp.ts)
    ORDER BY DATE_PART('month', sp.ts)
) AS data
          ON data.month = months
$$;

-- Get the play count of a song over the months in a year.
CREATE OR REPLACE FUNCTION get_monthly_song_play(
    song VARCHAR(40), artist VARCHAR(40)
)
    RETURNS TABLE
            (
                month      VARCHAR(3),
                play_count BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    TO_CHAR(TO_TIMESTAMP(months::TEXT, 'MM'), 'TMmon') AS month,
    CAST(COALESCE(data.play_count, 0) AS BIGINT)       AS play_count
FROM generate_series(1, 12) AS months
LEFT JOIN (
    SELECT
        DATE_PART('month', sp.ts) AS month,
        COUNT(*)                  AS play_count
    FROM song_play AS sp
    INNER JOIN song_artist AS sa
               ON sp.song_id = sa.song_id
    INNER JOIN artist AS art
               ON sa.artist_id = art.artist_id
    INNER JOIN song AS s
               ON sp.song_id = s.song_id
    WHERE art.name = artist
      AND s.name = song
    GROUP BY DATE_PART('year', sp.ts), DATE_PART('month', sp.ts)
    ORDER BY DATE_PART('month', sp.ts)
) AS data
          ON data.month = months
$$;

-- Get the play count of an album over the months in a year.
CREATE OR REPLACE FUNCTION get_monthly_album_play(
    album VARCHAR(40)
)
    RETURNS TABLE
            (
                month      VARCHAR(3),
                play_count BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    TO_CHAR(TO_TIMESTAMP(months::TEXT, 'MM'), 'TMmon') AS month,
    CAST(COALESCE(data.play_count, 0) AS BIGINT)       AS play_count
FROM generate_series(1, 12) AS months
LEFT JOIN (
    SELECT
        DATE_PART('month', sp.ts) AS month,
        COUNT(*)                  AS play_count
    FROM song_play AS sp
    INNER JOIN song_album AS sa
               ON sp.song_id = sa.song_id
    INNER JOIN album AS alb
               ON sa.album_id = alb.album_id
    WHERE alb.name = album
    GROUP BY DATE_PART('year', sp.ts), DATE_PART('month', sp.ts)
    ORDER BY DATE_PART('month', sp.ts)
) AS data
          ON data.month = months
$$;