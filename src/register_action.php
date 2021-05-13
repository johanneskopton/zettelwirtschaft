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
                $new_dir_path = "zettel/$name";
                if (!file_exists($new_dir_path)) {
                    mkdir($new_dir_path, 0777, True);
                }

                $content  = "#+TITLE: Start\n";
                $content .= "#+ROAM_TAGS: Start\n";
                $content .= "#+CREATED: " . date("Y-m-d") . "\n";
                $content .= "#+LAST_MODIFIED: " . date("Y-m-d") . "\n";
                $content .= file_get_contents(__DIR__."/startzettel_template.txt");

                $_SESSION['user'] = $name;
                $username = $name;
                $new_start_path = $new_dir_path ."/start.org";
                if (!file_exists($new_start_path )) {
                    $file = fopen($new_start_path , "w");
                    fwrite($file, $content);
                    fclose($file);
                }else{
                    require_once("src/helper.php");
                    $content = get_content("", "start");
                }                

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