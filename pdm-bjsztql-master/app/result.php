<?php
    include('sql.php');
    include('header.php');
    include('sidenav.php');

    $query = $_GET['query'];
    if (strlen($query) < 3) {
        echo "<h2>Qurery must have more than 3 chars!</h2>";
        goto no;
    }

    if (isset($_GET["action"])) {
        processAction();
        #echo "<meta http-equiv='Refresh' content='0'; url=./albums.php?page=$page&sort=$sort>";
        echo "<script>location.assign('result.php?query=$query');</script>";
    }

    $songs_result = search_songs($query);
    $genres_result = search_genres($query);
    $albums_result = search_albums($query);
    $artists_result = search_artists($query);

    echo "<h2>Search Results for '$query'</h2>";

    # MATCHING ARTISTS
    echo "<div class = 'generalInfo'>";
    echo "<div class = 'myBox'>";
    echo "<h3>Matching Artists</h3>";

    echo "<table>";
    if ($_SESSION['logged_in']) {
        echo "<tr><td><b>Collection</b></td>";
    } else {
        echo "<tr>";
    }
    echo "<td><b>Artist</b></td><td><b>Global Play Count</b></td></tr>";
    while ($line = pg_fetch_array($artists_result, null, PGSQL_ASSOC)) {
        $artist = $line["artist"];
        $playcount = $line["play_count"];
        echo "<tr>";
        if ($_SESSION['logged_in']) {
            if (isArtistInCollection($artist)) {
                echo "<td><a href='result.php?query=$query&action=removeArtist&artist=$artist'>➖</a></td>";
            } else {
                echo "<td><a href='result.php?query=$query&action=addArtist&artist=$artist'>➕</a></td>";
            }
        }
        echo "<td><a href='artist.php?artist=$artist' class='link'>$artist</a></td>";
        echo "<td>$playcount";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "</div>";
    echo "</div>";

    # MATCHING ALBUMS
    echo "<div class = 'generalInfo'>";
    echo "<div class = 'myBox'>";
    echo "<h3>Matching Albums</h3>";
    
    echo "<table>";
    if ($_SESSION['logged_in']) {
        echo "<tr><td><b>Collection</b></td>";
    } else {
        echo "<tr>";
    }
    echo "<td><b>Album</b></td><td><b>Artist</b></td><td><b>Genres</b></td><td><b>Release Date</b></td><td><b>Global Play Count</b></td></tr>";
    while ($line = pg_fetch_array($albums_result, null, PGSQL_ASSOC)) {
        $album = $line["album"];
        $artist = $line["artist"];
        $playcount = $line["play_count"];
        $genres = explode(',', $line["genres"]);
        $temp = explode(" ", $line["release_date"]);
        $reldate = $temp[0];
        echo "<tr>";
        if ($_SESSION['logged_in']) {
            if (isAlbumInCollection($album)) {
                echo "<td><a href='result.php?query=$query&action=removeAlbum&album=$album'>➖</a></td>";
            } else {
                echo "<td><a href='result.php?query=$query&action=addAlbum&album=$album'>➕</a></td>";
            }
        }
        echo "<td><a href='album.php?album=$album&artist=$artist&reldate=$reldate'>$album</a></td>";
        echo "<td><a href='artist.php?artist=$artist'>$artist</a></td>";
        echo "<td>";
        foreach($genres as $genre) {
            echo "<a href='genre.php?genre=$genre' class='link'>$genre</a>&ensp;";

        }
        echo "</td>";
        echo "<td>$reldate</td>";
        echo "<td>$playcount</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    echo "</div>";

    # MATCHING GENRES
    echo "<div class = 'generalInfo'>";
    echo "<div class = 'myBox'>";
    echo "<h3>Matching Genres</h3>";

    echo "<table>";
    echo "<tr><td><b>Genre</b></td><td><b>Global Play Count</b></td></tr>";
    while ($line = pg_fetch_array($genres_result, null, PGSQL_ASSOC)) {
        $genre = $line["genre"];
        $playcount = $line["play_count"];
        echo "<tr>";
        echo "<td><a href='genre.php?genre=$genre' class='link'>$genre</a></td>";
        echo "<td>$playcount</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    echo "</div>";

    # MATCHING SONGS
    echo "<div class = 'generalInfo'>";
    echo "<div class = 'myBox'>";
    echo "<h3>Matching Songs</h3>";
    echo "<table>";
    if ($_SESSION['logged_in']) {
        echo "<tr><td><b>Play</b></td><td><b>Collection</b></td>";
    } else {
        echo "<tr>";
    }
    echo "<td><b>Song</b></td><td><b>Length</b></td><td><b>Album</b></td><td><b>Artist</b></td><td><b>Genre</b></td><td><b>Global Play Count</b></td></tr>";
    while ($line = pg_fetch_array($songs_result, null, PGSQL_ASSOC)) {
        $song = $line["song"];
        $album = $line["album"];
        $artist = $line["artist"];
        $playcount = $line["play_count"];
        $genre = $line["genre"];
        $temp = explode(":", $line["song_length"]);
        $songlength = $temp[1] . ":" . $temp[2];
        echo "<tr>";
        if ($_SESSION['logged_in']) {
            echo "<td><a href='result.php?query=$query&action=play&song=$song&album=$album&artist=$artist'>▶️</a></td>";
            if (isSongInCollection($song, $album, $artist)) {
                echo "<td><a href='result.php?query=$query&action=removeSong&song=$song&album=$album&artist=$artist&duration=$songlength'>➖</a></td>";
            } else {
                echo "<td><a href='result.php?query=$query&action=addSong&song=$song&album=$album&artist=$artist&duration=$songlength'>➕</a></td>";
            }
        }
        echo "<td>$song</td>";
        echo "<td>$songlength</td>";
        echo "<td><a href='album.php?album=$album&artist=$artist' class='link'>$album</a></td>";
        echo "<td><a href='artist.php?artist=$artist' class='link'>$artist</a></td>";
        echo "<td><a href='genre.php?artist=$genre' class='link'>$genre</a></td>";
        echo "<td>$playcount</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    echo "</div>";
    no:
?>
