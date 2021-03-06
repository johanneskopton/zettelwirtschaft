<div id="menu">
    <form method="get" autocomplete="off" action="<?php echo $_SERVER['SCRIPT_NAME'];?>">
        <div class="autocomplete">
            <input id="openInput" type="text" name="link" id="new_name_text" autofocus>
        </div>
        <input class="button" type="submit" name="open" value="<?php echo $l["Open"];?>">
    </form>
    <?php
        include_once('src/autocomplete.php');
    ?>
    <script>
        autocomplete(document.getElementById("openInput"), countries);
    </script>
    <form method="get" action="edit.php" target="_blank">
    <?php echo $l["Filename"];?>: <input type="text" name="link" id="new_name_text"> 
        <input class="button" type="submit" name="create" value="<?php echo $l["Create"];?>">
    </form>
    <?php
        if ($namespace == ""){
    ?>
    <form enctype="multipart/form-data" action="" method="POST">
        <!-- MAX_FILE_SIZE muss vor dem Datei-Eingabefeld stehen -->
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
        <!-- Der Name des Eingabefelds bestimmt den Namen im $_FILES-Array -->
        <input name="bibfile" type="file"><input class="button" type="submit" style="width: 12em;" name="bib_submit" value="<?php echo $l["Upload bibtex"];?>">
    </form>

    <form method="post" action="overview.php">
        <label for="delete_check"><?php echo $l["Delete zettel"];?></label><input type="checkbox" id="delete_check" name="delete_check" onchange='deleteCheckChange(this);' value="yes">
        <input type="hidden" name="delete_name" value="<?php echo $filename; ?>">
        <input class="button" type="submit" name="delete" id="delete_button" value="<?php echo $l["Delete"];?>">
    </form>
    <?php
        }
    ?>
<?php
    $overview_name_link = ($namespace != "")?"?user=$namespace":"";
?>
<a class="button" href="<?php echo $_SERVER['SCRIPT_NAME'];?>"><?php echo $l["Start"];?></a>
<a class="button" href="overview.php<?php echo $overview_name_link;?>"><?php echo $l["All Zettel"];?></a>
<a class="button" href="graph.php"><?php echo $l["Graph"];?></a>
<a class="button" href="access_rights.php" style="width:10em;"><?php echo $l["Manage access rights"];?></a><br>
<a class="button" href="index.php"><?php echo $l["Logout"];?></a>
</div>

<script>
    function setCursorPosition(ctrl, pos) {
        if (ctrl.setSelectionRange) {
            ctrl.focus();
            ctrl.setSelectionRange(pos, pos);
        }
    }
    function toggleForm(){
        var new_name_box = document.getElementById("menu");
        var focus_element = document.getElementById("openInput");
        var display = new_name_box.style.display;
        if (display == "block"){
            new_name_box.style.display = "none";
        } else {
            new_name_box.style.display = "block";
            setCursorPosition(new_name_text, 0);
            focus_element.focus();
        }
    }
    document.addEventListener ("keydown", function (zEvent) {
        if (zEvent.altKey  &&  zEvent.key === "m") {  // case sensitive
            toggleForm();
        }
    } );


    function deleteCheckChange(checkbox) {
        var delete_button = document.getElementById("delete_button");
        if(checkbox.checked == true){
            delete_button.removeAttribute("disabled");
            delete_button.classList.remove('disabled');
        }else{
            delete_button.setAttribute("disabled", "disabled");
            delete_button.classList.add('disabled');
        }
    }
    deleteCheckChange(document.getElementById("delete_check"));
</script>
