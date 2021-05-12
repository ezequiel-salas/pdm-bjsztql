/*
 * Throws and exception if the artist being added has already been added. Must be handled
 * in the application.
 *
 * Returns a message indicating the status of the operation otherwise.
 */
CREATE OR REPLACE FUNCTION add_collection_artist(user_id integer, artist_name character varying)
    RETURNS varchar(100)
    LANGUAGE plpgsql
as
$$
DECLARE
    artist_id INTEGER;
    _m VARCHAR(100);
BEGIN

    SELECT INTO artist_id
        art.artist_id
    FROM artist AS art
    WHERE art.name = artist_name;

    IF artist_id IS NULL
    THEN
        SELECT INTO _m CONCAT('Artist "', artist_name, '" does not exist');
        return _m;
    ELSE
        INSERT INTO collection_artist
        (
            uid,
            artist_id
        )
        SELECT
            user_id,
            artist_id;

        SELECT INTO _m CONCAT('Successfully added artist "', artist_name, '" to collection.');
        return _m;
    END IF;
END
$$;

/*
 * Throws and exception if the album being added has already been added. Must be handled
 * in the application.
 *
 * Returns a message indicating the status of the operation otherwise.
 */
CREATE OR REPLACE FUNCTION add_collection_album(
    user_id INTEGER, album_name VARCHAR(40)
)
RETURNS VARCHAR(100)
    LANGUAGE plpgsql
AS
$$
DECLARE
    album_id INTEGER;
    _m VARCHAR(100);
BEGIN

    SELECT INTO album_id
        art.album_id
    FROM album AS art
    WHERE art.name = album_name;

    IF album_id IS NULL
    THEN
        SELECT INTO _m CONCAT('album "', album_name, '" does not exist');
        return _m;
    ELSE
        INSERT INTO collection_album
        (
            uid,
            album_id
        )
        SELECT
            user_id,
            album_id;

        SELECT INTO _m CONCAT('Successfully added album "', album_name, '" to collection.');
        return _m;
    END IF;
END
$$;

/*
 * Throws and exception if the album being added has already been added. Must be handled
 * in the application.
 *
 * Returns a message indicating the status of the operation otherwise.
 */
CREATE OR REPLACE FUNCTION add_collection_song(
    user_id INTEGER, song_name VARCHAR(40), album_name VARCHAR(40), artist_name VARCHAR(40), song_duration INTERVAL
)
    RETURNS VARCHAR(100)
    LANGUAGE plpgsql
AS
$$
DECLARE
    song_id INTEGER;
    _m      VARCHAR(100);
BEGIN

    SELECT INTO song_id
        s.song_id
    FROM song AS s
    WHERE s.duration = song_duration
      AND s.name = song_name
      AND EXISTS(
            SELECT
                sart.song_id
            FROM song_artist AS sart
            INNER JOIN artist AS art
                       ON art.artist_id = sart.artist_id
            INNER JOIN song_album salb
                       ON salb.song_id = sart.song_id
            INNER JOIN album AS alb
                       ON alb.album_id = salb.album_id
            WHERE alb.name = album_name
              AND art.name = artist_name
        );

    IF song_id IS NULL THEN
        SELECT INTO _m CONCAT('Could not find song "', song_name, '"');
        RETURN _m;
    END IF;

    INSERT INTO collection_song
    (
        uid,
        song_id
    )
    SELECT
        user_id,
        song_id;

    SELECT INTO _m CONCAT('Successfully added song "', song_name, '" to collection.');
    RETURN _m;
END
$$;
