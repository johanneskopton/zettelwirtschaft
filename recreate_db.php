<?php
    require_once("config/db_connect.php");
    require_once("src/helper.php");
    
    function update_db(){
        global $db_host, $db_user, $db_pass, $db_name;
        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

        $sql = "DROP TABLE zettel";
        $mysqli->query($sql);
    
        $sql = "DROP TABLE connections";
        $mysqli->query($sql);

        $sql = file_get_contents("database/schema.sql");
        $mysqli->multi_query($sql);
        do{}while(mysqli_next_result($mysqli));


        if ($handle = opendir("zettel")) {
            while (false !== ($name = readdir($handle))) {
                if ($name != "." && $name != ".."){
                    $name = explode(".org", $name)[0];
                    echo "$name<br>";

                    $content = get_content("", $name);
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
                    //echo "<br>";
                }
            }
            closedir($handle);
        }
        $mysqli->close();
    }
    update_db();
?>