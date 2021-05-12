<?php
    include('sql.php');
    include('header.php');
    include('sidenav.php');
    ?>
<h2 class ="addText">All Genres</h2>

<?php
echo "<div class = 'generalInfo'>";
echo "<div class = 'myBox'>";
$result = getGenres();

echo "<table>";
echo "<tr><td><b>Genre</b></td><td><b>Global Play Count</b></td></tr>";
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
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
?>