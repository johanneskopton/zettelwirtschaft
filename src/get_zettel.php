<?php
    $username = $_SESSION["user"];
    require_once("helper.php");
    if (isset($_GET["link"]) && $_GET["link"] != "") {
        $file_id = $_GET["link"];
        if (strpos($file_id, ":")) {
            $filename = explode(":", $file_id)[1];
            $namespace = explode(":", $file_id)[0];
        } else {
            $filename = $file_id;
            $namespace = "";
        }
        
    } else {
        $file_id = "start";
        $filename = "start";
        $namespace = "";
    }
    //$path = $base_path . $filename;

    $content = get_content($namespace, $filename);
    $title = get_title($content);
?>