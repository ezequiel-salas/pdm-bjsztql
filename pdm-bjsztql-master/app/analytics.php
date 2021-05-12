<?php include('sql.php');
 include('header.php');
 include('sidenav.php');
 ?>
<h2>Analytics</h2>


<?php

if (isset($_POST['g'])) {
    $array = get_monthly_genre_plays($_POST['g']);
    $real_array = array();
    while ($line = pg_fetch_array($array, null, PGSQL_ASSOC)) {
        array_push($real_array,$line);
    }
}
if (isset($_POST['a'])) {
    $array = get_monthly_artist_plays($_POST['a']);
    $real_array = array();
    while ($line = pg_fetch_array($array, null, PGSQL_ASSOC)) {
        array_push($real_array,$line);
    }
}
if (isset($_POST['custom_artist'])) {
    $array = get_monthly_artist_plays($_POST['custom_artist']);
    $real_array = array();
    while ($line = pg_fetch_array($array, null, PGSQL_ASSOC)) {
        array_push($real_array,$line);
    }
}
if (isset($_POST['album'])) {
    $array = get_monthly_album_play($_POST['album']);
    $real_array = array();
    while ($line = pg_fetch_array($array, null, PGSQL_ASSOC)) {
        array_push($real_array,$line);
    }
}
if (isset($_POST['custom_album'])) {
    $array = get_monthly_album_play($_POST['custom_album']);
    $real_array = array();
    while ($line = pg_fetch_array($array, null, PGSQL_ASSOC)) {
        array_push($real_array,$line);
    }
}
if (isset($_POST['song'])) {
    $var = explode(",",$_POST['song']);
    $array = get_monthly_song_play($var[0],$var[1]);
    $real_array = array();
    while ($line = pg_fetch_array($array, null, PGSQL_ASSOC)) {
        array_push($real_array,$line);
    }
}
$genres = getGenres();
$gen = array();
while($line = pg_fetch_array($genres,null,PGSQL_ASSOC)){
    array_push($gen,$line);
}
$genres = getGenres();
$global_artists = getArtistsPlayCount(10,0);
$global_albums = getAlbumsPlayCount(10,0);
$global_songs = getSongsPlayCount(10,0);
echo "<div class = 'generalInfo'>";
echo "<div class = 'grid'>";
echo "<div class = 'myBox'>";
echo '<h3>Pick a genre!</h3>';
echo '<form action="analytics.php" method="post">';
echo '<select name="g" id="g">';
while ($line = pg_fetch_array($genres,null,PGSQL_ASSOC)){
    $genre = $line["genre"];
    echo "<option value='$genre'>$genre</option>";
}
if (isset($_POST['g'])){
    echo "<option selected='selected' selected disabled hidden>" . $_POST['g'] . "</option>";
}
echo '</select>';
echo "<button type='submit'>Submit</button>";
echo "</form>";

echo '<h3>Pick from top 10 Artists!</h3>';
echo '<form action="analytics.php" method="post">';
echo '<select name="a" id="a">';
while ($line = pg_fetch_array($global_artists,null,PGSQL_ASSOC)){
    $artist = $line["artist"];
    echo "<option value='$artist'>$artist</option>";
}
if (isset($_POST['a'])){
    echo "<option selected='selected' selected disabled hidden>" . $_POST['a'] . "</option>";
}
echo '</select>';
echo "<button type='submit'>Submit</button>";
echo "</form>";
echo "<h4>Or you could type the name of an artist and hit enter.</h4>";
echo "</form>";
echo "<form action='analytics.php' method='post'>";
echo "<input type='text' name='custom_artist' id ='custom_artist'><br><br>";
echo "</form>";

echo '<h3>Pick from top 10 Albums!</h3>';
echo '<form action="analytics.php" method="post">';
echo '<select name="album" id="album">';
while ($line = pg_fetch_array($global_albums,null,PGSQL_ASSOC)){
    $album = $line["album"];
    echo "<option value='$album'>$album</option>";
}
if (isset($_POST['album'])){
    echo "<option selected='selected' selected disabled hidden>" . $_POST['album'] . "</option>";
}
echo '</select>';
echo "<button type='submit'>Submit</button>";
echo "</form>";
echo "<h4>Or you could type the name of an album and hit enter.</h4>";
echo "<form action='analytics.php' method='post'>";
echo "<input type='text' name='custom_album' id ='custom_album'><br><br>";
echo "</form>";

