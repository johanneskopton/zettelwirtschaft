<?php

class BibtexFormatter
{
    static function format($entry)
    {
        $body = self::buildBody($entry);

        preg_match_all("/{(\w+)}/", $body, $placeholder);
        for ($j = 0; $j < count($placeholder[0]); $j++) {
            $key = strtolower($placeholder[1][$j]);

            // Check if field is actually set
            if (!empty($entry[$key])) {

                // Arrays need special treatment
                if(is_array($entry[$key])) {
                    if($key == 'author') {
                        $data = implode(' and ', $entry[$key]);
                    }
                    elseif($key == 'pages' && is_array($entry[$key])) {
                        $data = $entry[$key]['start'] . '&mdash;' . $entry[$key]['end'];
                    }
                    else {
                        $data = "";
                    }
                }

                // Regular strings are simply used as-is
                else {
                    $data = $entry[$key];
                }

                $body = str_ireplace($placeholder[0][$j], $data, $body);

            }

        }

        return self::removeOptionalFields($body);
    }

    static function buildBody($entry)
    {
        include("config/external.php");
        $type = strtolower($entry['type']);
        if ($type == "article")
            return "{author}<br/> <i>{title}</i><br/>{journal}[, {volume}][({number})][: {pages}], <a href='https://doi.org/{doi}' target='_blank'>doi:{doi}</a>, {year}. [<br>{dbslinks}]";
        elseif ($type == "book")
            return "{author}<br/> <i>{title}</i><br/>{publisher}[, ISBN: <a href='$library_pre{isbn}$library_post' target='_blank'>{isbn}</a>], {year}. [<br>{dbslinks}]";
        elseif ($type == "incollection")
            return "{author}<br/> <i>{title}</i><br/>In[ {editor} (ed.)]: {booktitle}, {publisher}[, {volume}][: {pages}], {year}. [<br>{dbslinks}]";
        elseif ($type == "proceedings")
            return "[{author}<br/> ]<i>{title}</i><br/>[In {booktitle}, ]{year}. [<br>{dbslinks}]";
        elseif ($type == "inproceedings")
            return "{author}<br/> <i>{title}</i><br/>In {booktitle}, {year}. [<br>{dbslinks}]";
        elseif ($type == "mastersthesis")
            return "{author}<br/> <i>{title}</i><br/>[{note},] {school}, {year}. [<br>{dbslinks}]";
        elseif ($type == "misc")
            return "[{author}<br/>][ <i>{title}</i><br/>][{howpublished}, ][{note}][, {year}]. [<br>{dbslinks}]";
        elseif ($type == "phdthesis")
            return "{author}<br/> <i>{title}</i><br/>PhD Thesis, {school}, {year}. [<br>{dbslinks}]";
        elseif ($type == "techreport")
            return "{author}<br/> <i>{title}</i><br/>Technical Report, [No. {number}, ]{institution}, {year}. [<br>{dbslinks}]";
        elseif ($type == "unpublished")
            return "{author}<br/> <i>{title}</i><br/>{note}. [<br>{dbslinks}]";
        else
            return "{author}<br/> <i>{title}</i><br/>{journal}{booktitle}, {year} [<br>{dbslinks}]";
    }

    static function removeOptionalFields($entry)
    {
        $save = 10; // just to avoid an inf. loop
        do {
            // Find all optional fields that do not contain any other optional fields.
            // If an opt. field contains place holders, remove the field. Otherwise,
            // just remove the brackets.
            // Do this as long as optional fields are found (but max 10 times).
            preg_match_all("/\[[^\[\]]+\]/", $entry, $out);
            $out = $out[0];
            for ($i = 0; $i < count($out); $i++) {
                if (strstr($out[$i], "{")) {
                    // at least one placeholder was not replaced, so remove this optional field
                    $entry = str_replace($out[$i], "", $entry);
                } else {
                    // no more placeholders, just remove the braces: [,]
                    $entry = str_replace($out[$i], substr($out[$i], 1, -1), $entry);
                }
            }
        } while (count($out) > 0 && $save-- > 0);

        return $entry;
    }
}
