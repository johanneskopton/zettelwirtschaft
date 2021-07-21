<?php 

/*
  This class is based on classOrgile, but has been heavily changed to support
  the special Zettelkasten syntax, not only standadrd Org-Mode.
  
  ______________________
  C L A S S  O R G I L E

  classOrgile a very rough Org-Mode (http://orgmode.org/) file to HTML parser.
  This class is part of the Orgile publishing tool but can be used as a 
  standalone class. Please see http://toshine.org.

  Version 20110418

  Copyright (c) 2011 , 'Mash (Thomas Herbert) <letters@toshine.org>
  All rights reserved.

  This project was inspired by Dean Allen's "Textile" http://textile.thresholdstate.com/.

  NOTE: If you would like to help me develop this class properly rather then this
  amateur garden shed effort; please do contact me on the above address.

*/
require_once("citations.php");

$bib_item_i = 1;
$music_item = 0;


class orgile {

  // ----------[ ORGILE ]----------
  function orgileThis($text) {
    $text = $this->orgilise($text);
    $text = $this->orgilise_music($text);
    $text = $this->orgilise_links($text);
    $text = $this->orgilise_links_external($text);
    $text = $this->tidy_lists($text);
    $text = $this->codeReplace($text);
    $text = $this->footnotes($text);
    $text = $this->citation($text);
    $text = $this->paragraph($text);
    return $text;
  }

  // ----------[ ORGALISE CONTENT ]----------
  // replace some general Org-mode markup with HTML.
  // NOTE: careful with changing order as links may be "glyphed"

