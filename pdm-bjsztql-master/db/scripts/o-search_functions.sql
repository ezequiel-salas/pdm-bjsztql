/*search for songs containing a string or search for songs
  by artist_name containing a string*/
CREATE OR REPLACE FUNCTION search_songs(search VARCHAR(40)
)
    RETURNS TABLE
        (
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
    s.song,
    s.song_length,
    s.play_count,
    s.album,
    s.artist,
    s.genre
    FROM get_songs() AS s
    WHERE(s.song LIKE CONCAT('%', search, '%')OR
          s.album LIKE CONCAT('%', search, '%')OR
          s.artist LIKE CONCAT('%', search, '%')OR
          s.genre LIKE CONCAT('%', search, '%'))
ORDER BY play_count DESC
$$;
  /*search for albums containing a string or for albums by
    artist_name containing a string*/
CREATE OR REPLACE FUNCTION search_albums(search VARCHAR(40)
)
    RETURNS TABLE
        (
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
      alb.album,
       alb.artist,
       alb.release_date,
       alb.play_count,
       alb.genres
FROM get_albums() as alb
WHERE (alb.album LIKE CONCAT('%', search, '%') OR
       alb.artist LIKE CONCAT('%', search, '%') OR
       alb.genres LIKE CONCAT('%', search, '%'))
ORDER BY play_count DESC
$$;

/*search for artists containing a string*/
CREATE OR REPLACE FUNCTION search_artists(search VARCHAR(40)
)
    RETURNS TABLE
        (
        artist VARCHAR(40),
        play_count BIGINT
        )
    LANGUAGE SQL
AS
$$
SELECT
    a.artist_name,
    a.play_count
    FROM  get_artists() AS a
    WHERE(a.artist_name LIKE CONCAT('%', search, '%'))
    ORDER BY play_count DESC
$$;
/*search for genres containing a string*/
CREATE OR REPLACE FUNCTION search_genres(search VARCHAR(40)
)
    RETURNS TABLE
        (
        genre VARCHAR(40),
        play_count BIGINT
        )
    LANGUAGE SQL
AS
$$
SELECT
    g.genre,
    g.play_count
    FROM  get_genres() AS g
    WHERE(g.genre LIKE CONCAT('%', search, '%'))
    ORDER BY play_count DESC
$$;
