<?php
    require_once("config/db_connect.php");
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
?>

<textarea name="code" class="code"><?php echo $content;?></textarea>
<div id="vim">
    <div>Key buffer: <span id="command-display"></span></div>
    <div>Vim mode: <span id="vim-mode"></span></div>
</div>

<script>
    var textBox = document.getElementsByName("code")[0];
    var title = document.title;

    CodeMirror.registerHelper("hint", "zettel", function(editor, options) {
        var cur = editor.getCursor();
        var end = cur.ch, start = end;
        var list = [<?php 
                        $res = $mysqli->query("SELECT name FROM zettel WHERE `user`='$username' ORDER BY name ASC");
                        while($row = $res->fetch_assoc()) {
                            $name = $row["name"];
                            echo "'$name', ";
                        }
                        echo "";
                    ?>];
        return {list: list, from: CodeMirror.Pos(cur.line, start), to: CodeMirror.Pos(cur.line, end)};
    });

    CodeMirror.commands.autocomplete = function(cm) {
        cm.showHint({hint: CodeMirror.hint.zettel});
    }

    var myCodeMirror = CodeMirror.fromTextArea(textBox, {
        lineWrapping: true,
            theme: "<?php echo $theme;?>",
	//keyMap: "vim",
        highlightFormatting: true,
        extraKeys: {
            "Ctrl-Space": "autocomplete"
        }
    });
    var commandDisplay = document.getElementById('command-display');
    var keys = '';
    CodeMirror.on(myCodeMirror, 'vim-keypress', function(key) {
      keys = keys + key;
      commandDisplay.innerText = keys;
    });
    CodeMirror.on(myCodeMirror, 'vim-command-done', function(e) {
      keys = '';
      commandDisplay.innerHTML = keys;
    });
    var vimMode = document.getElementById('vim-mode');
    var vimBox = document.getElementById('vim');
    CodeMirror.on(myCodeMirror, 'vim-mode-change', function(e) {
      vimMode.innerText = JSON.stringify(e);
      if (JSON.stringify(e) == '{"mode":"insert"}'){
        vimBox.style.display = "none";
      } else {
        vimBox.style.display = "block";
      }
    });
    //var vim_insert_event = new CustomEvent("vim-mode-change", {"mode":"insert"});
    //myCodeMirror.dispatchEvent(vim_insert_event);
    myCodeMirror.focus();
    //CodeMirror.Vim.enterInsertMode(myCodeMirror, {}, myCodeMirror.state.vim);
    //CodeMirror.Vim().leaveVimMode(myCodeMirror);
    //myCodeMirror.toggleOverwrite(false);
    //myCodeMirror.setOption('keyMap', 'vim-insert');
    //CodeMirror.signal(myCodeMirror, "vim-mode-change", {mode: "insert"});
    //CodeMirror.signal(myCodeMirror, "vim-mode-change", {mode: "normal"});
    //CodeMirror.signal(myCodeMirror, "vim-mode-change", {mode: "insert"});
    myCodeMirror.on("change", function(cm,change){
            var viewlink = document.getElementsByName("toview")[0];
            viewlink.classList.add('disabled');
            viewlink.removeAttribute("href");
            document.getElementsByName("zettelkasten_link").forEach(function(element, idx) {
                element.removeAttribute("href");
            });
            document.title = title + "*";
        });

    document.addEventListener("keydown", function(e) {
    if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)  && e.keyCode == 83) {
        e.preventDefault();
        document.getElementById("submit").click();
    }
    }, false);
</script>
