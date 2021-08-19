<html>
    <head>
    <?php
        session_start();
        require_once("lang/language.php");
        include_once("src/header.php");
    ?>
    </head>
    <body class="<?php echo $theme;?>">
    <div class="box alone">
        <div class="wrapper">
            <?php
                if(isset($_SESSION["user"])){
                    $username = $_SESSION["user"];
                    include("src/get_access_rights.php");
                }else{
                    echo "<a href='index.php'>".$l["Please log in first"]."</a>";
                }
            ?>
        </div>
    </div>
    </body>
</html>
