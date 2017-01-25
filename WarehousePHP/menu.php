<?php if (!isset($_SESSION)) 
  session_start();
	if ($_SESSION['MM_UserGroup']=="a") $color="#F30' style='font-weight:bolder'"; else $color="#FFCC00";
?><head>
   <script src="jquery-latest.min.js" type="text/javascript"></script>
   <script src="script.js"></script>
   <link rel="stylesheet" type="text/css" href="style.css">
</head>

<div id='cssmenu'>
  <ul>
<li class='active'><?php if (isset($_SESSION['MM_Username'])) echo "<a>[<font color='".$color."'>".$_SESSION['MM_Username']."</font>]</a>"; else {?><a href="login.php">Войти</a><?php } ?></li>
<li><a href="warehouse.php">Главная</a></li>
<li><a href="w_new_material.php">Новый материал</a></li>
<li><a href="email.php">email</a></li>
<li><a href="w_main_table.php">Материалы</a></li>
<li><a href="w_materials.php">Справочник материалов</a></li>
<li><a href="w_suppliers.php">Справочник поставщиков</a></li>
<li><a href="w_inform.php">Статистика</a></li>
	<?php if ($_SESSION['MM_UserGroup']=="a"){ ?>
<li><a href="w_users.php">Пользователи</a></li>
	<?php } ?>
<li><a href="logout.php">Выйти</a></li>
  </ul>
</div> 
<!--Строка поиска-->
  <form id="slick-find" name="slick-find" style="float: right" action="./w_find.php" method="post"> 
  <div class="container-1">
    <input style="height:35px" type="search" name="search" />
    <input style="height:35px" type="submit" name="submit" value="Поиск"/> 
  </div>
</form>
<br><br><br>
