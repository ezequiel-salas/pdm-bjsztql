INSERT INTO SONG (
    DURATION, NAME
)
SELECT DURATION, SONG
FROM DATA;

INSERT INTO GENRE (
    NAME
)
SELECT DISTINCT GENRE
FROM DATA;

INSERT INTO ARTIST(
    NAME
)
SELECT DISTINCT ARTIST
FROM DATA;

INSERT INTO ALBUM(
    NAME, RELEASE_DATE
)
SELECT DISTINCT ALBUM, RELEASE_DATE
FROM DATA;

INSERT INTO ACCOUNT(
    USER_NAME
)
SELECT USER_NAME
FROM ACCOUNT_DATA;