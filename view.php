<html>
    <head>
    <?php
        session_start();
        require_once("lang/language.php");


        include_once("src/register_action.php");
        include_once("src/login_action.php");


        if(isset($_SESSION["user"])){
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
            require("src/zettel.php");
        ?>
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
