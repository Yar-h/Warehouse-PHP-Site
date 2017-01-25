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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "edituser")) {
  $updateSQL = sprintf("UPDATE users SET login=%s, password=%s, rights=%s WHERE id=%s",
                       GetSQLValueString($_POST['login'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['rights'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_warehousedb, $warehousedb);
  $Result1 = mysql_query($updateSQL, $warehousedb) or die(mysql_error());

  $updateGoTo = "w_users.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
?>
<html>
<head>
<meta charset="windows-1251">
<title>[WareHouse]|Редактировать пользователя</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php require_once ("./connections/warehouseDB.php");
$user_id = $_GET['id'];
$query = "SELECT * FROM users WHERE id='$user_id';";
$select = mysqli_select_db($link, $db);
$result = mysqli_query ($link, $query);
$edit_user = mysqli_fetch_array ($result);
?>

<?php include('menu.php'); ?>
<h1 style="text-align:center">Страница редактирования пользователя</h1>
<div class="main">
<form action="<?php echo $editFormAction; ?>" id="edituser" name="edituser" method="POST">
    <div class="field"><label>Id<label> <input name="user_id" type="text" id="user_id" value="<?php echo $edit_user['id'];?>" disabled/></div>
	<div class="field"><label>Login:<label> <input name="login" type="text" id="login" value="<?php echo $edit_user['login'];?>"/></div>
    <div class="field"><label>Password:<label> <input name="password" type="password" id="password" value="<?php echo $edit_user['password'];?>"/></div>
  	<div class="field"><label>Rights:<label> <select name="rights" id="rights" maxlength="40">
			<option <?php if ($edit_user['rights']=='u') echo "selected ";?>value="u">User</option>
			<option <?php if ($edit_user['rights']=='a') echo "selected ";?>value="a">Administrator</option>
        </select></div>
		<input hidden="true" name="id" type="text" id="id" value="<?php echo $edit_user['id'];?>" />
	<div class="field"><input type="submit" name="submit" id="submit" value="Сохранить" /></div>
	<input type="hidden" name="MM_update" value="edituser">
</form>
</div>
</body>
</html>