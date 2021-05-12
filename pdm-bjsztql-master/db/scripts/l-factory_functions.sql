CREATE OR REPLACE FUNCTION create_artist(
    artist_name VARCHAR(40)
)
    RETURNS VARCHAR(100)
    LANGUAGE plpgsql
AS
$$
DECLARE
    _message VARCHAR(100);
BEGIN

    INSERT INTO artist
    (
        name
    )
    SELECT
        artist_name;

    SELECT INTO _message CONCAT('Successfully created new artist "', artist_name, '"');
    return _message;
END
$$;

CREATE OR REPLACE FUNCTION create_album(
    album_name VARCHAR(40), artist_name VARCHAR(40)
)
    RETURNS VARCHAR(100)
    LANGUAGE plpgsql
AS
$$
DECLARE
    artist_id INTEGER;
    album_id  INTEGER;
    _message VARCHAR(100);
BEGIN

    SELECT INTO artist_id
        a.artist_id
    FROM artist AS a
    WHERE a.name = artist_name;

    IF artist_id IS NULL THEN
        SELECT INTO _message CONCAT('Artist "', artist_name, '" does not exist');
        RETURN _message;
    END IF;

    INSERT INTO album
    (
        name,
        release_date
    )
    SELECT
        album_name,
        NOW();

    SELECT INTO album_id
        alb.album_id
    FROM album AS alb
    WHERE alb.name = album_name;

    INSERT INTO album_artist
    (
        album_id,
        artist_id
    )
    SELECT
        album_id,
        artist_id;

    SELECT INTO _message CONCAT('Successfully created new album "', album_name, '"');
    return _message;
END
$$;

CREATE OR REPLACE FUNCTION create_genre(
    genre_name VARCHAR(40)
)
    RETURNS VARCHAR(100)
    LANGUAGE plpgsql
AS
$$
DECLARE
    _message VARCHAR(100);
BEGIN

    INSERT INTO genre
    (
        name
    )
    SELECT
        genre_name;

    SELECT INTO _message CONCAT('Successfully created new genre "', genre_name, '"');
    return _message;
END
$$;

CREATE OR REPLACE FUNCTION create_song(
    song_name VARCHAR(40),
    artist_name VARCHAR(40),
    album_name VARCHAR(40),
    genre_name VARCHAR(40),
    song_length INTERVAL
)
    RETURNS VARCHAR(100)
    LANGUAGE plpgsql
AS
$$
DECLARE
    artist_id      INTEGER;
    album_id_param INTEGER;
    genre_id       INTEGER;
    track_number   INTEGER;
    song_id        INTEGER;
    _message VARCHAR(100);
BEGIN

    SELECT INTO artist_id a.artist_id FROM artist AS a WHERE a.name = artist_name;
    IF artist_id IS NULL THEN
        SELECT INTO _message CONCAT('The artist "', artist_name, '" does not exist');
        RETURN _message;
    END IF;

    SELECT INTO album_id_param a.album_id FROM album AS a WHERE a.name = album_name;
    IF album_id_param IS NULL THEN
        SELECT INTO _message CONCAT('The album "', album_name, '" does not exist');
        RETURN _message;
    END IF;

    SELECT INTO genre_id g.genre_id FROM genre AS g WHERE g.name = genre_name;
    IF genre_id IS NULL THEN
        SELECT INTO _message CONCAT('The genre "', genre_name, '" does not exist');
        RETURN _message;
    END IF;

    SELECT INTO track_number
        COALESCE(MAX(sa.track_number), 0) + 1 AS track_number
    FROM song_album AS sa
    WHERE sa.album_id = album_id_param
    GROUP BY sa.album_id;

    IF track_number IS NULL THEN
        track_number := 1;
    END IF;

    INSERT INTO song
    (
        name,
        duration
    )
    SELECT
        song_name,
        song_length;

    SELECT INTO song_id MAX(s.song_id) FROM song AS s WHERE s.name = song_name;

    INSERT INTO song_artist
    (
        song_id,
        artist_id
    )
    SELECT
        song_id,
        artist_id;

    INSERT INTO song_album
    (
        song_id,
        album_id,
        track_number
    )
    SELECT
        song_id,
        album_id_param,
        track_number;

    INSERT INTO song_genre
    (
        song_id,
        genre_id
    )
    SELECT
        song_id,
        genre_id;

    SELECT INTO _message CONCAT('Successfully created new song "', song_name, '"');
    return _message;
END
$$;
