    <div class="zettel_wrapper">
        <?php
            $orgile = new orgile();
            echo $orgile->orgileThis($content);
        ?>
        <?php
            include_once("citations.php");
        ?>
        <?php
            require_once(__DIR__."/../config/db_connect.php");
            $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
            $sql = "SELECT origin_name FROM connections WHERE target_name='$file_id' AND target_user='$username'";
            $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                echo "<div class='incoming'>";
                echo $l["Links to this zettel"].":";
                echo "<ul>";
                while($row = $result->fetch_assoc()) {
                    $origin_name = $row['origin_name'];
                    $res = $mysqli->query("SELECT title FROM zettel WHERE `name`='$origin_name'");
                    if ($res) {
                        $row = $res->fetch_row();
                        $origin_title =  $row[0];
                    }
                    echo "<li><a href='" . $_SERVER['PHP_SELF'] . "?link=$origin_name'>$origin_title</a></li>";
                }
                echo "</ul></div>";
            }
        ?>
    </div>
  
    
    <div class="buttonbox box">
        <?php
            $filepath_bits = explode("/", $_SERVER["SCRIPT_NAME"]);
            if (end($filepath_bits ) == "edit.php"){
                echo "<a href='view.php?link=$filename' name='toview' class='button'>".$l["View"]."</a>";
            } else {
                if ($namespace == ""){
                    echo "<a href='edit.php?link=$filename' class='button'>".$l["Edit"]."</a>";
                }
            }
        ?>
        <a class='button' onclick="toggleForm()"><?php echo $l["Menu"];?></a>
        <?php
            include("src/menu.php");
        ?>
    </div>
