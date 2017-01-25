<!doctype html>
<html>
<head>
<meta charset="windows-1251">
<title>Создание пользователя</title>
</head>
<body>
<?php
$link = mysqli_connect("localhost","root","");
$query = "GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost' IDENTIFIED BY 'admin' WITH GRANT OPTION";
$create_user = mysqli_query($link, $query);
if ($create_user){
	echo "User has been created.";
} else {
	echo "User hasn't been created.";	
}
?>
</body>
</html>