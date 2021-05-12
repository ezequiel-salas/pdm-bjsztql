<?php
    session_start();
    // Reddwarf Connection String
     //$conn = pg_connect("host=reddwarf.cs.rit.edu dbname=p320_05 user=p320_05 password=leeya4beenah8foQuaiz")
       //  or die('Could not connect: ' . pg_last_error());
    // Localhost Connection String
    $conn = pg_connect("host=postgres dbname=postgres user=postgres password=password")
       or die('Could not connect: ' . pg_last_error());
        
    function printResult($result) {
            echo "<table>\n";
            while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                echo "\t<tr>\n";
                foreach ($line as $col_value) {
                    echo "\t\t<td>$col_value</td>\n";
                }
                echo "\t</tr>\n";
            }
            echo "</table>\n";
    }

    //GENERAL FUNCTIONS
    
    function doesUserExist($username) {
        $result = pg_query('SELECT \'' . $username . '\' IN (SELECT user_name FROM account)');
        return pg_fetch_row($result)[0];
    }

    function getReleaseDate($album) {
        $result = pg_query("SELECT to_char(release_date, 'YYYY-MM-DD') as release_date FROM album WHERE name = '$album'");
        return pg_fetch_row($result)[0];
    }

    //ARTISTS
    function countArtists() {
        $result = pg_query('SELECT COUNT(*) FROM artist');
        return pg_fetch_row($result)[0];
    }

    function getArtistsPlayCount($number, $page) {
        return pg_query("SELECT * from get_artists_play_count($number,$page)");
    }

    function getArtistsAlpha($number, $page) {
        return pg_query("SELECT * from get_artists_alpha($number,$page)");
    }

    //ALBUMS
    function countAlbums() {
        $result = pg_query('SELECT COUNT(*) FROM album');
        return pg_fetch_row($result)[0];
    }

    function getAlbumsPlayCount($number, $page) {
        return pg_query('SELECT album, artist, play_count, genres, to_char(release_date, \'YYYY-MM-DD\') as release_date from get_albums_play_count(' . $number . ',' . $page . ')');
    }

    function getAlbumsAlpha($number, $page) {
        return pg_query('SELECT album, artist, play_count, genres, to_char(release_date, \'YYYY-MM-DD\') as release_date from get_albums_alpha(' . $number . ',' . $page . ')');
    }

    function getAlbumsReleaseDate($number, $page) {
        return pg_query('SELECT album, artist, play_count, genres, to_char(release_date, \'YYYY-MM-DD\') as release_date from get_albums_release_date(' . $number . ',' . $page . ')');
    }

    //ALBUMS BY GENRE
    function countAlbumsByGenre($genre) {
        $result = pg_query('SELECT COUNT(*) FROM album WHERE album_id IN (SELECT album_id FROM song_album WHERE song_id IN (SELECT song_id FROM song_genre WHERE genre_id = (SELECT genre_id FROM genre WHERE name = \'' . $genre . '\')))');
        return pg_fetch_row($result)[0];
    }

    function getAlbumsByGenrePlayCount($genre, $number, $page) {
        return pg_query('SELECT album, artist, play_count, to_char(release_date, \'YYYY-MM-DD\') as release_date from get_albums_by_genre_play_count(\'' . $genre . '\',' . $number . ',' . $page . ')');
    }

    function getAlbumsByGenreAlpha($genre, $number, $page) {
        return pg_query('SELECT album, artist, play_count, to_char(release_date, \'YYYY-MM-DD\') as release_date from get_albums_by_genre_alpha(\'' . $genre . '\',' . $number . ',' . $page . ')');
    }

    function getAlbumsByGenreReleaseDate($genre, $number, $page) {
        return pg_query('SELECT album, artist, play_count, to_char(release_date, \'YYYY-MM-DD\') as release_date from get_albums_by_genre_release_date(\'' . $genre . '\',' . $number . ',' . $page . ')');
    }

    //ALBUMS BY ARTIST
    function countAlbumsByArtist($artist) {
        $result = pg_query('SELECT COUNT(*) FROM album WHERE album_id IN (SELECT album_id FROM song_album WHERE song_id IN (SELECT song_id FROM song_artist WHERE artist_id = (SELECT artist_id FROM artist WHERE name = \'' . $artist . '\')))');
        return pg_fetch_row($result)[0];
    }

    function getAlbumsByArtistPlayCount($artist, $number, $page) {
        return pg_query('SELECT album, genres, play_count, to_char(release_date, \'YYYY-MM-DD\') as release_date from get_albums_by_artist_play_count(\'' . $artist . '\',' . $number . ',' . $page . ')');
    }

    function getAlbumsByArtistAlpha($artist, $number, $page) {
        return pg_query('SELECT album, genres, play_count, to_char(release_date, \'YYYY-MM-DD\') as release_date from get_albums_by_artist_alpha(\'' . $artist . '\',' . $number . ',' . $page . ')');
    }

    function getAlbumsByArtistReleaseDate($artist, $number, $page) {
        return pg_query('SELECT album, genres, play_count, to_char(release_date, \'YYYY-MM-DD\') as release_date from get_albums_by_artist_release_date(\'' . $artist . '\',' . $number . ',' . $page . ')');
    }

    //SONGS
    function countSongs() {
        $result = pg_query('SELECT COUNT(*) FROM song');
        return pg_fetch_row($result)[0];
    }

    function getSongsPlayCount($number, $page) {
        return pg_query('SELECT song, to_char(song_length, \'MI:SS\') as song_length, album, artist, genre, play_count from get_songs_play_count(' . $number . ',' . $page . ')');
    }

    function getSongsAlpha($number, $page) {
        return pg_query('SELECT song, to_char(song_length, \'MI:SS\') as song_length, album, artist, genre, play_count from get_songs_alpha(' . $number . ',' . $page . ')');
    }
    
    function getGenres() {
        return pg_query('SELECT * FROM get_genres() ORDER BY play_count DESC');
    }

    function getSongsInAlbum($name) {
        return pg_query('SELECT track_number, song, play_count, to_char(song_length, \'MI:SS\') as song_length, genre FROM get_songs_in_album(\'' . $name . '\')');
    }

    //USER COLLECTIONS
    function countCollectionSongs() {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        $result = pg_query("SELECT COUNT(*) FROM collection_song WHERE uid = $uid");
        return pg_fetch_row($result)[0];
    }

    function countCollectionAlbums() {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        $result = pg_query("SELECT COUNT(*) FROM collection_album WHERE uid = $uid");
        return pg_fetch_row($result)[0];
    }

    function countCollectionArtists() {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        $result = pg_query("SELECT COUNT(*) FROM collection_artist WHERE uid = $uid");
        return pg_fetch_row($result)[0];
    }

    function getCollectionAlbumsAlpha($number, $page) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        return pg_query('SELECT album, artist, play_count_total as play_count, play_count_user, genres, to_char(release_date, \'YYYY-MM-DD\') as release_date from get_collection_albums_alpha(' . $number . ',' . $page . ',' . $uid . ')');
    }

    function getCollectionAlbumsPlayCount($number, $page) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        return pg_query('SELECT album, artist, play_count_total as play_count, play_count_user, genres, to_char(release_date, \'YYYY-MM-DD\') as release_date from get_collection_albums_play_count(' . $number . ',' . $page . ',' . $uid . ')');
    }

    function getCollectionAlbumsReleaseDate($number, $page) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        return pg_query('SELECT album, artist, play_count_total as play_count, play_count_user, genres, to_char(release_date, \'YYYY-MM-DD\') as release_date from get_collection_albums_release_date(' . $number . ',' . $page . ',' . $uid . ')');
    }

    function getCollectionSongsAlpha($number, $page) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        return pg_query('SELECT song, to_char(song_length, \'MI:SS\') as song_length, album, artist, genre, play_count_user, play_count_total as play_count from get_collection_songs_alpha(' . $number . ',' . $page . ',' . $uid . ')');
    }

    function getCollectionSongsPlayCount($number, $page) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        return pg_query('SELECT song, to_char(song_length, \'MI:SS\') as song_length, album, artist, genre, play_count_user, play_count_total as play_count from get_collection_songs_play_count(' . $number . ',' . $page . ',' . $uid . ')');
    }

    function getCollectionArtistsAlpha($number, $page) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        return pg_query('SELECT artist, play_count_total as play_count, play_count_user from get_collection_artists_alpha(' . $number . ',' . $page . ',' . $uid . ')');
    }
    
    function getCollectionArtistsPlayCount($number, $page) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        return pg_query('SELECT artist, play_count_total as play_count, play_count_user from get_collection_artists_play_count(' . $number . ',' . $page . ',' . $uid . ')');
    }

    //User interaction functions
    function processAction() {
        switch ($_GET["action"]) {
            case "play":
                playSong($_GET["song"], $_GET["album"], $_GET["artist"]);
                break;
            case "addSong":
                addSongToCollection($_GET["song"], $_GET["album"], $_GET["artist"], $_GET["duration"]);
                break;
            case "addArtist":
                addArtistToCollection($_GET["artist"]);
                break;
            case "addAlbum":
                addAlbumToCollection($_GET["album"]);
                break;
            case "removeSong":
                removeSongFromCollection($_GET["song"], $_GET["album"], $_GET["artist"], $_GET["duration"]);
                break;
            case "removeArtist":
                removeArtistFromCollection($_GET["artist"]);
                break;
            case "removeAlbum":
                removeAlbumFromCollection($_GET["album"]);
                break;
        }
    }

    function playSong($song, $album, $artist) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        pg_query("INSERT INTO song_play
        (
            song_id,
            uid,
            ts
        )
        SELECT
            s.song_id,
            $uid,
            now()
        FROM song AS s
        INNER JOIN song_artist sa
        ON s.song_id = sa.song_id
        WHERE s.name = '$song'
          AND sa.artist_id = (SELECT artist_id FROM artist WHERE name = '$artist');
        ");
        echo "Played $song on $album by $artist.<br><br>";
    }

    function isSongInCollection($song, $album, $artist) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        $result = pg_query("select *
        from collection_song as cs
        inner join song s ON cs.song_id = s.song_id
        inner join song_album as sa on sa.song_id = s.song_id
        inner join album as alb on alb.album_id = sa.album_id
        where s.name = '$song' and alb.name = '$album' and cs.uid = $uid");
        return pg_fetch_row($result)[0] == $uid;
    }

    function isArtistInCollection($artist) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        $artist = str_replace("'", "''", $artist);
        $result = pg_query("select *
        from collection_artist as ca
        inner join artist a ON ca.artist_id = a.artist_id
        where a.name = '$artist' and ca.uid = $uid");
        return pg_fetch_row($result)[0] == $uid;
    }

    function isAlbumInCollection($album) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        $result = pg_query("select *
        from collection_album as ca
        inner join album a ON ca.album_id = a.album_id
        where a.name = '$album' and ca.uid = $uid");
        return pg_fetch_row($result)[0] == $uid;
    }

    function addSongToCollection($song, $album, $artist, $duration) {
        $temp = explode(":",$duration);
        $mins = $temp[0];
        $seconds = $temp[1];
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        pg_query("SELECT * FROM add_collection_song($uid, '$song', '$album', '$artist', make_interval(0,0,0,0,0,$mins,$seconds))");
        echo "Added $song on $album by $artist.<br><br>";
    }

    function addArtistToCollection($artist) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        pg_query("SELECT * FROM add_collection_artist($uid, '$artist')");
        echo "Added artist $artist.<br><br>";
    }

    function addAlbumToCollection($album) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        pg_query("SELECT * FROM add_collection_album($uid, '$album')");
        echo "Added album $album.<br><br>";
    }

    function removeSongFromCollection($song, $album, $artist, $duration) {
        $temp = explode(":",$duration);
        $mins = $temp[0];
        $seconds = $temp[1];
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        pg_query("SELECT * FROM remove_collection_song($uid, '$song', '$album', '$artist', make_interval(0,0,0,0,0,$mins,$seconds))");
        echo "Removed $song on $album by $artist.<br><br>";
    }

    function removeArtistFromCollection($artist) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        pg_query("SELECT * FROM remove_collection_artist($uid, '$artist')");
        echo "Removed artist $artist.<br><br>";
    }

    function removeAlbumFromCollection($album) {
        $uid = pg_fetch_row(pg_query('select uid from account where user_name = \'' . $_SESSION["username"] . '\''))[0];
        pg_query("SELECT * FROM remove_collection_album($uid, '$album')");
        echo "Removed album $album.<br><br>";
    }

    //ADD PAGE
    function createUser($uname) {
        global $conn;
        pg_send_query($conn, "INSERT INTO account
        (
            user_name
        )
        VALUES
        (
            '$uname'
        );");
        $result = pg_get_result($conn);
        $state = pg_result_error_field($result, PGSQL_DIAG_SQLSTATE);
        if ($state == 0) {
            echo "User '$uname' added! <br>";
        } else if ($state == 23505) {
            echo "ERROR: User '$uname' already exists! <br>";
        } else {
            echo "ERROR: SQLSTATE $state! <br>";
        }
    }

    function createArtist($name) {
        global $conn;
        pg_send_query($conn, "SELECT * FROM create_artist('$name')");
        $result = pg_get_result($conn);
        $state = pg_result_error_field($result, PGSQL_DIAG_SQLSTATE);
        echo pg_fetch_row($result)[0];
        if ($state == 0) {
            echo pg_fetch_row($result)[0] . "<br>";
        } else if ($state == 23505) {
            echo "Artist '$name' already exists, not creating. <br>";
        } else {
            echo "ERROR: SQLSTATE $state! <br>";
        } 
    }

    function createAlbum($album_name, $artist_name) {
        global $conn;
        pg_send_query($conn, "SELECT * FROM create_album('$album_name', '$artist_name')");
        $result = pg_get_result($conn);
        $state = pg_result_error_field($result, PGSQL_DIAG_SQLSTATE);
        if ($state == 0) {
            echo pg_fetch_row($result)[0] . "<br>";
        } else if ($state == 23505) {
            echo "Album '$album_name' already exists, not creating. <br>";
        } else {
            echo "ERROR: SQLSTATE $state! <br>";
        }
    }

    function createGenre($gname) {
        global $conn;
        pg_send_query($conn, "SELECT * FROM create_genre('$gname')");
        $result = pg_get_result($conn);
        $state = pg_result_error_field($result, PGSQL_DIAG_SQLSTATE);
        if ($state == 0) {
            echo pg_fetch_row($result)[0] . "<br>";
        } else if ($state == 23505) {
            echo "Genre '$gname' already exists, not creating. <br>";
        } else {
            echo "ERROR: SQLSTATE $state! <br>";
        }
    }

    function createSong($song_name, $artist_name, $album_name, $genre_name, $song_length) {
        global $conn;
        if ($album_name == '') {
            $album_name = 'NULL';
        } else {
            $album_name = "'$album_name'";
        }
        $ss = explode(':', $song_length);
        $mins = $ss[0];
        $seconds = $ss[1];
        pg_send_query($conn, "SELECT * FROM create_song('$song_name', '$artist_name', $album_name, '$genre_name', make_interval(0,0,0,0,0,$mins,$seconds))");
        $result = pg_get_result($conn);
        $state = pg_result_error_field($result, PGSQL_DIAG_SQLSTATE);
        if ($state == 0) {
            echo pg_fetch_row($result)[0] . "<br>";
        } else {
            echo "ERROR: SQLSTATE $state! <br>";
        }
    }


    function get_monthly_genre_plays($genre){
        return pg_query("SELECT * FROM get_monthly_genre_plays('$genre')");
    }
    function get_monthly_artist_plays($artist){
        return pg_query("SELECT * FROM get_monthly_artist_plays('$artist')");
    }
    function get_monthly_album_play($album){
        return pg_query("SELECT * FROM get_monthly_album_play('$album')");
    }
    function get_monthly_song_play($song,$artist){
        return pg_query("SELECT * FROM get_monthly_song_play('$song','$artist')");
    }
    function get_recommended_albums_from_genre($num, $username){
        return pg_query("SELECT * FROM get_recommended_albums_from_genre('$num','$username')");
    }
    function get_recommended_songs_from_genre($num, $username){
        return pg_query("SELECT * FROM get_recommended_songs_from_genre('$num','$username')");
    }
    function get_recommended_artist_from_genre($num, $username){
        return pg_query("SELECT * FROM get_recommended_artist_from_genre('$num','$username')");
    }

    //SEARCH
    function search_songs($qstring) {
        return pg_query("SELECT * FROM search_songs('$qstring')");
    }

    function search_albums($qstring) {
        return pg_query("SELECT * FROM search_albums('$qstring')");
    }

    function search_genres($qstring) {
        return pg_query("SELECT * FROM search_genres('$qstring')");
    }

    function search_artists($qstring) {
        return pg_query("SELECT * FROM search_artists('$qstring')");
    }
?>