<?php
    
    require_once("config/db_connect.php");

    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    
    //$sql = file_get_contents("database/user.sql");
    //$mysqli->multi_query($sql);
    //do{}while(mysqli_next_result($mysqli));
    
    
    $sql = "SELECT * FROM user";
    $result = $mysqli->query($sql);
    while($row = $result->fetch_assoc()) {
        echo $row["name"] . "<br>";
    }

?>