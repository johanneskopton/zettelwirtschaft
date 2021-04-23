    <div class="zettel_wrapper">
        <?php
            $orgile = new orgile();
            echo $orgile->orgileThis($content, $namespace);
        ?>
    <?php
        require_once("src/db_connect.php");
        $sql = "SELECT origin_name FROM connections WHERE target_name='$file_id'";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            echo "<div class='incoming'>";
            echo "Links to this zettel:";
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                $origin_name = $row['origin_name'];
                $res = $mysqli->query("SELECT title FROM zettel WHERE `name`='$origin_name'");
                if ($res) {
                    $row = $res->fetch_row();
                    $origin_title =  $row[0];
                }
                echo "<li><a href='" . $_SERVER['PHP_SELF'] . "?link=$origin_name'>$origin_title</a></li>";
            }
            echo "</ul></div>";
        } else {
            //echo "0 results";
        }
    ?>
          </div>
  
    
    <div class="buttonbox box">
        <?php
            if ($_SERVER["PHP_SELF"] == "/edit.php"){
                echo "<a href='index.php?link=$filename' name='toview' class='button'>View</a>";
            } else {
                if ($namespace == ""){
                    echo "<a href='edit.php?link=$filename' class='button'>Edit</a>";
                }
            }
        ?>
        <a class='button' onclick="toggleForm()">New</a>
        <div id="new_name">
            <form method="get" action="edit.php" target="_blank">
                Filename: <input type="text" name="link" id="new_name_text" autofocus>
                <input class="button" type="submit" name="create" value="Create">
            </form>
        </div>
        <script>
            function setCursorPosition(ctrl, pos) {
                if (ctrl.setSelectionRange) {
                    ctrl.focus();
                    ctrl.setSelectionRange(pos, pos);
                }
            }
            function toggleForm(){
                var new_name_box = document.getElementById("new_name");
                var new_name_text = document.getElementById("new_name_text");
                var display = new_name_box.style.display;
                if (display == "block"){
                    new_name_box.style.display = "none";
                } else {
                    new_name_box.style.display = "block";
                    setCursorPosition(new_name_text, 0);
                    new_name_text.focus();
                }
            }
        </script>
    </div>
