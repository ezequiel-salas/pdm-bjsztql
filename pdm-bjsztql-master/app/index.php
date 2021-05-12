<?php
    include('sql.php');
    include('header.php');
    include('sidenav.php');
    ?>
<head>
</head>
<?php
$pagelen = 30;
    echo "<div class = 'generalInfo'>";
    if ($_SESSION['logged_in']) {
        if (isset($_GET["action"])) {
            processAction();
            #echo "<meta http-equiv='Refresh' content='0'; url=./albums.php?page=$page&sort=$sort>";
            echo "<script>location.assign('index.php');</script>"; #kinda slow
}
        echo '<h2>Your Stats</h2>';
        echo "<div class = 'grid'>";
        echo "<div class = 'myBox'>";
        echo '<h3>Your Artists</h3>';
        $your_artists = getCollectionArtistsPlayCount(10,0);
        if (pg_numrows($your_artists) == 0) {
            echo 'No artists in your collection yet! <br>';
            goto end_artists;
        }
        echo "<table>";
        if ($_SESSION['logged_in']) {
            echo "<tr><td><b>Collection</b></td>";
        } else {
            echo "<tr>";
        }
        echo "<td><b>Artist</b></td><td><b>Your Plays</b></td></tr>";
        while ($line = pg_fetch_array($your_artists, null, PGSQL_ASSOC)) {
            $artist = $line['artist'];
            $playcount = $line['play_count_user'];
            echo "<tr>";
            if ($_SESSION['logged_in']) {
                if (isArtistInCollection($artist)) {
                    echo "<td><a href='index.php?action=removeArtist&artist=$artist'>➖</a></td>";
                } else {
                    echo "<td><a href='index.php?action=addArtist&artist=$artist'>➕</a></td>";
                }
            }
            echo "<td><a href='artist.php?artist=$artist'>$artist</a></td>";
            echo "<td>$playcount</td>";
            echo "</tr>";
        }
        echo "</table>";
        end_artists:
        echo "</div>";
        echo "<div class = 'myBox'>";
        echo '<h3>Your Albums</h3>';
        $your_albums = getCollectionAlbumsPlayCount(10,0);
        if (pg_numrows($your_albums) == 0) {
            echo 'No albums in yout collection yet! <br>';
            goto end_albums;
        }
        echo "<table>";
        if ($_SESSION['logged_in']) {
            echo "<tr><td><b>Collection</b></td>";
        } else {
            echo "<tr>";
        }
        echo "<td><b>Album</b></td><td><b>Artist</b></td><td><b>Your Plays</b></td></tr>";
        while ($line = pg_fetch_array($your_albums, null, PGSQL_ASSOC)) {
            $album = $line['album'];
            $artist = $line['artist'];
            $playcount = $line['play_count_user'];
            echo "<tr>";
            if ($_SESSION['logged_in']) {
                if (isAlbumInCollection($album)) {
                    echo "<td><a href='index.php?action=removeAlbum&album=$album'>➖</a></td>";
                } else {
                    echo "<td><a href='index.php?action=addAlbum&album=$album'>➕</a></td>";
                }
            }
            echo "<td><a href='album.php?album=$album&artist=$artist'>$album</a></td>";
            echo "<td><a href='artist.php?artist=$artist'>$artist</a></td>";
            echo "<td>$playcount</td>";
            echo "</tr>";
        }
        echo "</table>";
        end_albums:
        echo "</div>";
        echo "<div class = 'myBox'>";
        echo '<h3>Your Songs</h3>';
        $your_songs = getCollectionSongsPlayCount(10,0);
        if (pg_numrows($your_songs) == 0) {
            echo 'No songs in your collection yet! <br>';
            goto end_songs;
        }
        echo "<table>";
        if ($_SESSION['logged_in']) {
            echo "<tr><td><b>Play</b></td><td><b>Collection</b></td>";
        } else {
            echo "<tr>";
        }
        echo "<td><b>Song</b></td><td><b>Length</b></td><td><b>Album</b></td><td><b>Artist</b></td><td><b>Your Plays</b></td></tr>";
        while ($line = pg_fetch_array($your_songs, null, PGSQL_ASSOC)) {
            $song = $line['song'];
            $album = $line['album'];
            $artist = $line['artist'];
            $playcount = $line['play_count_user'];
            $songlength = $line['song_length'];
            echo "<tr>";
            if ($_SESSION['logged_in']) {
                echo "<td><a href='index.php?action=play&song=$song&album=$album&artist=$artist'>▶️</a></td>";
                if (isSongInCollection($song, $album, $artist)) {
                    echo "<td><a href='index.php?action=removeSong&song=$song&album=$album&artist=$artist&duration=$songlength'>➖</a></td>";
                } else {
                    echo "<td><a href='index.php?action=addSong&song=$song&album=$album&artist=$artist&duration=$songlength'>➕</a></td>";
                }
            }
            echo "<td>$song</td>";
            echo "<td>$songlength</td>";
            echo "<td><a href='album.php?album=$album&artist=$artist'>$album</a></td>";
            echo "<td><a href='artist.php?artist=$artist'>$artist</a></td>";
            echo "<td>$playcount</td>";
            echo "</tr>";
        }
        echo "</table>";
        end_songs:
        echo "</div>";
    }
    if (!$_SESSION['logged_in'] && isset($_GET["action"])) {
        processAction();
    }
    echo "</div>";
    echo '<h2>Global Stats</h2>';
    echo "<div class = 'grid'>";
    echo "<div class = 'myBox'>";
    echo '<h3>Top Artists</h3>';
    $global_artists = getArtistsPlayCount(10,0);
    echo "<table>";
    if ($_SESSION['logged_in']) {
        echo "<tr><td><b>Collection</b></td>";
    } else {
        echo "<tr>";
     }
    echo "<td><b>Artist</b></td><td><b>Plays</b></td></tr>";
    while ($line = pg_fetch_array($global_artists, null, PGSQL_ASSOC)) {
        $song = $line["song"];
        $album = $line["album"];
        $artist = $line["artist"];
        $playcount = $line["play_count"];
        $genre = $line["genre"];
        $songlength = $line["song_length"];
            echo "<tr>";
            if ($_SESSION['logged_in']) {
                if (isArtistInCollection($artist)) {
                    echo "<td><a href='index.php?action=removeArtist&artist=$artist'>➖</a></td>";
                } else {
                    echo "<td><a href='index.php?action=addArtist&artist=$artist'>➕</a></td>";
            }
        }
        echo "<td><a href='artist.php?artist=$artist' class='link'>$artist</a></td>";
        echo "<td>$playcount</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    echo "<div class = 'myBox'>";
    echo '<h3>Top Albums</h3>';
    $global_albums = getAlbumsPlayCount(10,0);
    echo "<table>";
    if ($_SESSION['logged_in']) {
        echo "<tr><td><b>Collection</b></td>";
    } else {
        echo "<tr>";
    }
    echo "<td><b>Album</b></td><td><b>Artist</b></td><td><b>Plays</b></td></tr>";
    while ($line = pg_fetch_array($global_albums, null, PGSQL_ASSOC)) {
        $album = $line['album'];
        $artist = $line['artist'];
        $playcount = $line['play_count'];
        echo "<tr>";
        if ($_SESSION['logged_in']) {
        if (isAlbumInCollection($album)) {
            echo "<td><a href='index.php?action=removeAlbum&album=$album'>➖</a></td>";
        } else {
            echo "<td><a href='index.php?action=addAlbum&album=$album'>➕</a></td>";
        }
        }
        echo "<td><a href='album.php?album=$album&artist=$artist' class='link'>$album</a></td>";
        echo "<td><a href='artist.php?artist=$artist' class='link'>$artist</a></td>";
        echo "<td>$playcount</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
    echo "<div class = 'myBox'>";
    echo '<h3>Top Songs</h3>';
    $global_songs = getSongsPlayCount(10,0);
    echo "<table>";
    if ($_SESSION['logged_in']) {
        echo "<tr><td><b>Play</b></td><td><b>Collection</b></td>";
    } else {
        echo "<tr>";
    }
    echo "<td><b>Song</b></td><td><b>Length</b></td><td><b>Album</b></td><td><b>Artist</b></td><td><b>Plays</b></td></tr>";
    while ($line = pg_fetch_array($global_songs, null, PGSQL_ASSOC)) {
        $song = $line['song'];
        $album = $line['album'];
        $artist = $line['artist'];
        $playcount = $line['play_count'];
        $songlength = $line['song_length'];
        echo "<tr>";
        if ($_SESSION['logged_in']) {
            echo "<td><a href='index.php?action=play&song=$song&album=$album&artist=$artist'>▶️</a></td>";
            if (isSongInCollection($song, $album, $artist)) {
                echo "<td><a href='index.php?action=removeSong&song=$song&album=$album&artist=$artist&duration=$songlength'>➖</a></td>";
            } else {
                echo "<td><a href='index.php?action=addSong&song=$song&album=$album&artist=$artist&duration=$songlength'>➕</a></td>";
            }
        }
        echo "<td>$song</td>";
        echo "<td>$songlength</td>";
        echo "<td><a href='album.php?album=$album&artist=$artist' class='link'>$album</a></td>";
        echo "<td><a href='artist.php?artist=$artist' class='link'>$artist</a></td>";
        echo "<td>$playcount</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
?>