  function orgilise($text) {
    global $namespace, $username;
    $script_name = $_SERVER['PHP_SELF'];
    $namespace_prefix = ($namespace == "") ? $namespace:$namespace.":";

    $regex = array(
       // roam
       '/^\#\+title:{1}\s+?(.+)/im',         # #+TITLE:
       '/^\#\+roam_tags:{1}\s+?(.+)/im',     # #+ROAM_TAGS:
       '/^\#\+created:{1}\s+?(.+)/im',       # #+CREATED:
       '/^\#\+last_modified:{1}\s+?(.+)/im', # #+LAST_MODIFIED:
       '/^:properties:[\s\S]*?:end:(.*)/im', # :PROPERTIES: id :END:

		   // headings
		   '/^\*{1}\s+?(.+)/m', // * example
		   '/^\*{2}\s+?(.+)/m', // ** example
		   '/^\*{3}\s+?(.+)/m', // *** example
		   '/^\*{4}\s+?(.+)/m', // **** example
		   '/^\*{5}\s+?(.+)/m', // ***** example

		   // typography
		   '/(?<!\S)\*(.+?)\*/m', // *example*
		   '/(?<!\S)\/(\S.+?\S)\//m', // /example/
		   '/(?<!\S)\+(.+?)\+/m', // +example+

       // list
       '/^ {2}[\+\-\*]\s?(.+)/m',   // 1st level
       '/^ {4}[\+\-\*]\s?(.+)/m',   // 2st level
       '/^ {6}[\+\-\*]\s?(.+)/m',   // 3st level

        // numbered list
        '/^ {2}[0-9]+[\)\.]\s?(.+)/m',   // 1st level
        '/^ {4}[0-9]+[\)\.]\s?(.+)/m',   // 2st level
        '/^ {6}[0-9]+[\)\.]\s?(.+)/m',   // 3st level

		   // glyphs
		   // kudos: "Textile" http://textile.thresholdstate.com/.
//		   '/(\w)\'(\w)/',                   // apostrophe's
//		   '/(\s)\'(\d+\w?)\b(?!\')/',       // back in '88
//		   '/(\S)\'(?=\s|[[:punct:]]|<|$)/', // single closing
//		   '/\'/',                           // single opening
		   '/(\S)\"(?=\s|[[:punct:]]|<|$)/', // double closing
		   '/"/',                            // double opening
		   '/\b( )?\.{3}/',                  // ellipsis
		   '/(\s\w+)--(\w+\s)/',              // em dash
		   '/\s-(?:\s|$)/',                  // en dash
		   '/(\d+)( ?)x( ?)(?=\d+)/',        // dimension sign
		   '/\b ?[([]TM[])]/i',              // trademark
		   '/\b ?[([]R[])]/i',               // registered
		   '/\b ?[([]C[])]/i',               // copyright

		   // horizontal rule
		   '/-{5}/', // ----- (<hr/>)

		   // blockquotes
		   //'/#\+begin_quote\s([\s\S]*?)\s--\s(.*?)\s#\+end_quote/mi',
       '/#\+begin_quote/m',
       '/#\+end_quote/m',
       '/^>\s*(.+?)\n([^(,]+)([\(|\,].+)*$/m',

		   // pre
		   '/#\+begin_example\s([\s\S]*?)\s#\+end_example/mi',

		   // source
		   '/#\+begin_src[\s\n\r]*([\s\S]*?)\s#\+end_src/mi',

		   // links
       '/\[\[ext\:'.$username.'\:(.+?)\]\[(.+?)\]\]/m', // link from external to this zettelkasten
       '/\[\[file\:(.+?).org\]\[(.+?)\]\]/m', // intern
       '/\[\[ztl\:(.+?)\]\[(.+?)\]\]/m', // intern
       '/\[\[ext\:(.+?)\]\[(.+?)\]\]/m', // other orgroam zettelkasten
       '/\[\[(.+?)\]\[(.+?)\]\]/m', // extern

		   );

    $heading_anchor = "<span class=anchor>&para;</span>";
    $replace = array(
         // roam
     "<h1>$1 ". $heading_anchor ."</h1>\n", // #+TITLE:
     "<div class=roam_tags>$1</div>",
         "<div class=created>$1</div>",
         "<div class=last_modified>$1</div>",
         "",

		     // headings
		     "<h2 id=$1>$1 " . $heading_anchor . "</h2>\n", // * example
		     "<h3 id=$1>$1</h3>\n", // ** example
		     "<h4 id=$1>$1</h4>\n", // *** example
		     "<h5 id=$1>$1</h5>\n", // **** example
		     "<h6 id=$1>$1</h6>\n", // ***** example

		     // typography
		     "<strong>$1</strong>", // *example*
		     "<em>$1</em>",         // /example/
		     "<del>$1</del>",       // +example+

         // list
         "<ul><li>$1</li></ul>",
         "<ul><ul><li>$1</li></ul></ul>",
         "<ul><ul><ul><li>$1</li></ul></ul></ul>",

        // ordered list
        "<ol><li>$1</li></ol>",
        "<ol><ol><li>$1</li></ol></ol>",
        "<ol><ol><ol><li>$1</li></ol></ol></ol>",

		     // glyphs
//		     "$1&#8217;$2",  // apostrophe's&#8220;
//		     "$1&#8217;$2",  // back in '88
//		     "$1&#8217;",    // single closing
//		     "&#8216;",      // single opening
		     "$1&#8221;",    // double closing
		     "&#8220;",      // double opening
		     "$1&#8230;",    // ellipsis
		     "$1&#8212;$2",  // em dash
		     "&#8211;",      // en dash
		     "$1$2&#215;$3", // dimension sign
		     "&#8482;",      // trademark
		     "&#174;",       // registered
		     "&#169;",       // copyright

		     // horizontal rule
		     "<hr>", // ----- (<hr>)

		     // quotes (because of the cite="$2" these fail W3M validation)
		     //'<blockquote cite="$2"><p>$1</p></blockquote><p class="citeRef">$2</p>',
         '<blockquote>',
         '</blockquote>',
         '<blockquote>$1</blockquote><p class="blockquote_ref"><span class="blockquote_name">$2</span>$3</p>',

		     // pre
		     '<pre>$1</pre>',

		     // source
		     '<pre><code class="prettyprint">$1</code></pre>',

        //links
         '<a href="' . $script_name . '?link=$1" name="zettelkasten_link" class="external_zettelkasten" title="$2">$2</a>', // backlink to this zettelkasten
         '<a href="' . $script_name . '?link='.$namespace_prefix.'$1" name="zettelkasten_link" class="internal" title="$2">$2</a>', // intern
         '<a href="' . $script_name . '?link='.$namespace_prefix.'$1" name="zettelkasten_link" class="internal" title="$2">$2</a>', // intern
         '<a href="' . $script_name . '?link=$1" name="zettelkasten_link" class="external_zettelkasten" title="$2">$2</a>', // other orgroam zettelkasten

		     '<a href="$1" title="$2" class="external_internet" target="_blank">$2</a>', // extern
		     );

    return preg_replace($regex,$replace,$text);
  }

  function orgilise_music($text){
    $regex = '/#\+begin_music (\S+) (\S+)\s([\s\S]*?)\s#\+end_music/mi';
    function callback_music($pattern){
        global $music_item, $theme;
        $notes = $pattern[3];
        $clef = $pattern[1];
        $time = $pattern[2];
        $note_color = ($theme == "default")?"#444444":"#839496";
        $replace = <<<EOT
<div id="music_$music_item" class="music"></div>
<script>
vf = new Vex.Flow.Factory({
  renderer: {elementId: 'music_$music_item', width: 500, height: 160}
});
score = vf.EasyScore();
system = vf.System();
notes = score.notes($notes);
for (let i = 0; i < notes.length; i++) {
    notes[i].setStyle({fillStyle: "$note_color", strokeStyle: "$note_color"});
}
stave = system.addStave({
  voices: [
    score.voice(notes)
  ]
})
clef = stave.addClef('$clef');
clef.context.setFillStyle("$note_color");
time = stave.addTimeSignature('$time');
vf.draw();
delete vf, score, notes, clef, time, system;
</script>
EOT;
        $music_item += 1;
        return $replace;
    }
    return preg_replace_callback($regex,'callback_music',$text);
  }

