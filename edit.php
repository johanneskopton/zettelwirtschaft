<html>
    <head>
    <script src="src/codemirror/lib/codemirror.js"></script>
    <link rel="stylesheet" href="src/codemirror/lib/codemirror.css">
    <script src="src/codemirror/mode/markdown/markdown.js"></script>

    <link rel="shortcut icon" type="image/png" href="style/favicon.png">
    <meta meta name="viewport" content="width=device-width, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="style/common.css"/>
    <link rel="stylesheet" type="text/css" href="style/zettel.css"/>
    <link rel="stylesheet" type="text/css" href="style/edit.css"/>

    <?php
        require_once("src/orgile.php");
        require_once("src/get_zettel.php");
        require_once("src/update_db.php");
        require_once("lang/language.php");

        echo "<title>" . $title . "</title>";

        if (isset($_POST["submit"])) {
            $content = $_POST["code"];
            $content = preg_replace('/^(\#\+last_modified:){1}\s+?(.+)/im', "$1 ". date("Y-m-d"), $content);


            file_put_contents("zettel/" . $filename . ".org", $content);            
            update_db();
        }
        if ($namespace != ""){
            echo "<script>window.location.replace('index.php?link=". $file_id ."');</script>";
        }

    ?>
    </head>
    <body>
        <div class="two_col">
            <div class="box side">
                <form method="post">

                    <div class="edit_wrapper">
                            <textarea name="code" class="code"><?php
                                    echo $content;
                                ?></textarea>
                            
                        <script>
                            var textBox = document.getElementsByName("code")[0];
                            var title = document.title;
                            var myCodeMirror = CodeMirror.fromTextArea(textBox, {
                                lineWrapping: true,
                                theme: "default",
                                highlightFormatting: true
                            });
                            myCodeMirror.on("change", function(cm,change){
                                    var viewlink = document.getElementsByName("toview")[0];
                                    viewlink.classList.add('disabled');
                                    viewlink.removeAttribute("href");
                                    document.getElementsByName("zettelkasten_link").forEach(function(element, idx) {
                                        element.removeAttribute("href");
                                    });
                                    document.title = title + "*";
                                });
                        
                            document.addEventListener("keydown", function(e) {
                            if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)  && e.keyCode == 83) {
                                e.preventDefault();
                                document.getElementById("submit").click();
                            }
                            }, false);
                        </script>

                    </div>

                    <div class="buttonbox box">
                        <input class="button" type="submit" name="submit" id="submit" value="<?php echo $l["Save"]; ?>">
                    </div>
                </form>
            </div>
                
            
            <div class="box side">
            <?php
                require("src/zettel.php");
            ?>
            </div>
        </div>
    </body>
</html>