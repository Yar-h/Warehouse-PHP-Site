<?php require_once ("./connections/MySiteDB.php"); ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF8"/>
</head>
<body>
<?php
$selected_db = mysqli_select_db($link, $db);
$query = "SELECT * FROM notes  WHERE id=$_GET[note];"
    or die(mysql_errno().mysql_error());
$select_notes = mysqli_query ($link, $query);
$note = mysqli_fetch_array($select_notes);
echo $note['id'], "   ";
echo $note ['title'], "   ";
echo "<font size='2'>", $note['created'], "<br>", "</font>";
echo str_repeat("&nbsp", 8), $note['article'], "<br>"; 
?>
<a href="./editnote.php?note=<?php echo $note['id']; ?>">Редактировать</a>
<a href="./deletenote.php?note=<?php echo $note['id']; ?>">Удалить</a>
<hr size='4' color='black'>
<?php
$query = "SELECT * FROM comments WHERE art_id=$_GET[note];"
    or die(mysql_errno().mysql_error());
$select_comments = mysqli_query ($link, $query);
if (mysqli_num_rows($select_comments) == 0){
    echo "Эту запись еще никто не комментировал<br>";
}
else {
    while ($comment = mysqli_fetch_array($select_comments)) {
        echo $comment['author'], " ";
        echo "<font size='2'>", $comment['created'], 
             "<a href='./delcomment.php?delid=$comment[id]'> удалить</a>", 
             "</font><br>";
        echo str_repeat("&nbsp", 8), $comment['comment'], "<hr>"; 
    }
}
?>
<p>Добавить новый комментарий: </p>
<form id="newnote" name="newnote" method="post" action="">
    <p>Автор:<br><input type="text" name="author" id="author" maxlength="20"/>
    <p>Текст:<br><textarea name="comment" cols="80" rows="2" id="comment"></textarea>
    <input type="hidden" name = "date" id="date" value="<?php echo date('Y-m-d'); ?> "/><br>
    <input type="submit" name="submit" id="submit" value="Добавить комментарий" />
</form>
<a href="./blog.php">Возврат на главную страницу сайта</a>
<?php
$author = $_POST['author'];
$created = $_POST['date'];
$comment = $_POST['comment'];
$submit = $_POST['submit'];
if (($author)&&($created)&&($comment)&&($submit)){
    $query = "INSERT INTO comments (author, date, comment, art_id) VALUES ('$author', '$created', '$comment', '$_GET[note]')";
    $result = mysqli_query ($link, $query)
        or die(mysql_errno().mysql_error());
    echo "<script> javascript: window.location.href=window.location.href;</script>";
}
$submitdel = $_POST['submitdel'];
if ($submitdel){
    $delete_query = "DELETE FROM comments WHERE id = $comment_id";
    $delete_result = mysqli_query ($link, $delete_query)
        or die(mysql_errno().mysql_error());
   echo "<script> javascript: window.location.href=window.location.href;</script>";
}
?>
