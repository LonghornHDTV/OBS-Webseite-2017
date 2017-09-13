<?php
require_once ('config.php');

$conn = mysqli_connect(
                  $MYSQL_HOST,
                  $MYSQL_LOGIN_BENUTZER,
                  $MYSQL_LOGIN_PASSWORT,
                  $MYSQL_DATENBANK
                );

mysqli_set_charset($conn, 'utf8');

if(!$conn->connect_errno) {
//Validate
  if(isset($_POST['username'])) {
    if(isset($_POST['password'])) {
      //Check Login
    //  if(!mysqli_select_db($MYSQL_DATENBANK, $conn)) {
    //    die("<font style=\"color: red;\">Keine Verbindung zur Datenbank.</font>");
    //  }

      $sql = "SELECT * FROM logins WHERE Username = ? LIMIT 1";
      $statement = $conn->prepare($sql);
      if(!$statement) {
        print_r($conn);
        die("Query Error with INSERT: " . $conn->prepare($sql) . " : " . $sql . " : ");
      }
      $statement->bind_param('s', $username);

      $username = $_POST['username'];
      $password = $_POST['password'];

      if(!$statement->execute()) {
        die("Query fehlgeschlagen: " .$statement->error);
      }
      $statement->store_result();

      $statement->bind_result($usernamedb, $db_password);
      $statement->fetch();
      if($statement->num_rows == 1) {
        echo "Erfogreich eingeloggt. [DEBUG]: " . $usernamedb . " : " . $db_password;
      } else{
        die("Den Benutzer " . $username . " gibt es nicht.");
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
         <a href="#loginmodal" class="flatbtn" id="modaltrigger">
           <span style="vertical-align: bottom;">Login</span>
         </a>
       </center>
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
     <script type="text/javascript">
       $(function(){
         $('#modaltrigger').leanModal({top: 110, overlay: 0.45, closeButton: ".hidemodal"});
       });
     </script>
   </body>
 </html>
