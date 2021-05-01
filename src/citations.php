<?php
    require_once("src/bibtexparser/BibtexParser.php");
    require_once("src/bibtexparser/BibtexFormatter.php");
    require_once(__DIR__."/../lang/language.php");
    require_once(__DIR__."/../config/external.php");



    $bibtexparser = new BibtexParser();

    if (isset($self_bib_location) && $self_bib_location != ""){
        $bibitems = $bibtexparser->parse_file($self_bib_location);
    }

    function print_citations($content){
        global $l;
        preg_match_all('/\[cite\:(.+?)\]/m', $content, $citations);
        if (sizeof($citations[0]) > 0){
            echo "<div class='bibliography'><h2>".$l["Bibliography"]."</h2><ol>";
            if (!isset($self_bib_location) || $self_bib_location == ""){
                echo "No .bib file!";
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