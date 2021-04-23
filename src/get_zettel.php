<?php
    require_once("helper.php");
    if (isset($_GET["link"]) && $_GET["link"] != "") {
        $filename = $_GET["link"];
    } else {
        $filename = "start.org";
    }
    $path = "zettel/" . $filename;
    $content = get_content($filename);
    $title = get_title($content);
?>