CREATE TABLE ACCOUNT (
    UID INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    USER_NAME VARCHAR(40) UNIQUE
);

CREATE TABLE ARTIST (
    ARTIST_ID INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    NAME VARCHAR(40) UNIQUE
);

CREATE TABLE GENRE (
    GENRE_ID INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    NAME VARCHAR(40) UNIQUE
);

CREATE TABLE ALBUM (
    ALBUM_ID INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    NAME VARCHAR(40) UNIQUE,
    RELEASE_DATE TIMESTAMP
);

CREATE TABLE SONG (
    SONG_ID INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    DURATION INTERVAL,
    NAME VARCHAR(40)
);

CREATE TABLE ALBUM_ARTIST (
    ALBUM_ID INT REFERENCES ALBUM(ALBUM_ID),
    ARTIST_ID INT REFERENCES ARTIST(ARTIST_ID)
);

CREATE TABLE ALBUM_GENRE (
    ALBUM_ID INT REFERENCES ALBUM(ALBUM_ID),
    GENRE_ID INT REFERENCES GENRE(GENRE_ID)
);

CREATE TABLE SONG_ARTIST (
    SONG_ID INT REFERENCES SONG(SONG_ID),
    ARTIST_ID INT REFERENCES ARTIST(ARTIST_ID)
);

CREATE TABLE SONG_GENRE (
    SONG_ID INT REFERENCES SONG(SONG_ID),
    GENRE_ID INT REFERENCES GENRE(GENRE_ID)
);

CREATE TABLE SONG_ALBUM (
    SONG_ID INT REFERENCES SONG(SONG_ID),
    ALBUM_ID INT REFERENCES ALBUM(ALBUM_ID),
    TRACK_NUMBER INT
);

CREATE TABLE SONG_PLAY (
    SONG_ID INT REFERENCES SONG(SONG_ID),
    UID INT REFERENCES ACCOUNT(UID),
    TS TIMESTAMP
);

CREATE TABLE COLLECTION_ALBUM (
    UID INT REFERENCES ACCOUNT(UID),
    ALBUM_ID INT REFERENCES ALBUM(ALBUM_ID),
    UNIQUE (UID, ALBUM_ID)
);

CREATE TABLE COLLECTION_ARTIST (
    UID INT REFERENCES ACCOUNT(UID),
    ARTIST_ID INT REFERENCES ARTIST(ARTIST_ID),
    UNIQUE (UID, ARTIST_ID)
);

CREATE TABLE COLLECTION_SONG (
    UID INT REFERENCES ACCOUNT(UID),
    SONG_ID INT REFERENCES SONG(SONG_ID),
    UNIQUE (UID, SONG_ID)
);
