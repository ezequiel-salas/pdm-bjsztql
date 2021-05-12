/*
 * Returns a message indicating the status of the operation otherwise.
 */
CREATE OR REPLACE FUNCTION remove_collection_artist(
    user_id INTEGER, artist_name CHARACTER VARYING
)
    RETURNS VARCHAR(100)
    LANGUAGE plpgsql
AS
$$
DECLARE
    art_id INTEGER;
    _m        VARCHAR(100);
BEGIN

    SELECT INTO art_id
        art.artist_id
    FROM artist AS art
    INNER JOIN collection_artist AS ca
               ON art.artist_id = ca.artist_id
    WHERE art.name = artist_name
      AND ca.uid = user_id;

    IF art_id IS NULL
    THEN
        SELECT INTO _m CONCAT('Artist "', artist_name, '" does not exist in collection');
        RETURN _m;
    ELSE
        DELETE
        FROM collection_artist AS ca
        WHERE ca.uid = user_id
          AND ca.artist_id = art_id;

        SELECT INTO _m CONCAT('Successfully removed artist "', artist_name, '" from collection.');
        RETURN _m;
    END IF;
END
$$;

/*
 * Returns a message indicating the status of the operation otherwise.
 */
CREATE OR REPLACE FUNCTION remove_collection_album(
    user_id INTEGER, album_name VARCHAR(40)
)
    RETURNS VARCHAR(100)
    LANGUAGE plpgsql
AS
$$
DECLARE
    alb_id INTEGER;
    _m       VARCHAR(100);
BEGIN

    SELECT INTO alb_id
        alb.album_id
    FROM album AS alb
    INNER JOIN collection_album AS ca
               ON alb.album_id = ca.album_id
    WHERE alb.name = album_name
      AND ca.uid = user_id;

    IF alb_id IS NULL
    THEN
        SELECT INTO _m CONCAT('album "', album_name, '" does not exist in collection');
        RETURN _m;
    ELSE
        DELETE
        FROM collection_album AS ca
        WHERE ca.uid = user_id
          AND ca.album_id = alb_id;

        SELECT INTO _m CONCAT('Successfully removed album "', album_name, '" from collection.');
        RETURN _m;
    END IF;
END
$$;

/*
 * Returns a message indicating the status of the operation otherwise.
 */
CREATE OR REPLACE FUNCTION remove_collection_song(
    user_id INTEGER, song_name VARCHAR(40), album_name VARCHAR(40), artist_name VARCHAR(40), song_duration INTERVAL
)
    RETURNS VARCHAR(100)
    LANGUAGE plpgsql
AS
$$
DECLARE
    s_id INTEGER;
    _m      VARCHAR(100);
BEGIN

    SELECT INTO s_id
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
            INNER JOIN collection_song AS cs
                       ON salb.song_id = cs.song_id
            WHERE alb.name = album_name
              AND art.name = artist_name
              AND cs.uid = user_id
        );

    IF s_id IS NULL THEN
        SELECT INTO _m CONCAT('Could not find song "', song_name, '" in collection');
        RETURN _m;
    END IF;

    DELETE
    FROM collection_song AS cs
    WHERE cs.uid = user_id
      AND cs.song_id = s_id;

    SELECT INTO _m CONCAT('Successfully removed song "', song_name, '" from collection.');
    RETURN _m;
END
$$;
