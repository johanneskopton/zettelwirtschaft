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
        $access_filter = ($verified_name == $namespace)?"":" AND `access`=1";
        $sql = "SELECT * FROM zettel WHERE `user`='$namespace' AND `name`='$filename'" . $access_filter;
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
            while (false !== ($filename = readdir($handle))) {
                if (substr($filename, 0, 1) != "."){
                    $access_filter = (($verified_name == $username) && isset($_POST["access_all"]))?"":" AND `access`=1";
                    $file_id = explode(".org", $filename)[0];
                    $sql = "SELECT * FROM zettel WHERE `user`='$username' AND `name`='$file_id'" . $access_filter;
                    $result = $mysqli->query($sql);
                    if ($result->num_rows == 1) {
                        echo "$filename\n";
                    }
                }
            }
        }
    }elseif(isset($_GET["bib"]) && $_GET["bib"] != ""){
        $name = $_GET["bib"];
        $access_filter = ($verified_name == $name)?"":" AND `access`=1";
        $sql = "SELECT * FROM zettel WHERE `user`='$name'" . $access_filter;
        $result = $mysqli->query($sql);
        if ($result->num_rows >= 1) {
            $bib_location = "bibliography/$name.bib";
            if (is_file($bib_location)){
                echo file_get_contents($bib_location);
            }
        }
    }else{
        echo "No Zettel specified!";
    }
?>
