<html>
    <head>
    <meta meta name="viewport" content="width=device-width, user-scalable=no" />
    <link rel="shortcut icon" type="image/png" href="style/favicon.png">
    <link rel="stylesheet" type="text/css" href="style/common.css"/>
    <link rel="stylesheet" type="text/css" href="style/login.css"/>

    <?php
        session_start();
        session_unset();

        require_once("lang/language.php");
    ?>
    <title>Zettelwirtschaft</title>
    </head>
    <body class="home">
    <div class="box alone">
        <div class="wrapper">
        <h1><?php echo $l["Login"]; ?></h1>
        <form action="view.php" method="post">
            <div class="login_container">
                <label for="uname" class="textlabel"><?php echo $l["Username"]; ?></label>
                <input type="text" name="uname" required>
                <br>
                <label for="psw" class="textlabel"><?php echo $l["Password"]; ?></label>
                <input type="password" name="psw" required>
                <br>
                <!--<label>
                <input type="checkbox" checked="checked" name="remember"> <?php echo $l["Remember me"]; ?>
                </label><br>-->
                <button type="submit" name="login"><?php echo $l["Login"]; ?></button>

            </div>
        </form>
        <br>
        <a href="register.php"><?php echo $l["Register"]; ?></a>

        </div>
    </div>
    </body>
</html>