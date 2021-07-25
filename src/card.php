<?php
function DOMinnerHTML(DOMNode $element)
{
    $innerHTML = "";
    $children  = $element->childNodes;

    foreach ($children as $child)
    {
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }

    return $innerHTML;
}

function getSiteOG( $url, $specificTags=0 ){
    $doc = new DOMDocument();
    @$doc->loadHTML(file_get_contents($url));
    $res['title'] = $doc->getElementsByTagName('title')->item(0)->nodeValue;

    foreach ($doc->getElementsByTagName('meta') as $m){
        $tag = $m->getAttribute('name') ?: $m->getAttribute('property');
        if(in_array($tag,['description','keywords']) || strpos($tag,'og:')===0) $res[str_replace('og:','',$tag)] = $m->getAttribute('content');
    }
    foreach ($doc->getElementsByTagName('a') as $m){
        $rel = $m->getAttribute('rel');
        if($rel == "author"){
            $res["author"] = DOMinnerHTML($m);
        }
    }
    return $specificTags? array_intersect_key( $res, array_flip($specificTags) ) : $res;
}

  function card($text){
    $regex = '/^\#\+card:{1}\s+?(.+)/im';
    function callback_card($pattern){
        $link = trim($pattern[1]);
        $result = getSiteOG($link);
        $result2 = get_meta_tags($link);

        $title = explode(" - ", $result["title"])[0];
        $author = array_key_exists("author", $result2)?$result2["author"]:"";
        if (!$author){
            $author = array_key_exists("author", $result)?$result["author"]:"";
        }
        $page = array_key_exists("site_name", $result)?$result["site_name"]:"";
        if (!$page){
            $page = array_key_exists("page-topic", $result2)?$result2["page-topic"]:"";
        }
        $description = $result["description"];
        $image = $result["image"];

        $replace = <<<EOT
<div class='card'>
<a href='$link'>
<img src='$image'>
<span class='author'>$author</span><span class='page'>$page</span>
<h3>$title</h3>
<div>$description</div>
</a>
</div>
EOT;
        return $replace;
    }
    return preg_replace_callback($regex,'callback_card',$text);
  }
?>
