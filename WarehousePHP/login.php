<?php require_once('Connections/warehousedb.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['login'])) {
  $loginUsername=$_POST['login'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "rights";
  $MM_redirectLoginSuccess = "warehouse.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_warehousedb, $warehousedb);
  	
  $LoginRS__query=sprintf("SELECT login, password, rights FROM users WHERE login=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $warehousedb) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'rights');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="windows-1251">
<title>Authorization</title>
	<link rel="stylesheet" type="text/css" href="loginstyle.css" />
	<script type="text/javascript" src="jquery-latest.min.js"></script>
	<script src="http://www.modernizr.com/downloads/modernizr-latest.js"></script>
	<script type="text/javascript" src="placeholder.js"></script>
</head>

<body>
<?php
if ($_SESSION['MM_Username']!=""){
	echo "<script> javascript: document.location.href = 'warehouse.php'; </script>";
}
?>
<form ACTION="<?php echo $loginFormAction; ?>" id="slick-login" name="login" method="POST">
  <p align="center">
    <label for="login">Login:</label>
    <input name="login" type="text" autofocus id="login" tabindex="1" maxlength="20" class="placeholder" placeholder="login">
  </p>
  <p align="center">
    <label for="password">Password:</label>
    <input name="password" type="password" id="password" tabindex="2" maxlength="20" class="placeholder" placeholder="password">
  </p>
  <p align="center">
    <input name="submit" type="submit" id="submit" tabindex="3" value="Войти">
  </p>
</form>
</body>
</html>