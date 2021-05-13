<?php
    require_once(__DIR__."/../config/external.php");

    function get_content($namespace, $filename){
        global $external_paths, $username, $l;
        
        $base_path = ($namespace=="")?"zettel/$username/":$external_paths[$namespace];

        $path = $base_path . $filename . ".org";

        $handle = @fopen($path,"r");
        if ($handle){
            $content = file_get_contents ($path);
        } else {
            if (isset($_GET["create"]) && $_SERVER['SCRIPT_NAME'] == "/edit.php"){
                $content  = "#+TITLE: " . explode(".", $filename)[0] . "\n";
                $content .= "#+ROAM_TAGS: \n";
                $content .= "#+CREATED: " . date("Y-m-d") . "\n";
                $content .= "#+LAST_MODIFIED: " . date("Y-m-d") . "\n";

                $file = fopen($path, "w");
                fwrite($file, $content);
                fclose($file);
            }else{
                return $l["Zettel not found"] . "\n  [[overview.php][".$l["All Zettel"]."]]";

            }
        }
        
        return $content;
    }
    function get_title($content){
        global $l;
        $sep1 = explode("#+TITLE: ", $content, 2);
        if (sizeof($sep1) > 1){
            return trim(explode(PHP_EOL, $sep1[1], 2)[0]);
        } else {
            return $l["no title"];
        }
    }

    function get_title_from_name($namespace, $filename){
        return get_title(get_content($namespace, $filename));
    }

    function get_creation_date($content){
        $sep1 = explode("#+CREATED: ", $content, 2);
        if (sizeof($sep1) > 1){
            $datestr = trim(explode(PHP_EOL, $sep1[1], 2)[0]);
            //return date_create_from_format ("Y-m-d", $datestr);
            return $datestr;
        } else {
            return "no date";
        }
    }

    function get_modified_date($content){
        $sep1 = explode("#+LAST_MODIFIED: ", $content, 2);
        if (sizeof($sep1) > 1){
            $datestr = trim(explode(PHP_EOL, $sep1[1], 2)[0]);
            //return date_create_from_format ("Y-m-d", $datestr);
            return $datestr;
        } else {
            return "no date";
        }
    }

    function find_connections($content){
        preg_match_all('/(\[\[file\:(.+?).org\]\[(.+?)\]\]|\[(?:ztl|ext)\:(.+?)\])/m', $content, $result);
        return $result;
    }
?>