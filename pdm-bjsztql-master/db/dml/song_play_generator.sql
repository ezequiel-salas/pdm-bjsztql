/*FOR EACH SONG_ID
  RANDOMLY DETERMINE HOW MANY TIME STAMPS IT GETS
  SET UID TO 0*/
DO
$$
    DECLARE
        COUNTULA INT;
        MAX_UID  INT;
        MIN_UID  INT;
        M_PLAYS  INT := 20;
        L_RECS   INT := 0;

    BEGIN
        SELECT MAX(SONG_ID) INTO COUNTULA FROM SONG;
        SELECT MAX(UID) INTO MAX_UID FROM ACCOUNT;
        SELECT MIN(UID) INTO MIN_UID FROM ACCOUNT;
        FOR i IN 1..COUNTULA
            LOOP
                DECLARE
                    QTY INT := floor(random() * (M_PLAYS - L_RECS + 1) + L_RECS)::INT; --20 is good upperbound according to prof brown
                BEGIN
                    FOR counter IN 1..QTY
                        LOOP
                            DECLARE
                                RAND_USER INT := floor(random() * (MAX_UID - MIN_UID + 1) + MIN_UID)::INT;
                            BEGIN
                                INSERT INTO SONG_PLAY(
                                    SONG_ID,
                                    UID,
                                    TS
                                )
                                VALUES
                                (
                                    i,
                                    RAND_USER,
                                    TIMESTAMP '2020-01-01 00:00:00' +
                                    random() * (now() -
                                                TIMESTAMP '2020-01-01 0:00:00')
                                );
                            END;
                        END LOOP;
                END;
            END LOOP;
    END;
$$ LANGUAGE plpgsql;

/*Random generation of collection_song table*/
DO $$
DECLARE
MAX_UID INT;
MIN_UID INT;
MAX_SID INT;
MIN_SID INT;
MOST INT := 100;
LEAST INT := 0;

BEGIN
SELECT MAX(UID) INTO MAX_UID FROM ACCOUNT;
SELECT MIN(UID) INTO MIN_UID FROM ACCOUNT;
SELECT MAX(SONG_ID) INTO MAX_SID FROM SONG;
SELECT MIN(SONG_ID) INTO MIN_SID FROM SONG;
FOR i IN MIN_UID..MAX_UID LOOP
    DECLARE
    SONGS INT := floor(random() * (MOST-LEAST+1) + LEAST)::int; /*fixme temporary increase for final submission*/
    TAKEN INT ARRAY; /*keep track of what songs have been added to avoid dups*/
    BEGIN
    FOR counter IN 1..SONGS LOOP
        DECLARE
        RAND_SONG INT :=  floor(random() * (MAX_SID-MIN_SID+1) + MIN_SID)::int;
        BEGIN
        BEGIN
        /*loop until we've found a new song for this user*/
        WHILE RAND_SONG = ANY(TAKEN) LOOP
            RAND_SONG := floor(random() * (MAX_SID-MIN_SID+1) + MIN_SID)::int;
            END LOOP;
        END;
        TAKEN = array_append(TAKEN,RAND_SONG);
        INSERT INTO COLLECTION_SONG(SONG_ID,UID)
        VALUES(RAND_SONG,i);
        END;
    END LOOP;
END;
END LOOP;
END;
$$ LANGUAGE plpgsql;

/*Generate initial collection_album data*/
DO $$
DECLARE
MAX_UID INT;
MIN_UID INT;
MAX_AID INT;
MIN_AID INT;
MOST INT := 100;
LEAST INT := 0;

BEGIN
SELECT MAX(UID) INTO MAX_UID FROM ACCOUNT;
SELECT MIN(UID) INTO MIN_UID FROM ACCOUNT;
SELECT MAX(ALBUM_ID) INTO MAX_AID FROM ALBUM;
SELECT MIN(ALBUM_ID) INTO MIN_AID FROM ALBUM;
FOR i IN MIN_UID..MAX_UID LOOP
    DECLARE
    ALBUMS INT := floor(random() * (MOST-LEAST+1) + LEAST)::int; /*fixme temporary increase for final submission*/
    TAKEN INT ARRAY;
    BEGIN
    FOR counter IN 1..ALBUMS LOOP
        DECLARE
        RAND_ALBUM INT :=  floor(random() * (MAX_AID-MIN_AID+1) + MIN_AID)::int;
        BEGIN
        BEGIN
    /*Ensure no duplicates for this user*/
        WHILE RAND_ALBUM = ANY(TAKEN) LOOP
            RAND_ALBUM := floor(random() * (MAX_AID-MIN_AID+1) + MIN_AID)::int;
            END LOOP;
        END;
        TAKEN = array_append(TAKEN,RAND_ALBUM);
        INSERT INTO COLLECTION_ALBUM(ALBUM_ID,UID)
        VALUES(RAND_ALBUM,i);
        END;
    END LOOP;
END;
END LOOP;
END;
$$ LANGUAGE plpgsql;

/*Generate initial collection_artist data*/
DO $$
DECLARE
MAX_UID INT;
MIN_UID INT;
MAX_ARID INT;
MIN_ARID INT;
MOST INT := 100; /*fixme temporary increase for final submission*/
LEAST INT := 0;

BEGIN
SELECT MAX(UID) INTO MAX_UID FROM ACCOUNT;
SELECT MIN(UID) INTO MIN_UID FROM ACCOUNT;
SELECT MAX(ARTIST_ID) INTO MAX_ARID FROM ARTIST;
SELECT MIN(ARTIST_ID) INTO MIN_ARID FROM ARTIST;
FOR i IN MIN_UID..MAX_UID LOOP
    DECLARE
    ARTISTS INT := floor(random() * (MOST-LEAST+1) + LEAST)::int; --20 is good upperbound according to prof brown
    TAKEN INT ARRAY;
    BEGIN
    FOR counter IN 1..ARTISTS LOOP
        DECLARE
        RAND_ARTIST INT :=  floor(random() * (MAX_ARID-MIN_ARID+1) + MIN_ARID)::int;
        BEGIN
        BEGIN
    /*Ensure no duplicates for this user*/
        WHILE RAND_ARTIST = ANY(TAKEN) LOOP
            RAND_ARTIST := floor(random() * (MAX_ARID-MIN_ARID+1) + MIN_ARID)::int;
            END LOOP;
        END;
        TAKEN = array_append(TAKEN,RAND_ARTIST);
        INSERT INTO COLLECTION_ARTIST(ARTIST_ID,UID)
        VALUES(RAND_ARTIST,i);
        END;
    END LOOP;
END;
END LOOP;
END;
$$ LANGUAGE plpgsql;