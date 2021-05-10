<?php
    require_once(__DIR__."/../config/db_connect.php");
    require_once("update_db.php");


    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);


    if(isset($_POST["register"])){
        $name = $_POST["uname"];
        $email = $_POST["mail"];

        $sql = "SELECT * FROM user WHERE `name`='$name' OR `email`='$email'";
        $result = $mysqli->query($sql);
        if (mysqli_num_rows($result) == 0){
            $pass = $_POST["psw"];
            $hash = password_hash($pass, PASSWORD_DEFAULT);

            $sql = "INSERT INTO user (`name`, `email`, `password`) VALUES ('$name','$email', '$hash')";
            if ($mysqli->query($sql) === TRUE) {
                //echo "New Zettelkasten created successfully!<br><br>";

                $new_dir_path = __DIR__."/../zettel/$name";
                mkdir($new_dir_path);

                $content  = "#+TITLE: Start\n";
                $content .= "#+ROAM_TAGS: Start\n";
                $content .= "#+CREATED: " . date("Y-m-d") . "\n";
                $content .= "#+LAST_MODIFIED: " . date("Y-m-d") . "\n";
                $content .= file_get_contents(__DIR__."/startzettel_template.txt");

                $file = fopen($new_dir_path ."/start.org", "w");
                fwrite($file, $content);
                fclose($file);
                $_SESSION['user'] = $name;

                $username = $name;
                update_db("start", $content);
            } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error . "<br>";
            }
            
        }else{
            echo "Username or mail already exists.<br></body></html>";
            exit();
        }
    }
?>