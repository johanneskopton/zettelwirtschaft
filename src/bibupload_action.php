<?php
    if (isset($_POST["bib_submit"]) && !empty($_FILES['bibfile']) && $_FILES['bibfile']['size'] > 0){
        if (!file_exists("bibliography")) {
            mkdir("bibliography");
        }
        
        $uploadpath = "bibliography/$username.bib";
        if (move_uploaded_file($_FILES['bibfile']['tmp_name'], $uploadpath)) {
            //echo "Success!<br>";
        }
    }
?>