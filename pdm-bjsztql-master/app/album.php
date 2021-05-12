<?php
    include('sql.php');
    include('header.php');
    include('sidenav.php');
?>
<h2><?php echo $_GET["album"] ?></h2>

<?php
$album = $_GET["album"];
$artist = $_GET["artist"];
$reldate = getReleaseDate($album);

if (isset($_GET["action"])) {
    processAction();
}
if ($_SESSION['logged_in']) {
    echo "<b>Album: </b>";
    if (isAlbumInCollection($album) == "t") {
        echo "<a href='album.php?album=$album&artist=$artist&reldate=$reldate&action=removeAlbum'class='link'> ➖ from Collection</a>";
    } else {
        echo "<a href='album.php?album=$album&artist=$artist&reldate=$reldate&action=addAlbum'class='link'> ➕ to Collection</a>";
    }
    echo '<br>';
    echo "<b>Artist</b>: <a href='artist.php?artist=$artist'class='link'>$artist</a>";
    if (isArtistInCollection($artist) == "t") {
        echo "&ensp;&ensp;<a href='album.php?album=$album&artist=$artist&action=removeArtist'class='link'>➖ from Collection</a>";
    } else {
        echo "&ensp;&ensp;<a href='album.php?album=$album&artist=$artist&action=addArtist'class='link'>➕ to Collection</a>";
    }
    echo '<br>';
    echo '<b>Release Date:</b> ' . $reldate . '<br><br>';
} else {
    echo "<b>Artist</b>: <a href='artist.php?artist=$artist'class='link'>$artist</a><br>";
    echo '<b>Release Date:</b> ' . $reldate . '<br><br>';
}

$result = getSongsInAlbum($album);

echo "<div class = 'generalInfo'>";
echo "<div class = 'myBox'>";
echo "<table>";
if ($_SESSION['logged_in']) {
    echo "<tr><td><b>Play</b></td><td><b>Collection</b></td>";
} else {
    echo "<tr>";
}
echo "<td><b>#</b></td><td><b>Name</b></td><td><b>Length</b></td><td><b>Genre</b></td><td><b>Global Play Count</b></td></tr>";
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    $song = $line["song"];
    $playcount = $line["play_count"];
    $genre = $line["genre"];
    $songlength = $line["song_length"];
    $tracknumber = $line["track_number"];
    echo "<tr>";
    if ($_SESSION['logged_in']) {
        echo "<td><a href='album.php?action=play&song=$song&album=$album&artist=$artist'>▶️</a></td>";
        if (isSongInCollection($song, $album, $artist) == 't') {
            echo "<td><a href='album.php?action=removeSong&song=$song&album=$album&artist=$artist&duration=$songlength'>➖</a></td>";
        } else {
            echo "<td><a href='album.php?action=addSong&song=$song&album=$album&artist=$artist&duration=$songlength'>➕</a></td>";
        }
    }
    echo "<td>$tracknumber</td>";
    echo "<td>$song</td>";
    echo "<td>$songlength</td>";
    echo "<td><a href='genre.php?genre=$genre'>$genre</a></td>";
    echo "<td>$playcount</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div></div>";
?>