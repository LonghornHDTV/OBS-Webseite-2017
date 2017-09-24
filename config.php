<?php
$MYSQL_HOST = '127.0.0.1'; // Bitte ändern falls der MySQL Server nicht lokal läuft.

$MYSQL_LOGIN_BENUTZER = 'root2'; //Bitte hier der Benutzer der alle Rechte auf die Datenbank hat.
$MYSQL_LOGIN_PASSWORT = 'admin2'; //Bitte hier das Passwort von dem Benutzer.

$MYSQL_DATENBANK = 'schoolpage'; //Hier bitte den Datenbank Namen eingeben.


$PAGE_ADMIN_BENUTZER = 'root'; //Bitte unbediengt ändern da man mit diesem User volle Rechte auf die Webseite hat.
$PAGE_ADMIN_PASSWORT = 'admin'; //Bitte das Passwort ändern.

//================================================================
//Configuration für die Passwort Verschlüsslung und Entschlüsslung
//================================================================

$SALT = 'passwortpasswort'; //Bitte ändern. !!!Wenn geändert wird funktionieren alle Pasworter die gespeichert worden nicht mehr!!!
//ACHTUNG Der "SALT" darf nur 16, 24 oder 32 Zeichen lang sein.
?>
