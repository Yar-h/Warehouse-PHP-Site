<?php require_once ("./connections/warehouseDB.php"); ?>
<!doctype html>
<html>
<head>
<meta charset="windows-1251">
<title>[WareHouse]|����������</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php include('menu.php'); ?>
<?php 
//���������� ���������� ����� ����������
$query = "SELECT COUNT(id) AS material_types FROM `materials`";
$select = mysqli_select_db($link, $db);
$material_types = mysqli_query ($link, $query) or die (mysql_errno().mysql_error());
$row_material_types = mysqli_fetch_assoc ($material_types);
$material_types_num = $row_material_types['material_types']; 
mysqli_free_result ($material_types);
//���������� ���������� �����������
$query = "SELECT COUNT(id) AS suppliers FROM `suppliers`";
$select = mysqli_select_db($link, $db);
$suppliers = mysqli_query ($link, $query) or die (mysql_errno().mysql_error());
$row_suppliers = mysqli_fetch_assoc ($suppliers);
$suppliers_num = $row_suppliers['suppliers']; 
mysqli_free_result ($suppliers);
//���������� ���������� ����������
$query = "SELECT COUNT(id) AS materials FROM `materials_main`";
$select = mysqli_select_db($link, $db);
$materials = mysqli_query ($link, $query) or die (mysql_errno().mysql_error());
$row_materials = mysqli_fetch_assoc ($materials);
$materials_num = $row_materials['materials']; 
mysqli_free_result ($materials);

$date_array = getdate();
//���������� ��������� ���� �������� ������
$begin_date = date ("Y-m-d", mktime(0,0,0, $date_array['mon'],1,$date_array['year']));
//���������� �������� ���� �������� ������
$end_date = date ("Y-m-d", mktime(0,0,0, $date_array['mon'] + 1,0,$date_array['year']));

//���������� �� �����
$query_lmmaterials = "SELECT COUNT(id) AS lmmaterials FROM `materials_main`
WHERE income_date>='$begin_date' AND income_date<='$end_date'";
$lmmaterials = mysqli_query ($link, $query_lmmaterials)or die (mysql_errno().mysql_error());
$row_lmmaterials = mysqli_fetch_assoc ($lmmaterials);
$lmmaterials_num = $row_lmmaterials['lmmaterials']; mysqli_free_result ($lmmaterials);

//��������� ����������� ��������
$query_lastmaterial = "SELECT materials_main.id, materials_main.id_material, materials.title FROM `materials_main`, `materials` WHERE materials_main.id_material=materials.id ORDER BY income_date DESC LIMIT 0,1";
$lastmaterial = mysqli_query ($link, $query_lastmaterial)
    or die(mysql_errno().mysql_error());
$row_lastmaterial = mysqli_fetch_assoc ($lastmaterial); 
mysqli_free_result ($lastmaterial);

//����� ������� ��������
$query_expensivematerial = "SELECT materials_main.id, materials_main.id_material, materials.title FROM `materials_main`, `materials` WHERE materials_main.id_material=materials.id ORDER BY price DESC LIMIT 0,1";
$expensivematerial = mysqli_query ($link, $query_expensivematerial)
    or die(mysql_errno().mysql_error());
$row_expensivematerial = mysqli_fetch_assoc ($expensivematerial); 
mysqli_free_result ($expensivematerial);

//���������� ���������� �������
$query = "SELECT COUNT(DISTINCT id_warehouse) AS warehouses FROM `materials_main`";
$select = mysqli_select_db($link, $db);
$warehouses = mysqli_query ($link, $query) or die (mysql_errno().mysql_error());
$row_warehouses = mysqli_fetch_assoc ($warehouses);
$warehouses_num = $row_warehouses['warehouses']; 
mysqli_free_result ($warehouses);
?>
<h1 style="text-align:center">����������</h1>
<table class="flatTable">
	<tr class="headingTr">
		<td style="width:400px;text-align:left">��������������</td>
        <td>��������</td>
    </tr>
    <tr>
		<td style="text-align:left">���������� ����� ����������</td>
        <td><?php echo $material_types_num ?></td>
    </tr>
	<tr style="text-align:left">
    	<td style="text-align:left">���������� ����������</td> <td><?php echo $suppliers_num ?></td>
    </tr>
	<tr>
    	<td style="text-align:left">���������� ����������</td> <td><?php echo $materials_num ?></td>
    </tr>
	<tr>
    	<td style="text-align:left">�� ��������� ����� ��������� ����������</td> <td><?php echo $lmmaterials_num ?></td>
    </tr>
	<tr>
    	<td style="text-align:left">��������� ����������� ��������</td>
		<td><a href="./w_edit_main_table.php?note_id=<?php echo $row_lastmaterial['id'];?>"><?php echo $row_lastmaterial['id'];?></a> 
        (<a href="./w_materials.php"><?php echo $row_lastmaterial['title'];?></a>)</td>
    </tr>
	<tr>
    	<td style="text-align:left">����� ������� ��������</td>
		<td><a href="./w_edit_main_table.php?note_id=<?php echo $row_expensivematerial['id'];?>"><?php echo $row_expensivematerial['id'];?></a> 
        (<a href="./w_materials.php>"><?php echo $row_expensivematerial['title'];?></a>)</td>
    </tr>
	<tr>
    	<td style="text-align:left">���������� �������</td> <td><?php echo $warehouses_num;?></td>
    </tr>
</table>

</body>
</html>
