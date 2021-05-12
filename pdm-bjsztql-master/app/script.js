function updateActive(){
    console.log(document.URL);
    var x = String(document.URL);
    if  (x.includes("index")){
        document.getElementById("1").className="active";
    }
    else if (x.includes("my_artists.php")){
        document.getElementById("7").className="active";
    }
    else if (x.includes("my_albums.php")){
        document.getElementById("8").className="active";
    }
    else if (x.includes("my_songs.php")){
        document.getElementById("9").className="active";
    }
    else if (x.includes("artists.php") && !x.includes("album")){
        document.getElementById("2").className="active";
    }
    else if (x.includes("albums.php")){
        document.getElementById("3").className="active";
    }
    else if (x.includes("genres.php")){
        document.getElementById("4").className="active";
    }
    else if (x.includes("songs.php")){
        document.getElementById("5").className="active";
    }
    else if (x.includes("add.php")){
        document.getElementById("6").className="active";
    }
    else if (x.includes("my_recomendations.php")){
        document.getElementById("10").className="active"
    }
    else if (x.includes("analytics.php")){
        document.getElementById("11").className="active"
    }
}
function openNav() {
  document.getElementById("sidenav").style.width = "8%";
}

function closeNav() {
  document.getElementById("sidenav").style.width = "0";
}
