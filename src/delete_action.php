<?php
    if (isset($_POST["delete"]) && isset($_POST["delete_check"]) && $_POST["delete_check"] == "yes"){
        require_once(__DIR__."/../config/db_connect.php");
        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

        $delete_filename = $_POST["delete_name"];
        $delete_path = "zettel/$username/$delete_filename.org";
        unlink($delete_path);
        $sql = "DELETE FROM zettel WHERE `name`='$delete_filename' AND `user`='$username'";
        $mysqli->query($sql);
    
        $sql = "DELETE FROM connections WHERE `origin_name`='$delete_filename' AND `origin_user`='$username'";
        $mysqli->query($sql);
    }

?>