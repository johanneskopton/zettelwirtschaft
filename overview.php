<html>
    <head>
    <meta meta name="viewport" content="width=device-width, user-scalable=no" />
    <link rel="shortcut icon" type="image/png" href="style/favicon.png">
    <link rel="stylesheet" type="text/css" href="style/common.css"/>
    <link rel="stylesheet" type="text/css" href="style/zettel.css"/>

    <?php
        session_start();
        require_once("lang/language.php");
    ?>
    </head>
    <body>
    <div class="box alone">
        <div class="wrapper">

            <?php
                //include_once("src/login_action.php");
                
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