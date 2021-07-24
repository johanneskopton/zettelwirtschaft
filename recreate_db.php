<?php
    session_start();
    $username = $_SESSION["user"];

    require_once("config/db_connect.php");
    require_once("src/helper.php");
    require_once("config/external.php");
    require_once("lang/language.php");

    function get_external_list($external_user){
        global $external_paths, $username;

        $base_path = $external_paths[$external_user];
        $path = $base_path . "get_raw.php?list_all=$external_user";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_POST, 1);
        $credentials = array('name' => $username, 'pass' => $_SESSION['pass']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($credentials));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $list = curl_exec($ch);
        curl_close($ch);
        return explode("\n", $list);
    }

    function update_db_zettel($user, $filename, $external){
        global $db_host, $db_user, $db_pass, $db_name, $username, $l;
        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

        $name = explode(".org", $filename)[0];
        if ($name == ""){
            return;
        }

        echo "$name<br>";

        $content = get_content($user, $name);
        if ($content == $l["Access denied"]){
            return;
        }

        $title = get_title($content);

        $date_creation = get_creation_date($content);
        $date_modified = get_modified_date($content);
        $tag = get_tag($content);

        $word_count = str_word_count($content);

        if ($external){
            $sql = "INSERT INTO zettel (`name`, `title`, `user`, `words`, `tag`, `date_creation`, `date_modified`, `access`) VALUES ('$name','$title', '$user', '$word_count', '$tag', '$date_creation', '$date_modified', 1)";
        } else {
            $sql = "SELECT * FROM zettel_old WHERE `user`='$user' AND `name`='$name' AND `access`=1";
            $result = $mysqli->query($sql);
            $access = (mysqli_num_rows($result) == 1)?1:0;
            $sql = "INSERT INTO zettel (`name`, `title`, `user`, `words`, `tag`, `date_creation`, `date_modified`, `access`) VALUES ('$name','$title', '$user', '$word_count', '$tag', '$date_creation', '$date_modified', $access)";
        }
        if (!$mysqli->query($sql) === TRUE) {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }

        $connections = find_connections($content);
        for ($i = 0; $i < sizeof($connections[0]); $i++){
            if ($connections[2][$i] != ""){
                $targetname = $connections[2][$i];
            } elseif ($connections[4][$i] != ""){
                $targetname = $connections[4][$i];
            }
            // echo "$targetname<br>";
            if (strpos($targetname, ":")){
                $target_zettel = explode(":", $targetname)[1];
                $target_user = explode(":", $targetname)[0];
            }else{
                $target_zettel = $targetname;
                $target_user = $user;
            }
            $sql = "INSERT INTO connections (`origin_name`, `target_name`, `origin_user`, `target_user`) VALUES ('$name', '$target_zettel', '$user', '$target_user')";
            if ($mysqli->query($sql) === TRUE) {
                // echo "New record created successfully";
            } else {
                //echo "Error: " . $sql . "<br>" . $mysqli->error;
            }
        }
    }

    
    function update_db(){
        global $db_host, $db_user, $db_pass, $db_name, $external_paths;
        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

        $sql = "DROP TABLE zettel_old";
        $mysqli->query($sql);

        $sql = "ALTER TABLE zettel RENAME TO zettel_old";
        $mysqli->query($sql);
    
        $sql = "DROP TABLE connections";
        $mysqli->query($sql);

        $sql = file_get_contents("database/schema.sql");
        $mysqli->multi_query($sql);
        do{}while(mysqli_next_result($mysqli));

        $sql = "SELECT * FROM user";
        $result = $mysqli->query($sql);
        while($row = $result->fetch_assoc()) {
            $user = $row["name"];
            echo "<h2>$user</h2>";
            if (array_key_exists($user, $external_paths)){
                $list = get_external_list($user);
                foreach($list as $filename){
                    update_db_zettel($user, $filename, True);
                }
            } elseif ($handle = opendir("zettel/$user")) {
                while (false !== ($filename = readdir($handle))) {
                    if (substr($filename, 0, 1) != "."){
                        update_db_zettel($user, $filename, False);
                    }
                }
                closedir($handle);
            }
        }
        $mysqli->close();
    }
    update_db();
?>
