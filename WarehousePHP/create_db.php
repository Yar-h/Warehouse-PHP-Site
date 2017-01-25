<!doctype html>
<html>
<head>
<meta charset="windows-1251">
<title>Создание базы</title>
</head>

<body>
<?php
$link = mysqli_connect("localhost", "root","");
if ($link){
	echo "Connected.", "<br>";
} else {
	echo "No connection.", "<br />";
}

$db = "WarehouseDB";
$query = "CREATE DATABASE $db";
$create_db = mysqli_query($link, $query);

if ($create_db){
	echo "Database $db has been created.", "<br />";
} else {
	echo "Database $db hasn't been created.", "<br />";
}
?>
</body>
</html>