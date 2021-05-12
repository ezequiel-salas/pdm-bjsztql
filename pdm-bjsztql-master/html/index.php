<!DOCTYPE html>
<head>
<link rel="stylesheet" href="site.css">
  <title>Mike Music Player</title>
</head>
<html>
<div class="topnav">
  <a class="active" href="#home">Home</a>
  <a href="#artist">Artists</a>
  <a href="#album">Album</a>
  <a href="#genre">Genre</a>
  <a href = "#song">Songs</a>

  <div class="search-container">
    <form action="result.php" method="get">
    <input type="text" id = "sstring"placeholder="Search..">
    </form>
  </div>
    <div class = "info">Hello User
      <?php
        if (!$_SESSION["logged_in"]) {
        echo '<h2>Log In</h2>';
        echo '<form action="index.php" method="post">';
        echo 'Username: <input type="text" name="username">';
        echo '<input type="submit"></form>';

        if (isset($_POST['username'])) {
            $does_exist = doesUserExist($_POST['username']);

            if ($does_exist == 't') {
                $_SESSION["username"] = $_POST['username'];
                $_SESSION["logged_in"] = true;
                $_SESSION["new_user"] = false;
                echo '<meta http-equiv="Refresh" content="0; url=./index.php">';
            } else {
                $_SESSION["new_user"] = true;
                echo 'No señor!';
            }
        }
    } else {
        if (!$_SESSION["new_user"]) {
            echo '<p> <font color=green\'>Logged in as ' . $_SESSION['username'] . '</font></p>';
        } else {
            echo '<p> <font color=green\'>Logged in as new user ' . $_SESSION['username'] . '!</font></p>';
        }
        echo '<form action="index.php" method="post"><button type="submit" name="logout">Log Out</button></form>';
    }
      ?>
  </div>
</div>
<div class = "local-cont">
  <div class = "local">Your Stats</div>
  <div class = "grid">
    <div class = "scrollBox">
      <p class = "mostSong">Most Played Songs</p>
      <div class="myBox">
      <ul class="myList">
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
      </ul>
      </div>
    </div>
        <div class = "scrollBox">
      <p class = "mostSong">Most Played Albums</p>
      <div class="myBox">
      <ul class="myList">
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
      </ul>
      </div>
    </div>
        <div class = "scrollBox">
      <p class = "mostSong">Most Played Artists</p>
      <div class="myBox">
      <ul class="myList">
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
      </ul>
      </div>
    </div>
  </div>
  </div>
<div class = "global-cont">
  <div class = "global">Global Stats</div>
  <div class = "grid">
    <div class = "scrollBox">
      <p class = "mostSong">Most Played Songs</p>
      <div class="myBox">
      <ul class="myList">
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
      </ul>
      </div>
    </div>
        <div class = "scrollBox">
      <p class = "mostSong">Most Played Albums</p>
      <div class="myBox">
      <ul class="myList">
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
      </ul>
      </div>
    </div>
        <div class = "scrollBox">
      <p class = "mostSong">Most Played Artists</p>
      <div class="myBox">
      <ul class="myList">
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
        <li>Poggers</li>
        <li>PogChamp</li>
        <li>WidePeepo</li>
      </ul>
      </div>
    </div>
  </div>
</div>
</html>