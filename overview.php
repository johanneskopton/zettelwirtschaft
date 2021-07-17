<html>
    <head>
    <?php
        session_start();
        require_once("lang/language.php");
        include_once("src/header.php");
    ?>
    <title><?php echo $l["All Zettel"]; ?></title>
    </head>
    <body class="<?php echo $theme;?>">
    <div class="box alone">
        <div class="wrapper">
        <?php
                if(isset($_SESSION["user"])){
                    $username = $_SESSION["user"];
                    include_once("src/delete_action.php");
                    include("src/get_overview.php");
                }else{
                    echo "<a href='index.php'>".$l["Please log in first"]."</a>";
                }
        ?>
        </div>
    </div>
    </body>
</html>
