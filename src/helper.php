<?php
    function get_content($filename){
        $path = "zettel/" . $filename;
        if (file_exists($path)){
            $content = file_get_contents ($path);
        } else {
            if (isset($_GET["create"])){
                $content = "#+TITLE: " . explode(".", $filename)[0];
                $file = fopen($path, "w");
                fclose($file);
            }else{
                echo "<div class='fatal'>Zettel not found</div></body></html>";
                exit();
            }
        }
        
        return $content;
    }
    function get_title($content){
        $sep1 = explode("#+TITLE: ", $content, 2);
        if (sizeof($sep1) > 1){
            return explode(PHP_EOL, $sep1[1], 2)[0];
        } else {
            return "new zettel";
        }
    }

    function find_connections($content){
        preg_match_all('/\[\[file\:(.+?)\]\[(.+?)\]\]/m', $content, $result);
        return $result;
    }
?>