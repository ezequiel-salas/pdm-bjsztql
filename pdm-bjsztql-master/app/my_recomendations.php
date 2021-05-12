<?php include('sql.php');
 include('header.php');
 include('sidenav.php');
 ?>
<h2>Your recommendations</h2>

<?php
if (isset($_GET["action"])) {
    processAction();
    #echo "<meta http-equiv='Refresh' content='0'; url=./albums.php?page=$page&sort=$sort>";
    echo "<script>location.assign('my_recomendations.php);</script>";
}
$rec_albums = get_recommended_albums_from_genre(10, $_SESSION["username"]);
$rec_songs = get_recommended_songs_from_genre(10, $_SESSION["username"]);
$rec_artists = get_recommended_artist_from_genre(10, $_SESSION["username"]);
echo "<div class = 'generalInfo'>";
    echo "<div class = 'grid'>";
            echo "<div class = 'myBox'>";
            echo "Most Recommended Songs\n";
                echo "<table>";
                echo "<tr><td><b>Play</b></td><td><b>Collection</b></td><td><b>Song</b></td><td><b>Artist</b></td></tr>";
                while ($line = pg_fetch_array($rec_songs, null, PGSQL_ASSOC)) {
                    $song = $line["song"];
                    $artist = $line["artist"];
                    $album = $line["album"];
                    $temp = explode(":",$line["duration"]);
                    $songlength = $temp[1] . ":" . $temp[2];
                    $genre = $line["genre"];
                    echo "<tr>";
                    echo "<td><a href='my_recomendations.php?action=play&song=$song&album=$album&artist=$artist'>▶️</a></td>";
                    if (isSongInCollection($song, $album, $artist)) {
                        echo "<td><a href='my_recomendations.php?action=removeSong&song=$song&album=$album&artist=$artist&duration=$songlength'>➖</a></td>";
                    } else {
                        echo "<td><a href='my_recomendations.php?action=addSong&song=$song&album=$album&artist=$artist&duration=$songlength'>➕</a></td>";
                    }
                    echo "<td>$song</td>";
                    echo "<td><a href='artist.php?artist=$artist'>$artist</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
        echo "</div>";
        echo "<div class = 'myBox'>";
            echo "Most Recommended Albums\n";
                echo "<table>";
                echo "<tr><td><b>Collection</b></td><td><b>Album</b></td><td><b>Artist</b></td><td><b>Release Date</b></td></tr>";
                while ($line = pg_fetch_array($rec_albums, null, PGSQL_ASSOC)){
                    $album = $line["album"];
                    $artist = $line["artist"];
                    $play_date = explode(" ",$line["release_date"])[0];
                    echo "<tr>";
                    if (isAlbumInCollection($album)) {
                        echo "<td><a href=my_recomendations.php?action=removeAlbum&album=$album'>➖</a></td>";
                    } else {
                    echo "<td><a href='my_recomendations.php?action=addAlbum&album=$album'>➕</a></td>";
                    }
                    echo "<td><a href='album.php?album=$album&artist=$artist&reldate=$reldate'>$album</a></td>";
                    echo "<td><a href='artist.php?artist=$artist'>$artist</a></td>";
                    echo "<td>$play_date</td>";
                    echo "</tr>";
                }
                echo "</table>";
        echo "</div>";
        echo "<div class = 'myBox'>";
            echo "Most Recommended Artists\n";
                echo "<table>";
                echo "<tr><td><b>Collection</b></td><td><b>Artist</b></td></tr>";
                while ($line = pg_fetch_array($rec_artists, null, PGSQL_ASSOC)) {
                    $artist = $line["artist_name"];
                    echo "<tr>";
                    if ($_SESSION['logged_in']) {
                    if (isArtistInCollection($artist)) {
                        echo "<td><a href='my_recomendations.php?action=removeArtist&artist=$artist'>➖</a></td>";
                    } else {
                        echo "<td><a href='my_recomendations.php?action=addArtist&artist=$artist'>➕</a></td>";
                    }
                    }
                    echo "<td><a href='artist.php?artist=$artist' class='link'>$artist</a></td>";
                    echo "<td>\t</td>";
                    echo "</tr>";
                }
                echo "</table>";
        echo "</div>";
    echo "</div>";
echo "</div>";
?>