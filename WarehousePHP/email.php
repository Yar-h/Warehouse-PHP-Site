<html> 
<head> 
    <title>email</title> 
    <meta charset="UTF8"/> 
    <link rel="stylesheet" type="text/css" href="style.css">
<head> 
<body> 
<?php include('menu.php'); ?>
<h1 style="text-align:center">��������� ���������</h1>
<div class="main">
<form method="post"> 
    <div class="field"><label for="subject">���� ���������:</label><input type="text" id="subject" name="subject" /></p></div>
    <div class="field"><label for="text">����� ���������:</label><textarea style="min-height:150px" type="text" id="text" name="text"></textarea></p></div>

    <div class="field"><input type="submit" name="submit" value="���������"/></div>
</form> 
<?php 
$to = "admin@site1.com"; 
$subject = $_POST['subject']; 
$text = " Message: ".$_POST['text']; 
$submit = $_POST['submit']; 
if ($submit){ 
    if($subject == "" || $text == ""){ 
        echo "<div class='field'>��������� ��� ����</div><br>"; 
    } 
    else{  
        if (mail($to, $subject, $text)){ 
            echo "<div class='field'><font style='color:#3399FF'>��������� ����������</font></div><br>"; 
        } 
        else{ 
            echo "<div class='field'>error with mail()</div>"; 
       } 
    } 
} 
?> 
</div>
</body> 
</html>
