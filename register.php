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
    <body>
    <div class="box alone">
        <div class="wrapper">
        <h1><?php echo $l["Register"]; ?></h1>
        <form action="view.php" method="post">
            <div class="login_container">
                <label for="uname" class="textlabel"><?php echo $l["Username"]; ?></label>
                <input type="text" name="uname" required>
                <br>
                <label for="mail" class="textlabel"><?php echo $l["Email"]; ?></label>
                <input type="email" name="mail" required>
                <br>
                <label for="psw" class="textlabel"><?php echo $l["Password"]; ?></label>
                <input type="password" name="psw" required>
                <br>
                <button type="submit" class="register" name="register"><?php echo $l["Register"]; ?></button>

            </div>
        </form> 

        </div>
    </div>
    </body>
</html>