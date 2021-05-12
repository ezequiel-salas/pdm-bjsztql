<head>
<link rel="stylesheet" href="./site.css">
  <title>Poggify</title>
<link rel="shortcut icon" href="./favicon.ico" />
<script src="script.js"></script>
</head>
<html>
<div class="topnav">
  <a href="index.php" id="1">Home</a>
  <a href="artists.php" id="2">Artists</a>
  <a href="albums.php" id="3">Album</a>
  <a href="genres.php" id="4">Genre</a>
  <a href = "songs.php" id="5">Songs</a>
  <a href="add.php" id="6">Add</a>
  <a href="analytics.php" id = "11">Analytics</a>
  <div class="search-container">
    <form action="result.php" method="get">
    <input type="text" id="sstring" name="query" placeholder="Search..">
    </form>
  </div>
<?php
    if (array_key_exists('logout', $_POST)) {
        $_SESSION["logged_in"] = false;
        echo '<meta http-equiv="Refresh" content="0; url=./index.php">';
    }
    if (!$_SESSION["logged_in"]) {
        #echo '<h2>Log In</h2>';
        echo '<form action="index.php" method="post">';
        echo '<input type="text" name="username"placeholder="Username">';
        echo '<input class="button" type="submit" value = "Log In" style = "align:right;margin-top:12px;margin-right:5px;"></form>';

        if (isset($_POST['username'])) {
            $does_exist = doesUserExist($_POST['username']);

            if ($does_exist == 't') {
                $_SESSION["username"] = $_POST['username'];
                $_SESSION["logged_in"] = true;
                $_SESSION["new_user"] = false;
                echo '<meta http-equiv="Refresh" content="0; url=./index.php">';
            } else {
                $_SESSION["new_user"] = true;
                echo 'User does not exist!';
            }
        }
    } else {
    unset($_POST['username']);
    }
      ?>
  </div>
</div>
<body onload="updateActive()">
</body>
</html>
?>