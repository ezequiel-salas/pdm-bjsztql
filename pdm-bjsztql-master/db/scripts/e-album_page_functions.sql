-- Function 16
CREATE OR REPLACE FUNCTION get_songs_in_album(
    album VARCHAR(40)
)
    RETURNS TABLE
            (
                song         VARCHAR(40),
                track_number INTEGER,
                song_length  INTERVAL,
                genre        VARCHAR(40),
                play_count   BIGINT
            )
    LANGUAGE sql
AS
$$
SELECT
    s.song_name AS song,
    sa.track_number,
    s.song_length,
    g.name as genre,
    s.play_count
FROM album AS alb
INNER JOIN song_album AS sa
    ON alb.album_id = sa.album_id
INNER JOIN get_songs_and_play_count() AS s
    ON sa.song_id = s.song_id
INNER JOIN song_genre as sg
    ON sg.song_id = sa.song_id
INNER JOIN genre as g
    ON g.genre_id = sg.genre_id
WHERE alb.name = album
ORDER BY track_number
$$;


-- Function 17
/*
 * Usage: select * from get_album_and_artist([artist_name => '<artist_name>'], [album_name => '<album_name>'0)
 * Both parameters are optional, but a value is required if you specify.
 * You can avoid using the parameter name if you call with just the artist name
 * Do not call with both artist and album names
 */
CREATE OR REPLACE FUNCTION get_album_and_artist(
    artist_name VARCHAR(40) DEFAULT NULL, album_name VARCHAR(40) DEFAULT NULL
)
    RETURNS TABLE
            (
                album  VARCHAR(40),
                artist VARCHAR(40)
            )
    LANGUAGE sql
AS
$$
SELECT
    alb.name AS album,
    art.name AS artist
FROM album AS alb
INNER JOIN album_artist AS aa
    ON alb.album_id = aa.album_id
INNER JOIN artist AS art
    ON aa.artist_id = art.artist_id
WHERE CASE
          WHEN artist_name IS NOT NULL THEN art.name
          WHEN album_name IS NOT NULL THEN alb.name
          ELSE art.name
          END =
      COALESCE(artist_name, album_name, 'Sam Ford')
$$;
