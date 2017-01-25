<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$localhost = "localhost";
$db = "warehousedb";
$user = "admin";
$password = "admin";

$link = mysqli_connect($localhost, $user, $password) or trigger_error(mysql_error(),E_USER_ERROR);

mysqli_query($link, "SET NAMES cp1251;") or die(mysql_error()); mysqli_query($link, "SET CHARACTER SET cp1251;") or die(mysql_error());

$hostname_warehousedb = "localhost";
$database_warehousedb = "warehousedb";
$username_warehousedb = "admin";
$password_warehousedb = "admin";
$warehousedb = mysql_pconnect($hostname_warehousedb, $username_warehousedb, $password_warehousedb) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES cp1251;" , $warehousedb) or die(mysql_error()); mysql_query("SET	CHARACTER	SET	cp1251;",	$warehousedb)	or die(mysql_error());
?>