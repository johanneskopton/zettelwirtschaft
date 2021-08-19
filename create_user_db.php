<?php
    require_once("config/db_connect.php");
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($mysqli->connect_error) {
      die("Connection failed: " . $mysqli->connect_error);
    }

    # STRICTLY RESTRICT ACCESS TO EXECUTE THIS FILE
    # OR COMMENT OUT THESE LINES AFTER DB CREATION
#    $sql = file_get_contents("database/user.sql");
#    $mysqli->multi_query($sql);
#    do{}while(mysqli_next_result($mysqli));

    $sql = "SELECT * FROM user";
    $result = $mysqli->query($sql);
    while($row = $result->fetch_assoc()) {
        echo $row["name"] . "<br>";
    }
?>
