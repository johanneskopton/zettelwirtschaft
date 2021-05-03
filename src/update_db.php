<?php
    require_once(__DIR__."/../config/db_connect.php");
    require_once("src/helper.php");
    
    function update_db($name, $content){
        global $db_host, $db_user, $db_pass, $db_name;
        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

        $sql = "DELETE FROM zettel WHERE name='$name'";
        $mysqli->query($sql);
    
        $sql = "DELETE FROM connections WHERE origin_name='$name'";
        $mysqli->query($sql);

        $title = get_title($content);

        $date_creation = get_creation_date($content);
        $date_modified = get_modified_date($content);
        $sql = "INSERT INTO zettel (`name`, `title`, `date_creation`, `date_modified`) VALUES ('$name','$title', '$date_creation', '$date_modified')";
        $mysqli->query($sql);

        $connections = find_connections($content);
        for ($i = 0; $i < sizeof($connections[0]); $i++){
            if ($connections[2][$i] != ""){
                $targetname = $connections[2][$i];
            } elseif ($connections[4][$i] != ""){
                $targetname = $connections[4][$i];
            }
            $sql = "INSERT INTO connections (`origin_name`, `target_name`) VALUES ('$name', '$targetname')";
            if ($mysqli->query($sql) === TRUE) {
                // echo "New record created successfully";
            } else {
                //echo "Error: " . $sql . "<br>" . $mysqli->error;
            }
        }
        $mysqli->close();
    }
?>