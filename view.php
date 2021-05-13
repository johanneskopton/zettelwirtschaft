<html>
    <head>
    <meta meta name="viewport" content="width=device-width, user-scalable=no" />
    <link rel="shortcut icon" type="image/png" href="style/favicon.png">
    <link rel="stylesheet" type="text/css" href="style/common.css"/>
    <link rel="stylesheet" type="text/css" href="style/zettel.css"/>

    <?php
        session_start();
        require_once("lang/language.php");


        include_once("src/register_action.php");

        if(isset($_SESSION["user"])){
        require_once("src/orgile.php");
        require_once("src/get_zettel.php");
        include_once("src/bibupload_action.php");

        echo "<title>" . $title . "</title>";
    ?>
    </head>
    <body>
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