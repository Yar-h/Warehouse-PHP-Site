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
<li class='active'><?php if (isset($_SESSION['MM_Username'])) echo "<a>[<font color='".$color."'>".$_SESSION['MM_Username']."</font>]</a>"; else {?><a href="login.php">�����</a><?php } ?></li>
<li><a href="warehouse.php">�������</a></li>
<li><a href="w_new_material.php">����� ��������</a></li>
<li><a href="email.php">email</a></li>
<li><a href="w_main_table.php">���������</a></li>
<li><a href="w_materials.php">���������� ����������</a></li>
<li><a href="w_suppliers.php">���������� �����������</a></li>
<li><a href="w_inform.php">����������</a></li>
	<?php if ($_SESSION['MM_UserGroup']=="a"){ ?>
<li><a href="w_users.php">������������</a></li>
	<?php } ?>
<li><a href="logout.php">�����</a></li>
  </ul>
</div> 
<!--������ ������-->
  <form id="slick-find" name="slick-find" style="float: right" action="./w_find.php" method="post"> 
  <div class="container-1">
    <input style="height:35px" type="search" name="search" />
    <input style="height:35px" type="submit" name="submit" value="�����"/> 
  </div>
</form>
<br><br><br>
