<?php
$mysqli = new mysqli("localhost", "phpmyadminuser", "password", "zettelkasten");
if ($mysqli->connect_errno) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}
?>