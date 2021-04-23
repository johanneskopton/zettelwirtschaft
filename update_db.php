<?php
    require_once("src/db_connect.php");
    require_once("src/helper.php");
    
    $sql = "TRUNCATE TABLE zettel";
    $mysqli->query($sql);

    $sql = "TRUNCATE TABLE connections";
    $mysqli->query($sql);


    if ($handle = opendir('zettel')) {
    
        while (false !== ($name = readdir($handle))) {
            if ($name != "." && $name != ".."){
                $name = explode(".org", $name)[0];
                $content = get_content("", $name);
                $title = get_title($content);
                echo "Name: $name <br>";
                echo "Title: $title <br>";

                $sql = "INSERT INTO zettel (`name`, `title`) VALUES ('$name','$title')";
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
                        echo "Error: " . $sql . "<br>" . $mysqli->error;
                      }
                }
                echo "<br>";
            }
        }
        
        $sql = "SELECT origin_name, target_name FROM connections";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "From <b>" . $row["origin_name"]. "</b> to <b>" . $row["target_name"]. "</b><br>";
            }
        } else {
            echo "0 results";
        }
        
        closedir($handle);
    }
    $mysqli->close();
?>