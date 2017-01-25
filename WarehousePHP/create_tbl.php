<!doctype html>
<html>
<head>
<meta charset="windows-1251">
<title>Создание таблицы</title>
</head>

<body>
<?php
$link = mysqli_connect("localhost","admin","admin");
if ($link){
	echo "Connected.","<br />";
} else {
	echo "Can't connect.","<br />";
}


$db = "WarehouseDB";
$select = mysqli_select_db($link, $db);
if ($select){
	echo "Database $db has been choosed succesfully.","<br />";
} else {
	echo "Database $db hasn't been choosed","<br />";
}

$query = "CREATE TABLE materials (id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id), title VARCHAR (31))";
$create_tbl = mysqli_query($link, $query);
if ($create_tbl){
	echo "Table materials has been created succesfully.","<br />";
} else {
	echo "Table materials hasn't been created","<br />";
}

$query = "CREATE TABLE suppliers (id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id), name VARCHAR (31))";
$create_tbl = mysqli_query($link, $query);
if ($create_tbl){
	echo "Table suppliers has been created succesfully.","<br />";
} else {
	echo "Table suppliers hasn't been created","<br />";
}
?>
</body>
</html>