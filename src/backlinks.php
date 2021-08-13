<?php
    require_once(__DIR__."/../config/db_connect.php");
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $sql = "SELECT origin_name, origin_user FROM connections WHERE target_name='$filename' AND target_user='$zetteluser'";
    $result = $mysqli->query($sql);

    if ($result && $result->num_rows > 0) {
        echo "<div class='incoming'>";
        echo $l["Links to this zettel"].":";
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            $origin_name = $row['origin_name'];
            $origin_user = $row['origin_user'];

            $res = $mysqli->query("SELECT title FROM zettel WHERE `name`='$origin_name' AND `user`='$origin_user'");
            if ($res) {
                $row = $res->fetch_row();
                $origin_title =  $row[0];
            }

            $link_name = ($origin_user==$username)?$origin_name:$origin_user.":".$origin_name;
            $external_marker = ($origin_user==$zetteluser)?"":"class='external_zettelkasten'";
            echo "<li><a $external_marker href='" . $_SERVER['PHP_SELF'] . "?link=$link_name'>$origin_title</a></li>";
        }
        echo "</ul></div>";
    }
?>
