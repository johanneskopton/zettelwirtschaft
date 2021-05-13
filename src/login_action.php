<?php
    require_once(__DIR__."/../config/db_connect.php");
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);


    if(isset($_POST["login"])){
        $name = $_POST["uname"];
        $pass = $_POST["psw"];

        $sql = "SELECT password FROM user WHERE `name`='$name'";
        $res = $mysqli->query($sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $hash = $res->fetch_row()[0];
            if (password_verify($pass, $hash)){
                $_SESSION['user'] = $name;
            } else {
                echo $l["Wrong password"]."!<br>";
            }
        } else {
            echo $l["No user with that username"]."!<br>";
        }
    }
?>