<html>
    <head>
    <script src="src/codemirror/lib/codemirror.js"></script>
    <script src="src/codemirror/addon/hint/show-hint.js"></script>
    <link rel="stylesheet" href="src/codemirror/lib/codemirror.css">
    <link rel="stylesheet" href="src/codemirror/addon/hint/show-hint.css">
    <link rel="stylesheet" href="src/codemirror/theme/solarized.css">

    <script src="src/codemirror/mode/markdown/markdown.js"></script>

    <?php
        include_once("src/header.php");

        if(isset($_SESSION["user"])){
        include_once("src/get_zettel.php");
        require_once("src/orgile.php");
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

    <link rel="stylesheet" type="text/css" href="style/edit.css"/>
    </head>
    <body class="boxed <?php echo $theme;?>">
        <div class="two_col">
            <div class="box side">
                <form method="post">

                    <div class="edit_wrapper">
                        <?php
                            include("src/create_editor.php");
                        ?>
                    <script>
                        CodeMirror.signal(myCodeMirror, "vim-mode-change", {mode: "insert"});
                    </script>
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
