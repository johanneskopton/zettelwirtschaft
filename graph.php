<html>
  <head>
    <title>Network</title>
<?php
session_start();
require_once("lang/language.php");
?>
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
