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

$MM_restrictGoTo = "w_materials.php";
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
<html>
<head>
<meta charset="windows-1251">
<title>[WareHouse]|Edit material</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php include('menu.php'); ?>
<?php
//получение идентификатора
$material_id = $_GET['material'];

//Соединение с сервером
require_once ("connections/warehouseDB.php");

//Выбор БД
$select_db = mysqli_select_db ($link, $db);

//Запрос к БД на получение строки, содержащей заметку с выбранным id
$query = "SELECT * FROM materials WHERE id = $material_id";

//Реализация запроса к БД
$result = mysqli_query ($link, $query);

//Помещение выбранной строки в массив
$edit_material = mysqli_fetch_array ($result);
?>
<h1 style="text-align:center">Страница редактирования материала</h1>
<h3>Введите новые данные для материала.</h3>
	<div class="main">
<form id="editmaterial" name="editmaterial" method="post" action="">
	<div class="field"><label>Код материала:&nbsp;</label><input type="text" name="id" id="id" disabled value="<?php echo $edit_material["id"]?>"/></div>
	<div class="field"><label for="title">Название материала:</label>
	<input type="text" name="title" id="title" value = "<?php echo $edit_material['title'];?>" /></div>
	<div class="field"><input type="hidden" name = "material" id = "material" value="<?php echo $edit_material['id']?>" /></div>
    <div class="field">    <img style="border-radius:5%;width:100%" src="<?php echo $edit_material["img_path"]?>?>"></div>
	<div class="field"><input type="submit" name="submit" id="submit" value="Изменить" /></div>
    </div>
</form>
<?php
//Собственно обновление данных
//Получение обновленных значений из формы
$submit = $_POST['submit'];
if ($submit){
$title = $_POST['title'];

$update_query = "UPDATE materials SET title = '$title' WHERE id = $material_id";
//Реализация запроса на обновление
$update_result = mysqli_query ($link, $update_query);
    echo "<script> javascript: document.location.href = 'w_materials.php'; </script>";
}
?>
</body>
</html>