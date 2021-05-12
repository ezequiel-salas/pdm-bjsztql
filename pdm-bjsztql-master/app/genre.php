<?php
    include('sql.php');
    include('header.php');
    include('sidenav.php');
    ?>
<h2><?php echo $_GET["genre"]?></h2>

<?php
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

$page_genre = $_GET["genre"];

$max_page = ceil(countAlbumsByGenre($page_genre) / $pagelen) - 1;
$nextpage = min($page + 1, $max_page);
$prevpage = max($page - 1, 0);

$friendly_this_page = $page + 1;
$friendly_max_page = $max_page + 1;
echo "<div class = 'generalInfo'>";
echo "<div class = 'myBox'>";
if ($sort == 'playcount') {
    echo "Sorting by Global Play Count\n";
    $result = getAlbumsByGenrePlayCount($page_genre, $pagelen, $page);
} else if ($sort == 'alpha') {
    echo "Sorting Alphabetically\n";
    $result = getAlbumsByGenreAlpha($page_genre, $pagelen, $page);
} else if ($sort == 'reldate') {
    echo "Sorting by Release Date";
    $result = getAlbumsByGenreReleaseDate($page_genre, $pagelen, $page);
}

echo "<table>";
if ($_SESSION['logged_in']) {
    echo "<tr><td><b>Collection</b></td>";
} else {
    echo "<tr>";
}
echo "<td><b>Album</b></td><td><b>Artist</b></td><td><b>Genres</b></td><td><b>Release Date</b></td><td><b>Global Play Count</b></td></tr>";
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    $album = $line["album"];
    $artist = $line["artist"];
    $playcount = $line["play_count"];
    $genres = explode(',', $line["genres"]);
    $reldate = $line["release_date"];
    echo "<tr>";
    if ($_SESSION['logged_in']) {
        if (isAlbumInCollection($album) == 't') {
            echo "<td><a href='genre.php?genre=$page_genre&page=$page&sort=$sort&action=removeAlbum&album=$album'>➖</a></td>";
        } else {
            echo "<td><a href='genre.php?genre=$page_genre&page=$page&sort=$sort&action=addAlbum&album=$album'>➕</a></td>";
        }
    }
    echo "<td><a href='album.php?album=$album&artist=$artist&reldate=$reldate'>$album</a></td>";
    echo "<td><a href='artist.php?artist=$artist'>$artist</a></td>";
    echo "<td>";
    foreach($genres as $genre) {
        echo "<a href='genre.php?genre=$genre'>$genre</a>&ensp;";
    }
    echo "</td>";
    echo "<td>$reldate</td>";
    echo "<td>$playcount</td>";
    echo "</tr>";
}

echo "</table>";


echo "<br>Page $friendly_this_page of $friendly_max_page: ";

if ($page != 0) {
    echo "<a href='genre.php?genre=$page_genre&page=$prevpage&sort=$sort'>Prev</a>&ensp;";
}

if ($page != $max_page) {
    echo "<a href='genre.php?genre=$page_genre&page=$nextpage&sort=$sort'>Next</a>";
}

echo "<br>Sort: ";
echo "<a href='genre.php?genre=$page_genre&page=0&sort=playcount'>By Global Play Count</a>&ensp;";
echo "<a href='genre.php?genre=$page_genre&page=0&sort=alpha'>Alphabetically</a>&ensp;";
echo "<a href='genre.php?genre=$page_genre&page=0&sort=reldate'>By Release Date</a>";
echo "</div></div>";
?>