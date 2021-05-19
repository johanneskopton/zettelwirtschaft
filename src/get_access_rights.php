<h1><?php echo $l["Manage access rights"]; ?></h1>
<?php
    require_once(__DIR__."/../config/db_connect.php");
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if (isset($_POST['ztk_change_access'])){
        $sql = "SELECT * FROM zettel WHERE `user`='$username'";
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc()) {
            $new_access = isset($_POST[$row["name"]])?1:0;
            $sql = "UPDATE zettel SET `access`=$new_access WHERE `user`='$username' AND `name`='". $row["name"] . "'";
            $mysqli->query($sql);
        }
    }
?>
<script>
function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}
</script>
<?php
    echo "<a href='access_rights.php?type=creation' class='button'>".$l["Creation date"]."</a>";
    echo "<a href='access_rights.php?type=modified' class='button'>".$l["Last modified"]."</a>";
    echo "<a href='access_rights.php?type=alphabetical' class='button'>".$l["Alphabetical"]."</a>";
    echo "<br>";

    echo "<form action='' method='post'>";
    echo "<ul><input type='checkbox' onclick='toggle(this);'> ".$l["Toggle all"]."</ul>";
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
    echo "<input type='submit' name='ztk_change_access' class='button' value='".$l["Save"]."'>";
    ?>
    <a class="button" href="view.php"><?php echo $l["Start"];?></a>
    <a class="button" href="overview.php"><?php echo $l["All Zettel"];?></a>
    <?php
    echo "</form>";

    function print_by_date($type){
        global $mysqli;
        $username = $_SESSION["user"];
        $sql = "SELECT * FROM zettel WHERE `user`='$username' ORDER BY $type DESC";
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
                $checked = ($row["access"] == 1)?"checked":"";
                echo "<input type='checkbox' name='".$row["name"]."' $checked> ".$row["title"]."<br>";
            }
        }
    }

    function print_alphabetically(){
        global $mysqli;
        $username = $_SESSION["user"];

        $sql = "SELECT * FROM zettel WHERE `user`='$username' ORDER BY title ASC";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                $checked = ($row["access"] == 1)?"checked":"";
                echo "<input type='checkbox' name='".$row["name"]."' $checked> ".$row["title"]."<br>";
            }
            echo "</ul>";
        }
    }
?>
