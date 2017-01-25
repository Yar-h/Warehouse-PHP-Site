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
<?php require_once ("./connections/warehouseDB.php");
$material_id = $_GET['material'];
$query = "SELECT * FROM materials WHERE id='$material_id';";
$select = mysqli_select_db($link, $db);
$result = mysqli_query ($link, $query);
$delete_material = mysqli_fetch_array ($result);
?>
<html>
<head>
<meta charset="windows-1251">
<title>[WareHouse]|Delete material</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php include('menu.php'); ?>
<h1 style="text-align:center">Страница удаления материала</h1>
<div class="main">
<form id="deletemater" name="deletemater" method="post">
<div class="field"><label>Код материала:&nbsp;</label><input type="text" name="id" id="id" disabled value="<?php echo $delete_material["id"]?>"/></div>
	<div class="field">Название материала: <input type="text" disabled value="<?php echo $delete_material['title'];?>"/></div>
    <div class="field"><input type="hidden" name = "material_id" id = "material_id" value="<?php echo $delete_material['id']?>" /></div><br />
    <div class="field">    <img style="border-radius:5%;width:100%" src="<?php echo $delete_material["img_path"]?>?>"></div>
    <div class="field"><input type="submit" name="submit" id="submit" value="Удалить" /></div>
</form>
</div>
</body>
</html>

<?php
$submit = $_POST['submit'];
if ($submit){
    $delete_query = "DELETE FROM materials WHERE id = '$material_id'";
    $delete_result = mysqli_query ($link, $delete_query)
        or die(mysql_errno().mysql_error());
    echo "<script> javascript: document.location.href = 'w_materials.php'; </script>";
	$file_name = $_SERVER['DOCUMENT_ROOT'] . "/MyTravelNotes/photo/" . $_POST["file_delete"];
	unlink($file_name);
}
?>