echo '<h3>Pick from top 10 Songs!</h3>';
echo '<form action="analytics.php" method="post">';
echo '<select name="song" id="song">';
while ($line = pg_fetch_array($global_songs,null,PGSQL_ASSOC)){
    $song = $line["song"];
    $artist = $line["artist"];
    echo "<option value='$song,$artist' name='$song'>$song</option>";
}
if (isset($_POST['song'])){
    $var = $var = explode(",",$_POST['song']);
    echo "<option selected='selected' selected disabled hidden>" . $var[0] . "</option>";
}
echo '</select>';
echo "<button type='submit'>Submit</button>";
echo "</form>";
echo "</div>";
        echo "<div class='myBarChart' id='myBarChart'></div>";
        echo "<div class='myPieChart' id='myPieChart'></div>";
    echo "</div;>";
echo "</div>";

?>
<script src="https://www.gstatic.com/charts/loader.js"> </script>
<script>
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
    var graph_data = <?php echo json_encode($real_array);?>;
    var genres = <?php echo json_encode($gen);?>;
    var data = new google.visualization.DataTable();
    <?php
    if (isset($_POST['g'])) {
        $g = $_POST['g'];
        echo "var name = \"Genre - $g\";";
        echo "var barOptions = {title: name,pieSliceText: 'label','width':400,'height':400,backgroundColor: '#333',titleTextStyle: { color: 'white' },legend:{textStyle: { color: 'white' }},bars:'vertical',hAxis:{textStyle:{color: '#FFF'},},vAxis:{textStyle:{color: '#FFF'},}};";
        echo "data.addColumn('string', 'Month');";
        echo "data.addColumn('number', 'Play Count');";
        echo "data.addRows(12);";
        echo "for (var i = 0; i < graph_data.length;i++){";
            echo "data.setCell(i,0,graph_data[i]['month']);";
            echo "data.setCell(i,1,graph_data[i]['play_count']);";
        echo "}";
        echo "\n";
        echo "var data2 = new google.visualization.DataTable();";
        echo "data2.addColumn('string', 'Genre');";
        echo "data2.addColumn('number', 'Play Count');";
        echo "data2.addRows(genres.length);";
        echo "for (var i = 0; i < genres.length;i++){";
            echo "data2.setCell(i,0,genres[i]['genre']);";
            echo "data2.setCell(i,1,genres[i]['play_count']);";
        echo "}";
        echo "var pieOptions = {backgroundColor: '#333',titleTextStyle: { color: 'white' },legend: {textStyle: {color: 'white'}}};";
        echo "var chart = new google.visualization.BarChart(document.getElementById('myBarChart'));";
        echo "chart.draw(data, barOptions);";
        echo "var char2 = new google.visualization.PieChart(document.getElementById('myPieChart'));";
        echo "char2.draw(data2,pieOptions);";
    }
    if (isset($_POST['a'])) {
        $a = $_POST['a'];
        echo "var name = \"Artist - $a\";";
        echo "var barOptions = {title: name,pieSliceText: 'label','width':400,'height':400,backgroundColor: '#333',titleTextStyle: { color: 'white' },legend:{textStyle: { color: 'white' }},bars:'vertical',hAxis:{textStyle:{color: '#FFF'},},vAxis:{textStyle:{color: '#FFF'},}};";
        echo "data.addColumn('string', 'Artist');";
        echo "data.addColumn('number', 'Play Count');";
        echo "data.addRows(12);";
        echo "for (var i = 0; i < graph_data.length;i++){";
            echo "data.setCell(i,0,graph_data[i]['month']);";
            echo "data.setCell(i,1,graph_data[i]['play_count']);";
        echo "}";
        echo "var chart = new google.visualization.BarChart(document.getElementById('myBarChart'));";
        echo "chart.draw(data, barOptions);";
    }
    if (isset($_POST['custom_artist'])) {
        $a = $_POST['custom_artist'];
        echo "var name = \"Artist - $a\";";
        echo "var barOptions = {title: name,pieSliceText: 'label','width':400,'height':400,backgroundColor: '#333',titleTextStyle: { color: 'white' },legend:{textStyle: { color: 'white' }},bars:'vertical',hAxis:{textStyle:{color: '#FFF'},},vAxis:{textStyle:{color: '#FFF'},}};";
        echo "data.addColumn('string', 'Artist');";
        echo "data.addColumn('number', 'Play Count');";
        echo "data.addRows(12);";
        echo "for (var i = 0; i < graph_data.length;i++){";
            echo "data.setCell(i,0,graph_data[i]['month']);";
            echo "data.setCell(i,1,graph_data[i]['play_count']);";
        echo "}";
        echo "var chart = new google.visualization.BarChart(document.getElementById('myBarChart'));";
        echo "chart.draw(data, barOptions);";
    }
    if (isset($_POST['album'])) {
        $album = $_POST['album'];
        echo "var name = \"Album - $album\";";
        echo "var barOptions = {title: name,pieSliceText: 'label','width':400,'height':400,backgroundColor: '#333',titleTextStyle: { color: 'white' },legend:{textStyle: { color: 'white' }},bars:'vertical',hAxis:{textStyle:{color: '#FFF'},},vAxis:{textStyle:{color: '#FFF'},}};";
        echo "data.addColumn('string', 'Month');";
        echo "data.addColumn('number', 'Play Count');";
        echo "data.addRows(12);";
        echo "for (var i = 0; i < graph_data.length;i++){";
            echo "data.setCell(i,0,graph_data[i]['month']);";
            echo "data.setCell(i,1,graph_data[i]['play_count']);";
        echo "}";
        echo "var chart = new google.visualization.BarChart(document.getElementById('myBarChart'));";
        echo "chart.draw(data, barOptions);";
    }
    if (isset($_POST['custom_album'])) {
        $album = $_POST['custom_album'];
        echo "var name =  \"Album - $album\";";
        echo "var barOptions = {title: name,pieSliceText: 'label','width':400,'height':400,backgroundColor: '#333',titleTextStyle: { color: 'white' },legend:{textStyle: { color: 'white' }},bars:'vertical',hAxis:{textStyle:{color: '#FFF'},},vAxis:{textStyle:{color: '#FFF'},}};";
        echo "data.addColumn('string', 'Month');";
        echo "data.addColumn('number', 'Play Count');";
        echo "data.addRows(12);";
        echo "for (var i = 0; i < graph_data.length;i++){";
            echo "data.setCell(i,0,graph_data[i]['month']);";
            echo "data.setCell(i,1,graph_data[i]['play_count']);";
        echo "}";
        echo "var chart = new google.visualization.BarChart(document.getElementById('myBarChart'));";
        echo "chart.draw(data, barOptions);";
    }
    if (isset($_POST['song'])) {
        $song = explode(",",$_POST['song'])[0];
        echo "var name = \"Song - $song\";";
        echo "var barOptions = {title: name,pieSliceText: 'label','width':400,'height':400,backgroundColor: '#333',titleTextStyle: { color: 'white' },legend:{textStyle: { color: 'white' }},bars:'vertical',hAxis:{textStyle:{color: '#FFF'},},vAxis:{textStyle:{color: '#FFF'},}};";
        echo "data.addColumn('string', 'Month');";
        echo "data.addColumn('number', 'Play Count');";
        echo "data.addRows(12);";
        echo "for (var i = 0; i < graph_data.length;i++){";
            echo "data.setCell(i,0,graph_data[i]['month']);";
            echo "data.setCell(i,1,graph_data[i]['play_count']);";
        echo "}";
        echo "var chart = new google.visualization.BarChart(document.getElementById('myBarChart'));";
        echo "chart.draw(data, barOptions);";
    }
    ?>
    // Instantiate and draw the chart.


    };
</script>