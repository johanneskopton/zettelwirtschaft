<?php
    require_once("config/design.php");
    require_once(__DIR__."/../config/external.php");
    require_once(__DIR__."/../config/db_connect.php");
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($mysqli->connect_error) {
        echo "</head><body>Connection failed: " . $mysqli->connect_error;
    }
    session_start();
    require_once("lang/language.php");
?>

<script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async
        src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js">
</script>
<script src="https://unpkg.com/vexflow/releases/vexflow-min.js"></script>

<link rel="shortcut icon" type="image/png" href="style/favicon.png">
<meta meta name="viewport" content="width=device-width, user-scalable=no" />
<link rel="stylesheet" type="text/css" href="style/common.css"/>
<link rel="stylesheet" type="text/css" href="style/zettel.css"/>
<link rel="stylesheet" type="text/css" href="style/colors.css"/>
<link rel="stylesheet" type="text/css" href="style/prettify-solarized/light.css">

