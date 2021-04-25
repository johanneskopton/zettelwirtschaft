<?php
if ($handle = opendir('zettel')) {

    while (false !== ($name = readdir($handle))) {
        if ($name != "." && $name != ".."){
            $name = explode(".org", $name)[0];
            echo $name."\n";
        }
    }
}
?>