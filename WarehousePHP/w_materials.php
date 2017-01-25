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
<title>[WareHouse]|Справочник материалов</title><link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>
<?php include('menu.php'); ?>
<?php require_once ("./connections/warehouseDB.php"); ?>
<?php
$query = "SELECT * FROM materials"
    or die(mysql_errno().mysql_error());
$select = mysqli_select_db($link, $db);
$select_notes = mysqli_query ($link, $query);
?>
<h1 style="text-align:center">Страница просмотра материалов</h1>
<table class="flatTable">
<tr  class="headingTr">
	<td>Код материала</td>
    <td>Наименование материала</td>
    <td>Image</td>
    <td></td>
    <td></td>
</tr>
<?php while($note = mysqli_fetch_array($select_notes)){?>
<tr>
	<td><?php echo $note["id"];?></td>
	<td><?php echo $note["title"]?></td>
    	<td><img style="border-radius:5%;width:100px" src="<?php echo $note["img_path"]?>?>"></td>
 <?php if (((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {?>
	<td><a href="w_edit_material.php?material=<?php echo $note["id"]?>"><img style="width:32px" src="src/edit.gif" /></a></td>
	<td><a href="w_del_material.php?material=<?php echo $note["id"]?>"><img style="width:32px" src="src/del.png" /></a></td>
 <?php } else echo "<td></td><td></td>"; ?>
</tr>
<?php }?>
</table>
<?php if (((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {?>
<hr />
<h3>Добавить новый материал: </h3>
<hr />
<div class="main">
<form id="newmaterial" name="newmaterial"action="w_materials.php" enctype="multipart/form-data" method="post">
	<div class="field"><label for="title">Material:</label><input type="text" name="title" id="title" maxlength="40"/></div>
	<input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
	<div class="field"><input class="file_upload" type="file" name="file_upload" /></div>
    <div class="field"><input type="submit" name="submit" id="submit" value="Add" /></div>
</form>
</div>
<?php } ?>
<?php
if (isset($_POST["MAX_FILE_SIZE"]))
{
	$tmp_file_name = $_FILES["file_upload"]["tmp_name"];
	$dest_file_name = $_SERVER['DOCUMENT_ROOT'] . "/MyTravelNotes/photo/" . $_FILES["file_upload"]["name"]; 
	while (!file_exists($dest_file_name))
		move_uploaded_file($tmp_file_name, $dest_file_name);
	echo "<script> javascript: window.location.href=window.location.href;</script>";
}
?>
<br />
</body>
</html>

<?php
//Подключение к серверу
require_once ("Connections/warehouseDB.php"); 
//Выбор БД
$select_db = mysqli_select_db ($link, $db);

//Получение данных из формы
$title = $_POST['title'];
$submit = $_POST['submit'];
$img_path = "/MyTravelNotes/photo/" . $_FILES["file_upload"]["name"];
if (($title)&&($submit=="Add"))
{
	$query = "INSERT INTO materials (title, img_path) VALUES ('$title','$img_path')";
	$result = mysqli_query ($link, $query);
    //echo "<script> javascript: document.location.href = 'w_materials.php'; </script>";
}
?>