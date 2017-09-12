<? php
require_once ('config.php');

$conn = mysqli_connect(
                  MYSQL_HOST,
                  MYSQL_LOGIN_BENUTZER,
                  MYSQL_LOGIN_PASSWORT,
                  MYSQL_DATENBANK
                );

mysqli_set_charset($conn, 'utf8');

if($conn) {
  echo 'Verbindung hergestellt.';
  print_r($conn);
} else {
  die('Keine Verbindung möglich: ' . mysql_error());
}
 ?>
