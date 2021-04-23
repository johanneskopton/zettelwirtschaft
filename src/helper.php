<?php
    require_once("external.php");

    function get_content($namespace, $filename){
        global $external_paths;
        
        $base_path = ($namespace=="")?"zettel/":$external_paths[$namespace];
        $path = $base_path . $filename . ".org";
        $handle = @fopen($path,"r");
        if ($handle){
            $content = file_get_contents ($path);
        } else {
            if (isset($_GET["create"])){
                $content = "#+TITLE: " . explode(".", $filename)[0];
                $file = fopen($path, "w");
                fclose($file);
            }else{
                //echo "<div class='fatal'>Zettel not found</div></body></html>";
                //exit();
                return "Zettel not found!";
            }
        }
        
        return $content;
    }
    function get_title($content){
        $sep1 = explode("#+TITLE: ", $content, 2);
        if (sizeof($sep1) > 1){
            return explode(PHP_EOL, $sep1[1], 2)[0];
        } else {
            return "no Title";
        }
    }

    function get_title_from_name($namespace, $filename){
        return get_title(get_content($namespace, $filename));
    }

    function find_connections($content){
        preg_match_all('/(\[\[file\:(.+?).org\]\[(.+?)\]\]|\[(?:ztl|ext)\:(.+?)\])/m', $content, $result);
        return $result;
    }
?>