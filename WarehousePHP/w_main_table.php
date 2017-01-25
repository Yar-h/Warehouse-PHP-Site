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
<title>[WareHouse]|Каталог</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php include('menu.php'); ?>
<?php require_once ("./connections/warehouseDB.php"); ?>
<?php
$query = "SELECT * FROM materials_main ORDER BY id ASC"
    or die(mysql_errno().mysql_error());
$select = mysqli_select_db($link, $db);
$select_notes = mysqli_query ($link, $query);
?>

<table class="flatTable">
<h1 style="text-align:center">Страница просмотра учета материалов</h1></td>
<tr  class="headingTr">
	<td>Код записи</td>
	<td>Код материала</td>
    <td>Код поставщика</td>
    <td>Дата поступления</td>
    <td>Номер товарно-транспортной накладной</td>
    <td>Номер склада</td>
	<td>ФИО материаольно ответсвенного лица</td>
	<td>Кол-во товара</td>
	<td>Единицы измерения</td>
	<td>Стоимость товара</td>    
    <td></td>
    <td></td>
</tr>
<?php while($note = mysqli_fetch_array($select_notes)){?>
<tr>
<td><?php echo $note["id"];?></td>

<td><?php echo "<a href='w_materials.php'>$note[id_material]</a>";?></td>
<td><?php echo "<a href='w_suppliers.php'>$note[id_supplier]</a>";?></td>
<td><?php echo $note["income_date"];?></td>

<td><?php echo $note["id_waybill"];?></td>
<td><?php echo $note["id_warehouse"];?></td>
<td><?php echo $note["MR_employee"];?></td>

<td><?php echo $note["count"];?></td>
<td><?php echo $note["metrics"];?></td>
<td><?php echo $note["price"];?></td>
 <?php if (((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {?>
	<td><a href="w_edit_main_table.php?note_id=<?php echo $note["id"]?>"><img style="width:32px" src="src/edit.gif" /></a></td>
	<td><a href="w_del_main_table.php?note_id=<?php echo $note["id"]?>"><img style="width:32px" src="src/del.png" /></a></td>
 <?php } else echo "<td></td><td></td>"; ?>
</tr>
<?php }?>
</table>


</body>
</html>
