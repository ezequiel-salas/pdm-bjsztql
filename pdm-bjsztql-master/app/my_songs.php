<?php include('sql.php');
 include('header.php');
 include('sidenav.php');
 ?>

<h2>My Songs</h2>

<?php
echo "<div class = 'generalInfo'>";
echo "<div class = 'myBox'>";
if (isset($_GET["action"])) {
    processAction();
}

$pagelen = 30;

if (!isset($_GET["sort"])) {
    $sort = 'playcount';
} else {
    $sort = $_GET["sort"];
}

if (!isset($_GET["page"])) {
    $page = 0;
} else {
    $page = $_GET["page"];
}

$max_page = ceil(countCollectionSongs() / $pagelen) - 1;
$nextpage = min($page + 1, $max_page);
$prevpage = max($page - 1, 0);

$friendly_this_page = $page + 1;
$friendly_max_page = $max_page + 1;

if ($sort == 'playcount') {
    echo "Sorting by My Play Count\n";
    $result = getCollectionSongsPlayCount($pagelen, $page);
} else if ($sort == 'alpha') {
    echo "Sorting Alphabetically\n";
    $result = getCollectionSongsAlpha($pagelen, $page);
}

echo "<table>";
if ($_SESSION['logged_in']) {
    echo "<tr><td><b>Play</b></td><td><b>Collection</b></td>";
} else {
    echo "<tr>";
}
echo "<td><b>Song</b></td><td><b>Length</b></td><td><b>Album</b></td><td><b>Artist</b></td><td><b>Genre</b></td><td><b>My Plays</b></td><td><b>Global Plays</b></td></tr>";
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    $song = $line["song"];
    $album = $line["album"];
    $artist = $line["artist"];
    $playcount = $line["play_count"];
    $genre = $line["genre"];
    $songlength = $line["song_length"];
    $playcount_me = $line["play_count_user"];
    echo "<tr>";
    if ($_SESSION['logged_in']) {
        echo "<td><a href='my_songs.php?page=$page&sort=$sort&action=play&song=$song&album=$album&artist=$artist'>▶️</a></td>";
        if (isSongInCollection($song, $album, $artist)) {
            echo "<td><a href='my_songs.php?page=$page&sort=$sort&action=removeSong&song=$song&album=$album&artist=$artist&duration=$songlength'>➖</a></td>";
        } else {
            echo "<td><a href='my_songs.php?page=$page&sort=$sort&action=addSong&song=$song&album=$album&artist=$artist&duration=$songlength'>➕</a></td>";
        }
    }
    echo "<td>$song</td>";
    echo "<td>$songlength</td>";
    echo "<td><a href='album.php?album=$album&artist=$artist'>$album</a></td>";
    echo "<td><a href='artist.php?artist=$artist'>$artist</a></td>";
    echo "<td><a href='genre.php?genre=$genre'>$genre</a></td>";
    echo "<td>$playcount_me</td>";
    echo "<td>$playcount</td>";
    echo "</tr>";
}
echo "</table>";

echo "<br>Page $friendly_this_page of $friendly_max_page: ";

if ($page != 0) {
    echo "<a href='my_songs.php?page=$prevpage&sort=$sort'>Prev</a>&ensp;";
}

if ($page != $max_page) {
    echo "<a href='my_songs.php?page=$nextpage&sort=$sort'>Next</a>";
}

echo "<br>Sort: ";
echo "<a href='my_songs.php?page=0&sort=playcount'>By My Play Count</a>&ensp;";
echo "|   ";
echo "<a href='my_songs.php?page=0&sort=alpha'>Alphabetically</a>";
echo "</div></div>";
?>