    <div class="zettel_wrapper<?php if ($namespace != ""){echo " with_banner";} ?>">
        <?php
            if ($access){
            if ($namespace != ""){
                echo "<div class='external_banner'>$namespace</div>";
            }

            $orgile = new orgile();
            echo $orgile->orgileThis($content);
        ?>
        <?php
            require_once("citations.php");
            print_citations($content);
            include_once("backlinks.php");
        }else{
            echo $l["Access denied"];
        }
        ?>
    </div>
    <script>
        function copyclip(content){
            navigator.clipboard.writeText("[ztl:" + content + "]");
        }

        var h1 = document.getElementsByTagName("H1")[0];
        h1.setAttribute("onclick", "copyclip('<?php echo $filename;?>')");

        var h2s = document.getElementsByTagName("H2");
        for (let i=0; i<h2s.length; i++){
            var h2 = h2s[i];
            h2.setAttribute("onclick", "copyclip('<?php echo $filename;?>#" + h2.id + "')");
        };
    </script>
    <div class="buttonbox box">
        <?php
            $filepath_bits = explode("/", $_SERVER["SCRIPT_NAME"]);
            if (end($filepath_bits ) == "edit.php"){
                echo "<a href='view.php?link=$filename' name='toview' class='button'>".$l["View"]."</a>";
            } else {
                if ($namespace == "" && !array_key_exists($username, $external_paths)){
                    echo "<a href='edit.php?link=$filename' class='button'>".$l["Edit"]."</a>";
                }
            }
        ?>
        <a class='button' onclick="toggleForm()" accesskey="m"><?php echo $l["Menu"];?></a>

        <?php
            include("src/menu.php");
        ?>
    </div>
