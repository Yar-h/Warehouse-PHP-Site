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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="w_users.php";
  $loginUsername = $_POST['login'];
  $LoginRS__query = sprintf("SELECT login FROM users WHERE login=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_warehousedb, $warehousedb);
  $LoginRS=mysql_query($LoginRS__query, $warehousedb) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "newuser")) {
  $insertSQL = sprintf("INSERT INTO users (login, password, rights) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['login'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['rights'], "text"));

  mysql_select_db($database_warehousedb, $warehousedb);
  $Result1 = mysql_query($insertSQL, $warehousedb) or die(mysql_error());

  $insertGoTo = "w_users.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<html>
<head>
<meta charset="windows-1251">
<title>[WareHouse]|Пользователи</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php include('menu.php'); ?>
<?php require_once ("./connections/warehouseDB.php"); ?>
<?php
$query = "SELECT * FROM users"
    or die(mysql_errno().mysql_error());
$select = mysqli_select_db($link, $db);
$select_users = mysqli_query ($link, $query);
?>
<h1 style="text-align:center">Страница просмотра пользователей</h1>
<table  class="flatTable">
<tr class="headingTr">
	<td>Id пользователя</td>
    <td>Логин</td>
    <td>Права доступа</td>
    <td></td>
    <td></td>
</tr>
<?php While ($user = mysqli_fetch_array($select_users)){?>
<tr>
	<td><?php echo $user["id"];?></td>
	<td><?php echo $user["login"]?></td>
   	<td><?php if ($user["rights"]=='a') echo "Administrator"; else echo "User";?></td>
    <td><a href="w_edit_user.php?id=<?php echo $user["id"]?>"><img style="width:32px" src="src/edit.gif" /></a></td>
	<td><a href="w_del_user.php?id=<?php echo $user["id"]?>"><img style="width:32px" src="src/del.png" /></a></td>
</tr>
<?php } ?>
</table>
<hr />
<h3>Добавить нового пользователя: </h3>
<hr />
<div class="main">
<form action="<?php echo $editFormAction; ?>" id="newuser" name="newuser" method="POST">
	<div class="field"><label for="name">Login:</label><input type="text" name="login" id="login" maxlength="40"/>
   	<div class="field"><label for="password">Password:</label><input type="password" name="password" id="password" maxlength="40"/>
   	<div class="field"><label for="rights">Rights:</label><select name="rights" id="rights" maxlength="40">
    														<option value="u">User</option>
    														<option value="a">Administrator</option>
                                                           </select>
	<div class="field"><input type="submit" name="submit" id="submit" value="Add" /></div>
	<input type="hidden" name="MM_insert" value="newuser">
</form>
</div>
</body>
</html>