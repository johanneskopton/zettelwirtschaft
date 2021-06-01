<?php
    require_once("src/bibtexparser/BibtexParser.php");
    require_once("src/bibtexparser/BibtexFormatter.php");
    require_once(__DIR__."/../lang/language.php");
    require_once(__DIR__."/../config/external.php");



    $bibtexparser = new BibtexParser();

    if (array_key_exists($zetteluser, $external_paths)){
        $base_path = $external_paths[$zetteluser];
        $path = $base_path . "get_raw.php?bib=$zetteluser";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_POST, 1);
        $credentials = array('name' => $username, 'pass' => $_SESSION['pass']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($credentials));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $bib_content = curl_exec($ch);
        curl_close($ch);
        $bibitems = $bibtexparser->parse_string($bib_content);
        $is_bib_file = True;
    }else{
        $self_bib_location = "bibliography/$zetteluser.bib";
        if (is_file($self_bib_location)){
            $bibitems = $bibtexparser->parse_file($self_bib_location);
            $is_bib_file = True;
        }
    }

    function print_citations($content){
        global $l, $is_bib_file;
        preg_match_all('/\[r?cite\:(.+?)\]/m', $content, $citations);
        if (sizeof($citations[0]) > 0){
            echo "<div class='bibliography'><h2>".$l["Bibliography"]."</h2><ol>";
            if (!$is_bib_file){
                echo $l["No bibliography file"];
            }else{
                for ($i = 0; $i < sizeof($citations[0]); $i++){
                    if ($citations[1][$i] != ""){
                        $key = $citations[1][$i];
                        print_citation($key, $i);
                    }
                }
            }
            echo "</ol></div>";
        }
    }

    function get_citation_title($key){
        global $bibitems;
        foreach($bibitems as &$item) {
            if ($item["reference"] == $key){
                if (array_key_exists("shorttitle", $item)){
                    return $item["shorttitle"];
                } elseif (array_key_exists("title", $item)){
                    return $item["title"];
                } else {
                    return $l["no title"];
                }
            }
        }
    }

    function print_citation($key, $i){
        global $bibitems;
        foreach($bibitems as &$item) {
            if ($item["reference"] == $key){
                echo "<li id='bib_".($i+1)."'>";
                echo BibtexFormatter::format($item);
                echo "</li>";
            }
        }
        
    }

?>