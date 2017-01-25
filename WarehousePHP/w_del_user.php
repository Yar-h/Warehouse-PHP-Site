<?php require_once('Connections/warehousedb.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "a";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "warehouse.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

if ((isset($_POST['user_id'])) && ($_POST['user_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM users WHERE id=%s",
                       GetSQLValueString($_POST['user_id'], "int"));

  mysql_select_db($database_warehousedb, $warehousedb);
  $Result1 = mysql_query($deleteSQL, $warehousedb) or die(mysql_error());

  $deleteGoTo = "w_users.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
<html>
<head>
<meta charset="windows-1251">
<title>[WareHouse]|Удалить пользователя</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php require_once ("./connections/warehouseDB.php");
$user_id = $_GET['id'];
$query = "SELECT * FROM users WHERE id='$user_id';";
$select = mysqli_select_db($link, $db);
$result = mysqli_query ($link, $query);
$delete_user = mysqli_fetch_array ($result);
?>

<?php include('menu.php'); ?>
<h1 style="text-align:center">Страница удаления пользователя</h1>
<div class="main">
<form id="deleteuser" name="deleteuser" method="post">
	<div class="field"><label>Id:</label><input type="text" name="id" id="id" disabled value="<?php echo $delete_user["id"]?>"/></div>
	<div class="field"><label>Login:<label> <input type="text" value="<?php echo $delete_user['login'];?>" disabled/></div>
  	<div class="field"><label>Rights:<label> <input type="text" value="<?php if ($user["rights"]=='a') echo "Administrator"; else echo "User";?>" disabled/></div>
	<div class="field"><input type="hidden" name = "user_id" id = "user_id" value="<?php echo $delete_user['id']?>" /></div>
	<div class="field"><input type="submit" name="submit" id="submit" value="Удалить" /></div>
</form>
</div>
</body>
</html>