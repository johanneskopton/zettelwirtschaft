<?php
    require_once("config/db_connect.php");
    require_once("src/helper.php");
    require_once("config/external.php");

    
    function update_db(){
        global $db_host, $db_user, $db_pass, $db_name, $username;
        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

        $sql = "DROP TABLE zettel";
        $mysqli->query($sql);
    
        $sql = "DROP TABLE connections";
        $mysqli->query($sql);

        $sql = file_get_contents("database/schema.sql");
        $mysqli->multi_query($sql);
        do{}while(mysqli_next_result($mysqli));

        $sql = "SELECT * FROM user";
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc()) {
            $username = $row["name"];
            echo "<h2>$username</h2>";
            if ($handle = opendir("zettel/$username")) {
                while (false !== ($name = readdir($handle))) {
                    if ($name != "." && $name != ".."){
                        $name = explode(".org", $name)[0];
                        echo "$name<br>";

                        $content = get_content("", $name);
                        $title = get_title($content);

                        $date_creation = get_creation_date($content);
                        $date_modified = get_modified_date($content);
                        $sql = "INSERT INTO zettel (`name`, `title`, `user`, `date_creation`, `date_modified`) VALUES ('$name','$title', '$username', '$date_creation', '$date_modified')";
                        if (!$mysqli->query($sql) === TRUE) {
                            echo "Error: " . $sql . "<br>" . $mysqli->error;
                        }

                        $connections = find_connections($content);
                        for ($i = 0; $i < sizeof($connections[0]); $i++){
                            if ($connections[2][$i] != ""){
                                $targetname = $connections[2][$i];
                            } elseif ($connections[4][$i] != ""){
                                $targetname = $connections[4][$i];
                            }
                            echo "$targetname<br>";
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
                                //echo "Error: " . $sql . "<br>" . $mysqli->error;
                            }
                        }
                        //echo "<br>";
                    }
                }
                closedir($handle);
            }
        }
        $mysqli->close();
    }
    update_db();
?>