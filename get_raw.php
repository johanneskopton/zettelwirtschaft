<?php
    require_once("config/db_connect.php");
    require_once("lang/language.php");

    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    $name = $_POST["name"];
    $pass = $_POST["pass"];

    $sql = "SELECT password FROM user WHERE `name`='$name'";

    $res = $mysqli->query($sql);


    if ($res && mysqli_num_rows($res) > 0) {
        $hash = $res->fetch_row()[0];
        if (password_verify($pass, $hash)){
            $verified_name = $name;
        } else {
            echo $l["Wrong password"];
            exit;
        }
    }else{
        echo $l["No user with that username"];
        exit;
    }

    if (isset($_GET["link"]) && $_GET["link"] != "") {
        require_once("src/helper.php");
        $file_id = $_GET["link"];
        $filename = explode(":", $file_id)[1];
        $namespace = explode(":", $file_id)[0];

        $sql = "SELECT * FROM zettel WHERE `user`='$namespace' AND `name`='$filename' AND `access`=1";
        $result = $mysqli->query($sql);
        if ($result->num_rows == 1) {
            $content = get_content($namespace, $filename);
            echo $content;
        } else {
            echo $l["Access denied"];
        }
    }elseif(isset($_GET["list_all"]) && $_GET["list_all"] != ""){
        $username = $_GET["list_all"];
        if ($handle = opendir("zettel/$username")) {
            while (false !== ($name = readdir($handle))) {
                if (substr($name, 0, 1) != "."){
                    # TODO check public
                    echo "$name\n";
                }
            }
        }
    }else{
        echo "No Zettel specified!";
    }
?>