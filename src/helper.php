<?php
    require_once(__DIR__."/../config/external.php");
    require_once(__DIR__."/../config/db_connect.php");
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    $access = True;

    function get_content($namespace, $filename){
        global $external_paths, $username, $l, $mysqli, $access;
        if ($namespace=="" && !array_key_exists($username, $external_paths)){
            $base_path = "zettel/$username/";
        } elseif($namespace==""){
            $base_path = $external_paths[$username];
            $namespace = $username;
            $is_url = True;
        } elseif (array_key_exists($namespace, $external_paths)){
            $base_path = $external_paths[$namespace];
            $is_url = True;
        } elseif (file_exists("zettel/$namespace/")){
            $base_path = "zettel/$namespace/";
            $sql = "SELECT * FROM zettel WHERE `user`='$namespace' AND `name`='$filename' AND `access`=1";
            $result = $mysqli->query($sql);
            $access = (mysqli_num_rows($result) == 1);
        } else {
            echo "Namespace not found!<br>";
        }

        if(isset($is_url)){
            $path = $base_path . "get_raw.php?link=$namespace:$filename";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $path);
            curl_setopt($ch, CURLOPT_POST, 1);
            $credentials = array('name' => $username, 'pass' => $_SESSION['pass']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($credentials));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $content = curl_exec($ch);
            curl_close($ch);
        }else{
            $path = $base_path . $filename . ".org";
            $handle = @fopen($path,"r");
            if ($handle){
                $content = file_get_contents ($path);
            } else {
                if (isset($_GET["create"])){
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

    function get_tag($content){
        $sep1 = explode("#+ROAM_TAGS: ", $content, 2);
        if (sizeof($sep1) > 1){
            $tagstr = trim(explode(PHP_EOL, $sep1[1], 2)[0]);
            return $tagstr;
        } else {
            return "no tag";
        }

    }

    function find_connections($content){
        preg_match_all('/(\[\[file\:(.+?).org\]\[(.+?)\]\]|\[(?:ztl|ext)\:(.+?)\])/m', $content, $result);
        return $result;
    }
?>
