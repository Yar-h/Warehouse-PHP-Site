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
?>
<html>
<head>
<meta charset="windows-1251">
<title>[WareHouse]|Справочник поставщиков</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php include('menu.php'); ?>
<?php require_once ("./connections/warehouseDB.php"); ?>
<?php
$query = "SELECT * FROM suppliers"
    or die(mysql_errno().mysql_error());
$select = mysqli_select_db($link, $db);
$select_notes = mysqli_query ($link, $query);
?>
<h1 style="text-align:center">Страница просмотра поставщиков</h1>
<table  class="flatTable">
<tr class="headingTr">
	<td>Код поставщика</td>
    <td>Наименование поставщика</td>
    <td></td>
    <td></td>
</tr>
<?php While ($note = mysqli_fetch_array($select_notes)){?>
<tr>
	<td><?php echo $note["id"];?></td>
	<td><?php echo $note["name"]?></td>
 <?php if (((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {?>
	<td><a href="w_edit_supplier.php?supplier=<?php echo $note["id"]?>"><img style="width:32px" src="src/edit.gif" /></a></td>
	<td><a href="w_del_suppl.php?supplier=<?php echo $note["id"]?>"><img style="width:32px" src="src/del.png" /></a></td>
 <?php } else echo "<td></td><td></td>"; ?>
</tr>
<?php } ?>
</table>
<?php if (((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {?>
<hr />
<h3>Добавить нового поставщика: </h3>
<hr />
<div class="main">
<form id="newsuppl" name="newsuppl" method="post">
	<div class="field" style="text-align:center"><label for="name">Фамилия Имя Отчество:</label><input type="text" name="name" id="name" maxlength="40"/>
	<div class="field"><input type="submit" name="submit" id="submit" value="Add" /></div>
</form>
</div>
<?php }?>
</body>
</html>

<?php
//Подключение к серверу
require_once ("Connections/warehouseDB.php"); 
//Выбор БД
$select_db = mysqli_select_db ($link, $db);

//Получение данных из формы
$name = $_POST['name'];
$submit = $_POST['submit'];
if (($name)&&($submit=="Add"))
{
	$query = "INSERT INTO suppliers (name) VALUES ('$name')";
$result = mysqli_query ($link, $query);
    echo "<script> javascript: document.location.href = 'w_suppliers.php'; </script>";
}
?>
