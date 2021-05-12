<?php
    include('sql.php');
    include('header.php');
    include('sidenav.php');
    ?>
<div class ="addText">
<h2>Add to Database</h2>
This dialogue is for adding of information to the database.<br>
Any blank fields are omitted when the submit button is pressed.<br>
When adding artists, type artist name in the box.<br>
When adding albums, type album name and artist name in their respective boxes.<br>
To add genres, type genre name in the respective box.<br>
To add songs, type artist name, optionally album name, and a list of songs on that album in the specified way:<br><br>
<code>
    Song A,MM:SS,Genre<br>
    Song B,MM:SS,Genre<br>
    Song C,MM:SS,Genre<br>
</code><br>
Note that songs in the song box are appended to the specified album if the album already exists.<br>
Genre is optional for songs, if it is blank then we use genre specified in the 'genre' box.<br>
The 'genre' box is not required if you specify individual genres for songs.<br>
</div>
<?php
    $head_printed = false;
    function printHead() {
        global $head_printed;
        if ($head_printed == false) {
            echo '<h3>Result</h3>';
        }
        $head_printed = true;
    }

    if (($username = $_POST['username']) != '') {
        printHead();
        //Print if user already exists, not adding.
        //Attempt to add user
        createUser($username);
    }

    if (($artist = $_POST['artist_name']) != '') {
        printHead();
        createArtist($artist);
    }

    if (($album = $_POST['album_name']) != '') {
        printHead();
        if ($artist == '') {
            echo 'ERROR: Artist needs to be defined to add album!<br>';
            goto albums_end;
        }
        createAlbum($album, $artist);
    }
    albums_end:

    if (($genre = $_POST['genre_name']) != '') {
        printHead();
        createGenre($genre);
    }

    if (($songs_unparsed = $_POST['song_list']) != '') {
        printHead();
        if ($artist == '') {
            echo 'ERROR: Artist needs to be defined to add songs!<br>';
            goto songs_end;
        }
        //Parse song list
        $songs_tuple = explode(PHP_EOL, $songs_unparsed);
        foreach ($songs_tuple as $row_unparsed) {
            $row = explode(',', $row_unparsed);
            $ns_song = $row[0];
            $ns_length = $row[1];
            if ($row[2] != "") {
                $ns_genre = trim($row[2]);
            } else {
                if ($genre == '') {
                    echo 'ERROR: Genre not specified for song, \'genre\' form field not filled!';
                    goto songs_end;
                }
                $ns_genre = $genre;
            }
            createSong($ns_song, $artist, $album, $ns_genre, $ns_length);
        }
        //Add songs to artist (null album) or on album
    }
    songs_end:
?>

<form action="add.php" method="post" class = "form">
    <h3>Add User</h3>
    <label for="uname">Username:</label>
    <input type="text" id="uname" name="username"><br>
    <input type="submit" value="Submit">
    <h3>Add Music</h3>
    <label for="aname">Artist Name:</label>
    <input type="text" id="aname" name="artist_name"><br>
    <label for="albname">Album Name:</label>
    <input type="text" id="albname" name="album_name"><br>
    <label for="gname">Genre:</label>
    <input type="text" id="gname" name="genre_name"><br>
    <label for="songs">Song List:</label><br>
    <textarea name="song_list" id="songs" cols="30" rows="10"></textarea><br>
    <input type="submit" value="Submit">
</form>