<?php
    require_once("config/external.php");
    $username = $_SESSION["user"];


    if (isset($_GET["user"]) && ($_GET["user"] != $username || array_key_exists($_GET["user"], $external_paths))){
        $overviewuser = $_GET["user"];
        $extern = True;
    }else{
        $overviewuser = $username;
        $extern = False;
    }

    echo "<h1>";
    echo $l["All Zettel"];
    if ($extern){
        echo " (".$overviewuser.")";
    }
    echo "</h1>";

    echo "<a href='overview.php?user=$overviewuser&type=creation' class='button'>".$l["Creation date"]."</a>";
    echo "<a href='overview.php?user=$overviewuser&type=modified' class='button'>".$l["Last modified"]."</a>";
    echo "<a href='overview.php?user=$overviewuser&type=alphabetical' class='button'>".$l["Alphabetical"]."</a>";
    echo "<a href='overview.php?user=$overviewuser&type=tag' class='button'>".$l["By Tag"]."</a>";
    echo "<br>";

    require_once(__DIR__."/../config/db_connect.php");
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if (isset($_GET["type"])){
        if ($_GET["type"] == "creation"){
            print_by_date("date_creation");
        } elseif ($_GET["type"] == "modified"){
            print_by_date("date_modified");
        } elseif ($_GET["type"] == "tag"){
            print_by_tag();
        } else {
            print_alphabetically();
        }
    } else {
        print_alphabetically();
    }


    function print_by_date($type){
        global $mysqli, $overviewuser, $extern;
        $public_filter = $extern?" AND `access`=1":"";
        $sql = "SELECT * FROM zettel WHERE `user`='$overviewuser'$public_filter ORDER BY $type DESC";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            $date = NULL;
            while($row = $result->fetch_assoc()) {
                if ($date != $row["$type"]){
                    if ($date){
                        echo "</ul>";
                    }
                    $date = $row["$type"];
                    echo "$date <ul>";
                }
                print_link($row["name"], $row["title"]);
            }
        }
    }

    function print_by_tag(){
        global $mysqli, $overviewuser, $extern;
        $public_filter = $extern?" AND `access`=1":"";
        $sql = "SELECT * FROM zettel WHERE `user`='$overviewuser'$public_filter ORDER BY tag, title ASC";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            $tag = NULL;
            while($row = $result->fetch_assoc()) {
                if ($tag != $row["tag"]){
                    if ($tag){
                        echo "</ul>";
                    }
                    $tag = $row["tag"];
                    echo "<span id='$tag'>$tag</span> <ul>";
                }
                print_link($row["name"], $row["title"]);
            }
        }
    }

    function print_alphabetically(){
        global $mysqli, $overviewuser, $extern;
        $public_filter = $extern?" AND `access`=1":"";
        $sql = "SELECT * FROM zettel WHERE `user`='$overviewuser'$public_filter ORDER BY title ASC";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                print_link($row["name"], $row["title"]);
            }
            echo "</ul>";
        }
    }

    function print_link($name, $title){
        global $extern, $overviewuser;
        $namespace_string = $extern?$overviewuser.":":"";
        echo "<li><a href='view.php?link=$namespace_string$name'>$title</a></li>";
    }
?>
