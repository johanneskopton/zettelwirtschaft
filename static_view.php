<html>
    <head>
    <?php
        session_start();
        require_once("lang/language.php");

        $_SESSION["user"] = "kopton.org";
        $_SESSION["pass"] = "WR\SC;o%wf4Z"; 

        require_once("src/get_zettel.php");
        require_once("src/orgile.php");


        include_once("src/bibupload_action.php");

        echo "<title>" . $title . "</title>";
        include_once("src/header.php");
    ?>
    </head>
    <body class="<?php echo $theme;?>">
    <div class="box alone">
        <?php
            require("src/static_zettel.php");
        ?>
    </div>
    </body>
</html>
