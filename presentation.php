<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <?php
            session_start();
            require_once("lang/language.php");
        ?>

        <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
        <script id="MathJax-script" async
                src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js">
        </script>
        <script src="https://unpkg.com/vexflow/releases/vexflow-min.js"></script>

        <link rel="shortcut icon" type="image/png" href="style/favicon.png">
        <?php
            require_once("config/external.php");
            require_once("lang/language.php");

            if(isset($_SESSION["user"])){
            require_once("src/get_zettel.php");
            if (get_tag($content) != $l["Presentation"]){
                echo "<script>window.location.replace('view.php?link=$filename');</script>";
            }

            require_once("src/orgile.php");
            $connections = find_connections($content); 
        ?>

            <title><?php echo $title;?></title>

		<link rel="stylesheet" href="reveal.js/dist/reset.css">
		<link rel="stylesheet" href="reveal.js/dist/reveal.css">
		<link rel="stylesheet" href="style/presentation/oekoprog.css">

		<!-- Theme used for syntax highlighted code -->
		<link rel="stylesheet" href="reveal.js/plugin/highlight/monokai.css">
	</head>
	<body>
		<div class="reveal">
			<div class="slides">
                <?php
                $orgile = new orgile();
                for ($i = 0; $i < sizeof($connections[0]); $i++){
                    if ($connections[2][$i] != ""){
                        $targetname = $connections[2][$i];
                    } elseif ($connections[4][$i] != ""){
                        $targetname = $connections[4][$i];
                    }
                    $targetcontent = get_content("", $targetname);
                    $targettitle = get_title($targetcontent);
                    echo "<section>";
                    $html_content = $orgile->orgileThis($targetcontent);
                    $html_content = str_replace("<h2", "</section><section><h2", $html_content); 
                    echo $html_content;
                    require_once("src/citations.php");
                    print_citations($targetcontent);
                    echo "</section>";
                }
                ?>
			</div>
		</div>

		<script src="reveal.js/dist/reveal.js"></script>
		<script src="reveal.js/plugin/notes/notes.js"></script>
		<script src="reveal.js/plugin/markdown/markdown.js"></script>
		<script src="reveal.js/plugin/highlight/highlight.js"></script>
		<script>
			// More info about initialization & config:
			// - https://revealjs.com/initialization/
			// - https://revealjs.com/config/
			Reveal.initialize({
				hash: true,

				// Learn about plugins: https://revealjs.com/plugins/
				plugins: [ RevealMarkdown, RevealHighlight, RevealNotes ]
			});
		</script>
    <?php
        // if not logged in
        }else{
    ?>
    <body>
    <div class="box alone">
    <div class="wrapper">
        <a href="index.php"><?php echo $l["Please log in first"];?></a>
    </div>
        </div>
    <?php
        }
    ?>
	</body>
</html>

