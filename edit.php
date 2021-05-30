<html>
    <head>
    <script src="src/codemirror/lib/codemirror.js"></script>
    <script src="src/codemirror/addon/hint/show-hint.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async
            src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js">
    </script>
    <link rel="stylesheet" href="src/codemirror/lib/codemirror.css">
    <link rel="stylesheet" href="src/codemirror/addon/hint/show-hint.css">

    <script src="src/codemirror/mode/markdown/markdown.js"></script>

    <link rel="shortcut icon" type="image/png" href="style/favicon.png">
    <meta meta name="viewport" content="width=device-width, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="style/common.css"/>
    <link rel="stylesheet" type="text/css" href="style/zettel.css"/>
    <link rel="stylesheet" type="text/css" href="style/edit.css"/>

    <?php
        session_start();
        require_once("lang/language.php");

        if(isset($_SESSION["user"])){
        require_once("src/orgile.php");
        require_once("src/get_zettel.php");
        require_once("src/update_db.php");
        include_once("src/bibupload_action.php");


        echo "<title>" . $title . "</title>";

        if (isset($_POST["submit"])) {
            if (!array_key_exists($username, $external_paths)){
                $content = $_POST["code"];
                $content = preg_replace('/^(\#\+last_modified:){1}\s+?(.+)/im', "$1 ". date("Y-m-d"), $content);


                file_put_contents("zettel/$username/$filename.org", $content);            
                update_db($filename, $content);
            } else {
                echo "Can not write on external zettelkasten!";
            }
        }
        if ($namespace != ""){
            echo "<script>window.location.replace('view.php?link=". $file_id ."');</script>";
        }

    ?>
    </head>
    <body>
        <div class="two_col">
            <div class="box side">
                <form method="post">

                    <div class="edit_wrapper">
                        <?php
                            include("src/create_editor.php");
                        ?>
                    </div>

                    <div class="buttonbox box">
                        <input class="button" type="submit" name="submit" id="submit" value="<?php echo $l["Save"]; ?>">
                    </div>
                </form>
            </div>
                
            
            <div class="box side">
                <?php
                    include("src/zettel.php");
                ?>
            </div>
        </div>
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