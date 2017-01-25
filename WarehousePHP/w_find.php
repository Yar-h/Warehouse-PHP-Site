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
<title>[WareHouse]|Поиск</title>
<meta charset="UTF8"/> 
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<?php include('menu.php'); ?>
<?php require_once ("./Connections/warehouseDB.php"); 
$user_search = $_POST['search']; 
$data_base=mysqli_select_db($link,$db);
echo "<h4>Результаты поиска: '$user_search'<h4>"; 
$where_list = array(); 
$query_usersearch = "SELECT * FROM `materials_main`"; 
$clean_search = str_replace(',', ' ', $user_search); 
$search_words = explode(' ', $user_search);  
 
$final_search_words = array(); 

if (count($search_words) > 0){ 
    foreach($search_words as $word){ 
        if (!empty($word)){ 
            $final_search_words[] = $word; 
        } 
    } 
} 

foreach ($final_search_words as $word){ 
    $where_list[] = " `income_date` LIKE '%$word%' OR `id_waybill` LIKE '%$word%' OR `MR_employee` LIKE '%$word%' OR`count` LIKE '%$word%' OR`metrics` LIKE '%$word%' OR`price` LIKE '%$word%' OR`id_material` LIKE '%$word%' OR`id_supplier` LIKE '%$word%' OR`id_warehouse` LIKE '%$word%'"; 
} 
$where_clause = implode (' OR ', $where_list); 
if (!empty($where_clause)){ 
    $query_usersearch .=" WHERE $where_clause"; 
} 
$res_query = mysqli_query($link, $query_usersearch) 
    or die(mysql_errno().mysql_error()); 
if (mysqli_num_rows($res_query) == 0){ 
    echo "Поиск не дал результатов<br>"; 
} 
else{ ?> 
<table class="flatTable">
<tr class="headingTr">
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
<?php
    while ($res_array = mysqli_fetch_array($res_query)){  ?>
        <tr>
<td><?php echo $res_array["id"];?></td>

<td><?php echo "<a href='w_materials.php'>$res_array[id_material]</a>";?></td>
<td><?php echo "<a href='w_suppliers.php'>$res_array[id_supplier]</a>";?></td>
<td><?php echo $res_array["income_date"];?></td>

<td><?php echo $res_array["id_waybill"];?></td>
<td><?php echo $res_array["id_warehouse"];?></td>
<td><?php echo $res_array["MR_employee"];?></td>

<td><?php echo $res_array["count"];?></td>
<td><?php echo $res_array["metrics"];?></td>
<td><?php echo $res_array["price"];?></td>
 <?php if (((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {?>
<td><a href="w_edit_main_table.php?note_id=<?php echo $res_array["id"]?>"><img style="width:32px" src="src/edit.gif" /></a></td>
<td><a href="w_del_main_table.php?note_id=<?php echo $res_array["id"]?>"><img style="width:32px" src="src/del.png" /></a></td>
<?php } else echo "<td></td><td></td>"; ?>
</tr>
<?php    } 
} 
?>
</table>
</html>