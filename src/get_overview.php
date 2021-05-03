<div class="overview_wrapper">
<h1><?php echo $l["All Zettel"]; ?></h1>
<?php
    echo "<a href='overview.php?type=creation' class='button'>".$l["Creation date"]."</a>";
    echo "<a href='overview.php?type=modified' class='button'>".$l["Last modified"]."</a>";
    echo "<a href='overview.php?type=alphabetical' class='button'>".$l["Alphabetical"]."</a>";
    echo "<br>";

    require_once(__DIR__."/../config/db_connect.php");
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if (isset($_GET["type"])){
        if ($_GET["type"] == "creation"){
            print_by_date("date_creation");
        } elseif ($_GET["type"] == "modified"){
            print_by_date("date_modified");
        } else {
            print_alphabetically();
        }
    } else {
        print_alphabetically();
    }


    function print_by_date($type){
        global $mysqli;
        $sql = "SELECT * FROM zettel ORDER BY $type DESC";
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
                echo "<li><a href='index.php?link=".$row["name"]."'>".$row["title"]."</a></li>";
            }
        }
    }

    function print_alphabetically(){
        global $mysqli;
        $sql = "SELECT * FROM zettel ORDER BY title ASC";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                echo "<li><a href='index.php?link=".$row["name"]."'>".$row["title"]."</a></li>";
            }
            echo "</ul>";
        }
    }
?>
</div>