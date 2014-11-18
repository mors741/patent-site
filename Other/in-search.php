<meta http-equiv="Content-Type" content="text/html; charset=utf8"/>

<?php
$link = mysqli_connect('localhost','root','','patent') or die("Error " . mysqli_error($link)); 
$query = "set names 'utf8'" or die("Ошибка при выполнении запроса.." . mysqli_error($link)); 
$link->query($query);

$searchq = $_GET['name'];
if (!addslashes($searchq) == "") {
	$like = "'%".addslashes($searchq)."%'";

	$query = "SELECT name FROM inventions WHERE name LIKE $like" or die("Ошибка при выполнении запроса.." . mysqli_error($link)); 
		$result = $link->query($query);			
	while ($row = mysqli_fetch_array($result))
		echo $row['name'] . '<br><br>';
}
?>