  function orgilise_links($text) {
    $script_name = $_SERVER['PHP_SELF'];
    $regex = '/\[ztl\:(.+?)\]/m';

    function callback($pattern){
      global $namespace, $script_name;
      $namespace_prefix = ($namespace == "") ? $namespace:$namespace.":";
      $linktitle = get_title_from_name($namespace, explode("#",$pattern[1])[0]);
      $section = mb_strpos($pattern[1], "#") !== false?"#".explode("#",$pattern[1])[1]:"";
      return '<a href="'.$script_name.'?link='.$namespace_prefix.$pattern[1].'" name="zettelkasten_link" class="internal" title="'.$linktitle.'">'.$linktitle.$section.'</a>';
    }
    return preg_replace_callback($regex,"callback",$text);
  }


  function orgilise_links_external($text) {
    $script_name = $_SERVER['PHP_SELF'];
    $regex = '/\[ext\:(.+?)\]/m';

    function callback_ext($pattern){
      global $script_name, $username;
      $filename = explode(":", $pattern[1])[1];
      $namespace = explode(":", $pattern[1])[0];
      $namespace = $namespace == $username?"":$namespace;
      $linktitle = get_title_from_name($namespace, $filename);
      $namespace_prefix = ($namespace == "") ? $namespace:$namespace.":";
      return '<a href="'.$script_name.'?link='.$namespace_prefix.$filename.'" name="zettelkasten_link" class="external_zettelkasten" title="'.$linktitle.'">'.$linktitle.'</a>';
    }
    return preg_replace_callback($regex,"callback_ext",$text);
  }

  // Tidy up lists
  function tidy_lists($text) {
    $regex = '/\<\/[uo]l>\n?<[uo]l>/im';
    $replace = "";
    return preg_replace($regex,$replace,$text);
  }

  function citation($text) {
    $regex = '/\[(cite|rcite)\:(.+?)\]/m';
    $callback_citation = function ($pattern){
        global $bib_item_i;
        $title = ($pattern[1] == "cite") ? get_citation_title($pattern[2]) . " ":"";
        $res =  "<a href='#bib_$bib_item_i' class='bib_link'>$title [$bib_item_i]</a>";
        $bib_item_i = $bib_item_i + 1;
        return $res;
    };
    $text = preg_replace_callback($regex, $callback_citation, $text);

    return $text;
  }

  // ----------[ CREATE FOOTNOTES ]----------
  // footnotes follow the pattern "example[n]" for id,  "[n] " for reference.
  function footnotes($text) {
    $regex = array(
		   '/(\S)\[([1-9]|[1-9][0-9])\]/',   // example[1]
		   '/\n\[([1-9]|[1-9][0-9])\](.*)/', // [1] example
		   );

    $replace = array(
		     '$1<sup class="fnote"><a href="#fn$2">$2</a></sup>',
		     '<p class="fnote"><sup id="fn$1" class="fnote">$1</sup>$2</p>',
		     );

    return preg_replace($regex,$replace,$text);
  }

  // ----------[ CODE REPLACE ]----------
  // use \"blah" in code and it will translated back into the "
  function codeReplace($code) {
    $dirty = array('\&#8216;','\&#8217;','\&#8220;','\&#8221;');
    $clean = array("'","'",'"','"');
    $code = str_replace($dirty, $clean, $code);
    return $code;
  }

  // ----------[ PARAGRAPHS AND CLEANUP TAGS ]----------
  // create paragraphs and cleanup HTML tags.
  function paragraph($text) {
    $paragraphs = preg_split("/[\r]?\n[\r]?\n/m", $text);
    $out = null;
    foreach($paragraphs as $paragraph) {
      $out .= "\n<p>".$paragraph."</p>\n";
    }

    // cleanup paragraphs
    // due to the simplicity of the above there are many incorrect nested tags
    // i.e. <h1> elements inclosed in <p> tags.

    $regex = array(
		   '/<p>(<h[1-9]{1}>.+<\/h[1-9]{1}>)<\/p>/m',         // <p><h1>example</h1></p>
		   '/<p>(<blockquote>[\s\S]+?)<\/p>/m',               // <p><blockquote>example</blockquote></p>
		   '/<p>(<blockquote cite=".+?">[\s\S]+?)<\/p>/m',    // <p><blockquote cite="example">example</blockquote></p>
		   '/<p>(<pre>[\s\S]+?<\/pre>)<\/p>/m',               // <p><pre>example</pre></p>
		   '/<p>(<p class="fnote">[\s\S]*?)\s+<\/p>/m',       // <p><p class="footnote">example</p></p>
		   '/(<\/p>)\s+<\/p>/m',                              // <p></p>
		   '/<p>(<hr>)<\/p>/',				      // <p><hr></p>
		   );

    $replace = array(
		     "$1", // <hx>example</hx>
		     "$1", // <blockquote>example</blockquote>
		     "$1", // <blockquote cite="example">example</blockquote>
		     "$1", // <pre>example</pre>
		     "$1", // <p class="footnote">example</p>
		     "$1", //
		     "$1", // <hr>
		     );

    $out = preg_replace($regex,$replace,$out);
    return $out;
  }

} // end: "class orgile {"
?>
