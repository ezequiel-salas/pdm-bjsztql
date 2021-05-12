<?php
    include('sql.php');
    include('header.php');
    include('sidenav.php');
    ?>
<h2 class ="addText">All Artists</h2>

<?php
echo "<div class = 'generalInfo'>";
echo "<div class = 'myBox'>";
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

$max_page = ceil(countArtists() / $pagelen) - 1;
$nextpage = min($page + 1, $max_page);
$prevpage = max($page - 1, 0);

$friendly_this_page = $page + 1;
$friendly_max_page = $max_page + 1;

if (isset($_GET["action"])) {
    processAction();
    #echo "<meta http-equiv='Refresh' content='0'; url=./albums.php?page=$page&sort=$sort>";
    echo "<script>location.assign('artists.php?page=$page&sort=$sort');</script>";
}

if ($sort == 'playcount') {
    echo "<h3>Sorting by Global Play Count</h3>";
    $result = getArtistsPlayCount($pagelen, $page);
} else if ($sort == 'alpha') {
    echo "<h3>Sorting Alphabetically</h3>";
    $result = getArtistsAlpha($pagelen, $page);
}

echo "<table>";

if ($_SESSION['logged_in']) {
    echo "<tr><td><b>Collection</b></td>";
} else {
    echo "<tr>";
}
echo "<td><b>Artist</b></td><td><b>Global Play Count</b></td></tr>";
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    $artist = $line["artist"];
    $playcount = $line["play_count"];
    echo "<tr>";
    if ($_SESSION['logged_in']) {
        if (isArtistInCollection($artist)) {
            echo "<td><a href='artists.php?page=$page&sort=$sort&action=removeArtist&artist=$artist'>➖</a></td>";
        } else {
            echo "<td><a href='artists.php?page=$page&sort=$sort&action=addArtist&artist=$artist'>➕</a></td>";
        }
    }
    echo "<td><a href='artist.php?artist=$artist' class='link'>$artist</a></td>";
    echo "<td>$playcount";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<br>Page $friendly_this_page of $friendly_max_page: ";

if ($page != 0) {
    echo "<a href='artists.php?page=$prevpage&sort=$sort' class='pagebuttn'>Prev</a>&ensp;";
}

if ($page != $max_page) {
    echo "<a href='artists.php?page=$nextpage&sort=$sort' class='pagebuttn'>Next</a>";
}

echo "<br>Sort: ";
echo "<a href='artists.php?page=0&sort=playcount'>By Global Play Count</a>&ensp;";
echo "|   ";
echo "<a href='artists.php?page=0&sort=alpha'>Alphabetically</a>";
echo "</div>";
echo "</div>";
?>