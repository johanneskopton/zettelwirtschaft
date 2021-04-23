<html>
    <head>
    <meta meta name="viewport" content="width=device-width, user-scalable=no" />
    <link rel="shortcut icon" type="image/png" href="style/favicon.png">
    <link rel="stylesheet" type="text/css" href="style/common.css"/>
    <link rel="stylesheet" type="text/css" href="style/zettel.css"/>

    <?php
        require_once("src/orgile.php");
        require_once("src/get_zettel.php");
        echo "<title>" . $title . "</title>";
    ?>
    </head>
    <body>
    <div class="box alone">
        <?php
            require("src/zettel.php");
        ?>
    </div>

    </body>
</html>