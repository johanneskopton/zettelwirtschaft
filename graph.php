<html>
    <head>
        <?php
            include_once("src/header.php");
            require_once("lang/language.php");
        ?>
            <title><?php echo $l["Graph"];?></title>
        <script
          type="text/javascript"
          src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"
        ></script>
        <style type="text/css">
            body {
                height: 90vh;
            }
            #mynetwork {
                width: 100%;
                height: 100%;
            }
        </style>
    </head>
    <body>
        <?php
            include "src/get_graph.php";
        ?>
    </body>
</html>
