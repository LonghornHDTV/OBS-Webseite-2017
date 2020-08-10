<?php
require_once ('config.php');

$conn = mysqli_connect(
                  $MYSQL_HOST,
                  $MYSQL_LOGIN_BENUTZER,
                  $MYSQL_LOGIN_PASSWORT
                );

$mysqldatabase = "'" . $MYSQL_DATENBANK . "'";

mysqli_set_charset($conn, 'utf8');

if(!$conn->connect_errno) {
//Validate
  if(isset($_POST['username']) && ($_POST['username'] != "")) {
    if(isset($_POST['password']) && ($_POST['password'] != "")) {
      //VARS
      $isAdmin = false;
      //Is Admin?
      if($_POST['username'] == $PAGE_ADMIN_BENUTZER) {
        if($_POST['password'] == $PAGE_ADMIN_PASSWORT) {
          $isAdmin = true;
        }else{
          $isAdmin = false;
        }
      }else{
        $isAdmin = false;
      }

      //First Start?
      $checkfirststart = "SHOW DATABASES LIKE " . $mysqldatabase;
      if(!($checkfirststartsql = $conn->query($checkfirststart))) {
        print_r($conn);
        die("QUERY Error: query in login on show DATABASES : " . $mysqldatabase);
      }
      if($checkfirststartsql->num_rows == 0) {
        if($isAdmin) {
          $sql_befehl = "CREATE DATABASE IF NOT EXISTS " . $mysqldatabase;
          if(mysqli_query($conn, $sql_befehl)) {
            $conn->select_db($mysqldatabase);
          }else{
            die("Der MySQL-Benutzer " . $MYSQL_LOGIN_BENUTZER . " hat nicht genügend Rechte um eine Datenbank zu erstellen.");
          }
        }else{
          die("Die Seite wird gerade noch erstellt... Kommen Sie später wieder.");
        }
      }

      mysqli_select_db($conn, $MYSQL_DATENBANK);

//      if($result = mysqli_query($conn, "SELECT DATABASE()")) {
//        $row = $result->fetch_row();
//        printf("Default Database is %s.\n", $row[0]);
//        $result->close();
//      }

      $sql_befehl = "SHOW TABLES LIKE 'logins'";
      if($resultat = mysqli_query($conn, $sql_befehl)) {
        if($resultat->num_rows == 0) {
          $sql_befehl = "CREATE TABLE IF NOT EXISTS logins (Username TEXT(255), Password TEXT(255))";
          if(mysqli_query($conn, $sql_befehl)) {
            echo("Alle Daten wurden erstellt. Bitte Seite neu laden.");
            die("");
          }else{
            die("Der MySQL-Benutzer " . $MYSQL_LOGIN_BENUTZER . " hat nicht genügend Rechte um eine Tabelle zu erstellen.");
          }
        }
      }else{
        die("QUERY Error: query in login on show TABLES");
      }

      //Check Login
    //  if(!mysqli_select_db($MYSQL_DATENBANK, $conn)) {
    //    die("<font style=\"color: red;\">Keine Verbindung zur Datenbank.</font>");
    //  }
      mysqli_select_db($conn, $MYSQL_DATENBANK);



      $sql = "SELECT * FROM logins WHERE `Username` = ? AND `Password` = ? LIMIT 1";
      //$sql = "INSERT INTO  logins (`Username`, `Password`) VALUES (?,?)"; TEST!!!
      if(!$statement = $conn->prepare($sql)) {
        print_r($conn);
        die("Query Error with INSERT: " . $conn->prepare($sql) . " : " . $sql . " : ");
      }
      $username = $_POST['username'];
      $password = $_POST['password'];

      //UNBIND SQL Injection and it works :)))
      $username = stripslashes($username);
      $password = stripslashes($password);
      //echo "user: " . $username . " pass: " . $password;
      $username = mysqli_real_escape_string($conn, $username);
      $password = mysqli_real_escape_string($conn, $password);
      //echo "user1: " . $username . " pass1: " . $password;

      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
      $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
      $encrypedpassword = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $SALT, $password, MCRYPT_MODE_ECB, $iv);

      $encrypedpasswordwithbase64 = base64_encode($encrypedpassword);

      //print_r($encrypedpasswordwithbase64);

      $statement->bind_param('ss', $username, $encrypedpasswordwithbase64);


      if(!$statement->execute()) {
        die("Query fehlgeschlagen: " .$statement->error);
      }
      $statement->store_result();

      //$statement->bind_result($usernamedb, $db_password);
      $statement->fetch();
      if($statement->num_rows == 1) {
        echo "Erfogreich eingeloggt. Du wird automatisch weiter geleitet. Wenn das nicht funktioniert klicke <a href=\"index.php\">hier</a>";
      //  echo "<meta http-equiv=\"refresh\" content=\"10\" URL=\"/dashboard.html\">"; Dosen't Work :(
        die("");
      } else{
        if($isAdmin) {
          echo "Erfogreich eingeloggt als Admin. Du wird automatisch weiter geleitet. Wenn das nicht funktioniert klicke <a href=\"index.php\">hier</a>";
          die("");
        }
        $errorMessage = "Den Benutzer " . $username . " gibt es nicht.";
      }

    } else {
        $errorMessage = "Bitte ein Passwort angeben.";
    }
  } else {
    $errorMessage = "Bitte ein Benutzernamen angeben.";
  }
} else {
  die('Keine Verbindung möglich: '.$conn->connect_error);
}
 ?>

 <html lang="de-DE">
   <head>
     <meta http-equiv="content-type" content="text/html;charset=utf-8">
     <title>OBS Badenhausen - Demo</title>
     <link rel="shortcut icon" href="http://www.obs-badenhausen.de/templates/obs_badenhausen_j25a4107/favicon.ico" type="image/vnd.microsoft.icon">
     <link rel="icon" href="http://www.obs-badenhausen.de/templates/obs_badenhausen_j25a4107/favicon.ico" type="image/vnd.microsoft.icon">
     <link rel="stylesheet" type="text/css" media="all" href="css/main.css">
     <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
     <script type="text/javascript" charset="utf-8" src="js/jquery.leanModal.min.js"></script>
     <!-- Loading Jquery plgin leanModal under MIT License http://leanmodal.finelysliced.com.au/ -->
   </head>
   <?php
   if(isset($errorMessage)) {
    echo "<body>";
    echo "<div class=\"errorMessage\"><label>" . $errorMessage . "</label></div>";
  } else {
    echo "<body>";
  }
   ?>
   <div id="w">
     <h1>Welcome to the Demo Test Login Site</h1>
     <p>Click the login link below to expand the login window. This is only a demo.</p>
     <center>

     </center>
     <ul>
         <li>
           <a href="#" class="active">Home</a>
         </li>
         <li style="float: right;">
           <?php
             require_once('checkSession.php');
             if($CHECK) {
               echo "<a href=\"#profilemodal\" class=\"flatbtn\" id=\"modaltrigger\">
                 <span style=\"vertical-align: bottom;\">Profil</span>
               </a>";
             }else{
               echo "<a href=\"#loginmodal\" class=\"flatbtn\" id=\"modaltrigger\">
                 <span style=\"vertical-align: bottom;\">Login</span>
               </a>";
             }
           ?>

         </li>

     </ul>
   </div>
   <div id="loginmodal" style="display: none;">
     <h1>Benutzer Login</h1>
     <form id="loginform" name="loginfrom" method="post" action="login.php">
       <label for="username">Benutzername:</label>
       <input type="text" name="username" id="username" class="txtfield" tabindex="1">

       <label for="password">Passwort:</label>
       <input type="password" name="password" id="password" class="txtfield" tabindex="2">

       <div class="center">
         <input type="submit" name="loginbtn" class="flatbtn-blu hidemodal" value="Log In" tabindex="3">
       </div>
     </form>
   </div>

 </body>
</html>
