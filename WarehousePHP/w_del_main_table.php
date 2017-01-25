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

$MM_restrictGoTo = "w_main_table.php";
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
<?php require_once ("./connections/warehouseDB.php");
$note_id = $_GET['note_id'];
$query = "SELECT * FROM materials_main WHERE id='$note_id';";
$select = mysqli_select_db($link, $db);
$result = mysqli_query ($link, $query);
$delete_note = mysqli_fetch_array ($result);
?>
<html>
<head>
<meta charset="windows-1251">
<title>[WareHouse]|Delete material record</title>
<link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>
<?php include('menu.php'); ?>
<h1 style="text-align:center">Страница удаления записи</h1>
<div class="main">
<form id="deletenote" name="deletenote" method="post">
<div class="field">Код записи:<input type="text" name="id" id="id" disabled value="<?php echo $delete_note["id"]?>"/></div>

<div class="field">Код материала: <input type="text" disabled value="<?php echo $delete_note['id_material'];?>"/></div>
<div class="field">Код поставщика: <input type="text" disabled value="<?php echo $delete_note['id_supplier'];?>"/></div>
<div class="field">Дата поступления: <input type="text" disabled value="<?php echo $delete_note['income_date'];?>"/></div>

<div class="field">Номер ТТН: <input type="text" disabled value="<?php echo $delete_note['id_waybill'];?>"/></div>
<div class="field">Номер склада: <input type="text" disabled value="<?php echo $delete_note['id_warehouse'];?>"/></div>
<div class="field">Материально ответственное лицо: <input type="text" disabled value="<?php echo $delete_note['MR_employee'];?>"/></div>

<div class="field">Кол-во товара: <input type="text" disabled value="<?php echo $delete_note['count'];?>"/></div>
<div class="field">Единица измерения: <input type="text" disabled value="<?php echo $delete_note['metrics'];?>"/></div>
<div class="field">Стоимость товара: <input type="text" disabled value="<?php echo $delete_note['price'];?>"/></div>
    <input type="hidden" name = "note_id" id = "note_id" value="<?php echo $delete_note['id']?>" />
    <input form="deletenote" style="width:100%" type="submit" name="submit" id="submit" value="Удалить" />
</form>
</div>

</body>
</html>

<?php
$submit = $_POST['submit'];
if ($submit){
    $delete_query = "DELETE FROM materials_main WHERE id = '$note_id'";
    $delete_result = mysqli_query ($link, $delete_query)
        or die(mysql_errno().mysql_error());
    echo "<script> javascript: document.location.href = 'w_main_table.php'; </script>";
}
?>
