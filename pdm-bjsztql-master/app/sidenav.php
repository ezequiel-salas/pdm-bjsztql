<?php
    if($_SESSION["logged_in"]){

        echo '<span onclick="openNav()" class="sideNavButn">&#9776;</span>
              <div class="sidenav"id="sidenav">
                <p>Hello ' . $_SESSION['username'] . '</p>
                <a href="my_artists.php" id="7">My Artists</a>
                <a href="my_albums.php" id="8">My Albums</a>
                <a href="my_songs.php" id="9">My Songs</a>
                <a href="my_recomendations.php" id ="10">Recommendations</a>
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <form action="index.php" method="post" class ="logout"><button class = "button"type="submit" name="logout">Log Out</button></form>
              </div>';
    }
?>
