<?php
    require_once(__DIR__."/../config/db_connect.php");
    require_once("src/helper.php");

    function update_db($name, $content){
        global $db_host, $db_user, $db_pass, $db_name, $username;
        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);


        $sql = "SELECT * FROM zettel WHERE `name`='$name' AND `user`='$username' AND `access`=1";
        $result = $mysqli->query($sql);
        $access = (mysqli_num_rows($result) == 1)?1:0;

        $sql = "DELETE FROM zettel WHERE `name`='$name' AND `user`='$username'";
        $mysqli->query($sql);

        $sql = "DELETE FROM connections WHERE `origin_name`='$name' AND `origin_user`='$username'";
        $mysqli->query($sql);

        $title = get_title($content);

        $date_creation = get_creation_date($content);
        $date_modified = get_modified_date($content);
        $tag = get_tag($content);

        $word_count = str_word_count($content);

        $sql = "INSERT INTO zettel (`name`, `title`, `user`, `words`, `tag`, `date_creation`, `date_modified`, `access`) VALUES ('$name','$title', '$username', '$word_count', '$tag', '$date_creation', '$date_modified', $access)";
        if ($mysqli->query($sql) === TRUE) {
            //echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }

        $connections = find_connections($content);
        for ($i = 0; $i < sizeof($connections[0]); $i++){
            if ($connections[2][$i] != ""){
                $targetname = $connections[2][$i];
            } elseif ($connections[4][$i] != ""){
                $targetname = $connections[4][$i];
            }
            if (strpos($targetname, ":")){
                $target_zettel = explode(":", $targetname)[1];
                $target_user = explode(":", $targetname)[0];
            }else{
                $target_zettel = $targetname;
                $target_user = $username;
            }
            $sql = "INSERT INTO connections (`origin_name`, `target_name`, `origin_user`, `target_user`) VALUES ('$name', '$target_zettel', '$username', '$target_user')";
            if ($mysqli->query($sql) === TRUE) {
                // echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
            }
        }
        $mysqli->close();
    }
?>
