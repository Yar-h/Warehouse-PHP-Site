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
<html><head>
<meta charset="windows-1251">
<title>[WareHouse]|Редактировать материал</title>
<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
<?php include('menu.php'); ?>
<h1 style="text-align:center">Страница редактирования записи</h1>
<?php
//получение идентификатора
$note_id = $_GET['note_id'];

//Соединение с сервером
require_once ("connections/warehouseDB.php");

//Выбор БД
$select_db = mysqli_select_db ($link, $db);

//Запрос к БД на получение строки, содержащей заметку с выбранным id
$query = "SELECT * FROM materials_main WHERE id = $note_id";

//Реализация запроса к БД
$result = mysqli_query ($link, $query);

//Помещение выбранной строки в массив
$edit_record = mysqli_fetch_array ($result);
?>
<div class="main">
<form id="editsupplier" name="editsupplier" method="post" action="">
<div class="field"><label for="id">Код записи:</label><input type="text" name="id" id="id"  disabled value="<?php echo $edit_record["id"]?>"/></div>
<div class="field"><label for="id_material">Код материала</label>
    <select name="id_material" id="id_material">
    <option disabled>Выберите материал</option>
    <?php  
	$query = "SELECT * FROM materials" or die(mysql_errno().mysql_error());
	$select = mysqli_select_db($link, $db);
	$select_notes = mysqli_query ($link, $query);
	while ($material=mysqli_fetch_array($select_notes)){?>
		<option 
        <?php if ($material["id"]==$edit_record["id_material"]) {echo " selected ";}?>
        value="<?php echo $material['id']?>"><?php echo $material['id']." [".$material[title]."]"?></option>
	<?php }
	?>
    </select><br /></div>
    <div class="field"><label for="id_supplier">Код поставщика</label><select name="id_supplier" id="id_supplier">
    <option disabled>Выберите материал</option>
    <?php  
	$query = "SELECT * FROM suppliers" or die(mysql_errno().mysql_error());
	$select = mysqli_select_db($link, $db);
	$select_notes = mysqli_query ($link, $query);
	while ($supplier=mysqli_fetch_array($select_notes)){?>
		<option 
        <?php if ($supplier["id"]==$edit_record["id_supplier"]) {echo selected;}?>
        value="<?php echo $supplier["id"]?>"><?php echo $supplier['id']." [".$supplier[name]."]"?></option>
	<?php }
	?>
    </select><br /></div>
    <div class="field"><label for="income_date">Дата поступления</label><input type="date" name="income_date" id="income_date" value="<?php echo $edit_record["income_date"]?>"/><br /></div>
    
    <div class="field"><label for="id_waybill">Номер ТТН</label><input type="text" name="id_waybill" id="id_waybill" maxlength="9" value="<?php echo $edit_record["id_waybill"]?>"/><br /></div>
    <div class="field"><label for="id_warehouse">Номер склада</label><input type="text" name="id_warehouse" id="id_warehouse" maxlength="4" value="<?php echo $edit_record["id_warehouse"]?>"/><br /></div>
    <div class="field"><label for="MR_employee">ФИО МОЛ</label><input type="text" name="MR_employee" id="MR_employee" maxlength="32" value="<?php echo $edit_record["MR_employee"]?>"/><br /></div>

    <div class="field"><label for="count"></label>Кол-во товара<input type="text" name="count" id="count" maxlength="9" value="<?php echo $edit_record["count"]?>"/><br /></div>
    <div class="field"><label for="metrics"></label>Единицы измерения<input type="text" name="metrics" id="metrics" maxlength="16" value="<?php echo $edit_record["metrics"]?>"/><br /></div>
    <div class="field"><label for="price"></label>Стоимость товара<input type="text" name="price" id="price" maxlength="9" value="<?php echo $edit_record["price"]?>"/><br /></div>
	<div class="field"><input type="submit" name="submit" id="submit" value="Изменить" /></div>
</div>
</form>

<?php
//Собственно обновление данных
//Получение обновленных значений из формы
$submit = $_POST['submit'];
if ($submit){
$id_material = $_POST['id_material'];
$id_supplier = $_POST['id_supplier'];
$income_date = $_POST['income_date'];

$id_waybill = $_POST['id_waybill'];
$id_warehouse = $_POST['id_warehouse'];
$MR_employee = $_POST['MR_employee'];

$count = $_POST['count'];
$metrics = $_POST['metrics'];
$price = $_POST['price'];

$update_query = "UPDATE materials_main SET  id_material = '$id_material',id_supplier = '$id_supplier',income_date = '$income_date',
											id_waybill = '$id_waybill',id_warehouse = '$id_warehouse',MR_employee = '$MR_employee',
											count = '$count',metrics = '$metrics',price = '$price' WHERE id = $note_id";
//Реализация запроса на обновление
$update_result = mysqli_query ($link, $update_query);
    echo "<script> javascript: document.location.href = 'w_main_table.php'; </script>";
}
?>
</div>
</body>
</html>