<html>
    <head>
    <meta meta name="viewport" content="width=device-width, user-scalable=no" />
    <link rel="shortcut icon" type="image/png" href="style/favicon.png">
    <link rel="stylesheet" type="text/css" href="style/common.css"/>
    <link rel="stylesheet" type="text/css" href="style/manual.css"/>
    <?php
            include_once("src/header.php");
            require_once("lang/language.php");
    ?>
    <title>
    <?php
            echo $l["Manual"];
    ?></title>
    </head>
    <body class="home <?php echo $theme;?>">
        <div class="box alone">
            <div class="wrapper">
                <h1 style="margin-top: 0px;"><?php echo $l["Manual"];?></h1>
                <?php
                    if ($l == $german){
                ?>
                Moinsen! <br>In dieser Anleitung lernst du, wie du Zettelwirtschaft benutzen kannst.
                <h2>Neuen Zettel erstellen</h2>
                <ul>
                    <li>Klicke auf "Menü".</li>
                    <li>Gib einen Dateinamen ins Textfeld ein. Mit diesem Namen wirst du später auf deinen Zettel verweisen.
                        Er ist auch gleichzeitig der Dateiname deines Zettels und
                        sollte nur Kleinbuchstaben und Unterstriche enthalten.</li>
                    <li>Klicke auf "Erstellen"</li>
                    <li>Dein neuer Zettel sollte ungefähr so aussehen: <br>
                    <pre>
    #+TITLE: barack_obama
    #+ROAM_TAGS: Idee
    #+CREATED: 2021-05-19
    #+LAST_MODIFIED: 2021-05-19
                    </pre></li>
                    <li>Ändere den Titel wie du möchtest, z. B. "Barack Obama".</li>
                    <li>Ändere den Tag hinter <code>#+ROAM_TAGS:</code> wie du möchtest, z. B. "Person".</li>
                    <li>Um die Datumseigenschaften brauchst du dich nicht zu kümmern. Das erledigt Zettelwirtschaft für dich.</li>
                    <li>Tada! Fertig ist dein erster neuer Zettel. Unter die 4 Zeilen, die von Anfang an da sind,
                        kannst du jetzt deine Notizen schreiben.</li>
                </ul>
                <h2>Links und Formatierungen</h2>
                <h3>Links</h3>
                Ein Tipp zum Einfügen von Links: mit STRG+Leertaste bekommst du eine Liste mit den Namen von all deinen Zetteln.
                <ul>
                    <li>Interne Links: <code>[ztl:dateiname_des_zettels]</code></li>
                    <li>Interne Links mit eigenem Text <code>[[ztl:dateiname][eigener_text]]</code></li>
                    <li>OrgMode-kompatibel <code>[[file:dateiname.org][eigener_text]]</code></li>
                    <br>
                    <li>Links zu anderem Zettelkasten: <code>[ext:nutzername:zettelname]</code></li>
                    <li>Links zu anderem Zettelkasten mit eigenem Text: <code>[[ext:nutzername:zettelname][eigener_text]]</code></li>
                    <li>Link zu Webseite: <code>[[http://wikipedia.org][Wikipedia]]</code></li>
                    <br>
                    <li>Zitierung: <code>[cite:citekey]</code></li>
                </ul>
                <h3>Überschriften</h3>
<pre>   
* Überschrift Level 1
** Überschrift Level 2
*** Überschrift Level 3
**** Überschrift Level 4
</pre>
                <h3>Zitate</h3>
<pre>
#+begin_quote
Du kannst etwas verändern – jeden Tag und zu jeder Zeit!
#+end_quote
- Jane Goodall, britische Verhaltensforscherin
</pre>
                <h3>Aufzählungen/Listen</h3>
Jede Ebene ist mit zwei Leerzeichen mehr eingerückt (die erste auch mit 2, nicht mit 0).
<pre>
  + Aufzählungen
    + mit mehreren
    + Ebenen
  - Minus geht auch
    - sogar
      - drei Ebenen
  1) nummerierte
  2) Aufzählungen
</pre>
                <h3>Sonstige Formatierungen</h3>
<pre>
/kursiv/
*fett*
</pre>
                <?php
                    } elseif ($l == $german){
                ?>

                <?php
                    }
                ?>
            </div>
        </div>
    </body>
</html>
