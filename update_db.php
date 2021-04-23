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
                $content = get_content($name);
                $title = get_title($content);
                echo "Name: $name <br>";
                echo "Title: $title <br>";

                $sql = "INSERT INTO zettel (`name`, `title`) VALUES ('$name','$title')";
                $mysqli->query($sql);

                $connections = find_connections($content);
                for ($i = 0; $i < sizeof($connections[0]); $i++){
                    $sql = "INSERT INTO connections (`origin_name`, `target_name`, `linktext`) VALUES ('$name', '".$connections[1][$i]."','".$connections[2][$i]."')";
                    $mysqli->query($sql);
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