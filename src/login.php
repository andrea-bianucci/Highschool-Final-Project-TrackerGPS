<?php
  session_start();
  
  if (isset($_SESSION['user'])){
  	session_destroy();
    header('Location: index.php');
  }

  $conn = new mysqli('localhost', 'root', '', 'my_trackergps');
  
  if (isset($_REQUEST['user']) && isset($_REQUEST['password']) && $_REQUEST['user']!='' && $_REQUEST['password']!=''){
	$query = 'SELECT COUNT(*) AS numero FROM Utenti WHERE Username="'.$_REQUEST['user'].'" AND Password = MD5("'.$_REQUEST['password'].'")'; //Controllo che le credenziali inserite dall'utente esistano
  	$numero = $conn->query($query)->fetch_assoc()['numero'];
	
    if ($numero==0){ //Se numero è uguale a 0 vuol dire che non ci sono record con quelle credenziali
    	echo("Username o Password errate"); 
    }else{
    	session_start(); //Creo una sessione
        $_SESSION['user'] = $_REQUEST['user']; //Creo una variabile di sessione 'user' per memorizzare l'utente loggato
        header('location:index.php'); //Rimando alla pagina index.php
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="generator" content="AlterVista - Editor HTML"/>
  <link rel="icon" type="image/x-icon" href="images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="assets/css/login.css">
  <title>Tracker GPS - Login</title>
</head>
<body>
	<form class='login-form' action="login.php" method="POST">
      <div class="flex-row">
        <label class="lf--label" for="username">
          <svg x="0px" y="0px" width="12px" height="13px">
            <path fill="#B1B7C4" d="M8.9,7.2C9,6.9,9,6.7,9,6.5v-4C9,1.1,7.9,0,6.5,0h-1C4.1,0,3,1.1,3,2.5v4c0,0.2,0,0.4,0.1,0.7 C1.3,7.8,0,9.5,0,11.5V13h12v-1.5C12,9.5,10.7,7.8,8.9,7.2z M4,2.5C4,1.7,4.7,1,5.5,1h1C7.3,1,8,1.7,8,2.5v4c0,0.2,0,0.4-0.1,0.6 l0.1,0L7.9,7.3C7.6,7.8,7.1,8.2,6.5,8.2h-1c-0.6,0-1.1-0.4-1.4-0.9L4.1,7.1l0.1,0C4,6.9,4,6.7,4,6.5V2.5z M11,12H1v-0.5 c0-1.6,1-2.9,2.4-3.4c0.5,0.7,1.2,1.1,2.1,1.1h1c0.8,0,1.6-0.4,2.1-1.1C10,8.5,11,9.9,11,11.5V12z"/>
          </svg>
        </label>
        <input id="username" name="user" class='lf--input' placeholder='Utente' type='text' required>
      </div>
      <div class="flex-row">
        <label class="lf--label" for="password">
          <svg x="0px" y="0px" width="15px" height="5px">
            <g>
              <path fill="#B1B7C4" d="M6,2L6,2c0-1.1-1-2-2.1-2H2.1C1,0,0,0.9,0,2.1v0.8C0,4.1,1,5,2.1,5h1.7C5,5,6,4.1,6,2.9V3h5v1h1V3h1v2h1V3h1 V2H6z M5.1,2.9c0,0.7-0.6,1.2-1.3,1.2H2.1c-0.7,0-1.3-0.6-1.3-1.2V2.1c0-0.7,0.6-1.2,1.3-1.2h1.7c0.7,0,1.3,0.6,1.3,1.2V2.9z"/>
            </g>
          </svg>
        </label>
        <input id="password"  name="password" class='lf--input' placeholder='Password' type='password' required>
      </div>
      <input class='lf--submit' type='submit' value='LOGIN' style="background-color: #35c3c1">
    </form>
</body>
</html>
<?php
	$conn->close();
?>
