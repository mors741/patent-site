<html>
    <head>
        <title>Услуги патентного отдела</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
        <script language="javascript" src="Other/ajax.js"></script>  
	</head>
    <body>
		<link rel="stylesheet" type="text/css" href="CSS/fon.css" />
		<link rel="stylesheet" type="text/css" href="CSS/menu.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/button.css" />

		<div id="header">
			<center><img src="Pictures/Top.jpg"/></center>
		</div>

		<div id="menu">
			<a href="index.php" class="button" />Главная</a>
			<a href="services.php" class="button"/>Услуги</a>
			<a href="news.php" class="button"/>Новости</a>
			<a href="inventions.php" class="button"/>Изобретения</a>
			<a href="registration.php" class="button"/>Регистрация</a>
		</div>

       	<?php
			session_start();
			if (isset($_COOKIE['a'])) {
				$c=$_COOKIE['a'];
			} else {
				unset($_SESSION['name']);
				unset($_SESSION['admin']);
				unset($_SESSION['login']);
			}
			$link = mysqli_connect('localhost','root','','patent') or die("Ошибка при соединении с базой данных.." . mysqli_error($link)); 
			$query = "set character_set_results='utf8'" or die("Ошибка при изменении кодировки.." . mysqli_error($link)); 
			$link->query($query);
			if (isset($_SESSION['name'])) {
				echo '<br><div id="vhod"><form method="post" action="index.php">
						<input type="submit" name="logout" value="Выйти"/>
						</form></div>';
			}
			if (isset($_SESSION['admin'])==1){
				echo'<div id="right">
						<h2>Найти плагиат</h2>
						<form id="searchForm" name="searchForm" method="post" action="javascript:insertTask();">
							<div class="searchInput">
							<input name="searchq" type="text" id="searchq" size="30" onkeyup="javascript:searchNameq()"/>
						</form>
					</div>
					
					<h3>Подсказки</h3>
					<div id="msg"></div>
					<div id="search-result"></div>
					</div>
					<div id="left">
						<h2>Удалить изобретение</h2>
						<form method="get" action="services.php">
							<input type="text" name="inv"><br>
							<br>
							<input type="checkbox" name="del_user" value="true">Вместе с пользователем
							<br>
							<input type="submit" value="Удалить"/>
						</form>';
					if (isset($_GET['inv'])){
						$del_inv = $_GET['inv'];
						$query = "set names 'utf8'" or die("Ошибка при выполнении запроса.." . mysqli_error($link)); 
						$link->query($query);
						if (isset($_GET['del_user'])){
							$query = "DELETE FROM users 
										WHERE id = (SELECT author_id 
													FROM inventions 
													WHERE name = '$del_inv')" 
										or die("Ошибка при выполнении запроса.." . mysqli_error($link));
						} else {
							$query = "DELETE FROM inventions WHERE name ='$del_inv'" or die("Ошибка при выполнении запроса.." . mysqli_error($link));
						}
						$result = $link->query($query);
						if ($link->affected_rows > 0){
							echo '<font color="green">Изобретение удалено</font>';    
						} else {
							echo '<font color="red">Изобретения с таким названием <br> не существует.</font>'; 
						}
					}
				echo "</div>";
			}
			else {
				echo'<div id="right">
						<h2>Найти похожие изобретения</h2>
						<form id="searchForm" name="searchForm" method="post" action="javascript:insertTask();">
						<div class="searchInput">
						<input name="searchq" type="text" id="searchq" size="30" onkeyup="javascript:searchNameq()"/>
					</div>
				</form>
				<h3>Результаты</h3>
				<div id="msg"></div>
				<div id="search-result"></div>
				</div>';        
			}
			 
			$query = "SELECT name, description, photo, date, 
						(SELECT CONCAT(fname,\" \", lname, \" \", patronymic) FROM users WHERE id = author_id) as author
						FROM inventions
						ORDER BY date DESC
						LIMIT 10" 
						or die("Ошибка при выполнении запроса.." . mysqli_error($link)); 
			$result = $link->query($query);
			$inv_data = mysqli_fetch_array($result);			
			echo ('<div id="content">');
			echo "<center><h2>Регистрация изобретения</h2></center>";
			echo "<center><p>Пройти процесс регистрации изобретения и получения авторского свидетельства</p></center>
					<center><p>(Доступно только авторизированным пользователям)</p></center><br>";
			if (isset($_SESSION['name'])) {
				echo '<center> <a href="patent.php" class="button">Начать</a></p> </center>
					<br /><br /><b><center><font size="6">* * *</font></center></b>';
			} else {
				echo '<center> <a href="" class="button">Начать</a></p> </center>
					<br><br><b><center><font size="6">* * *</font></center></b>';
			}
			
			echo "<center><h2>Последние 10 зарегистрированных изобретений:</h2></center>";
			do echo ('<center><h3>'.$inv_data['name'].'</h3>'
						.'<img src="'.$inv_data['photo'].'" width="300" height="300"/></center><br>'."\n"
						."&nbsp;&nbsp;&nbsp;".$inv_data['description']."<br>\n"
						."&nbsp;&nbsp;&nbsp;Автор: ".$inv_data['author']."<br>\n"
						."&nbsp;&nbsp;&nbsp;Зарегистрировано: ".$inv_data['date']."<br><br>\n"
						.'<b><center><font size="6">* * *</font></center></b>'."\n");            
			while ($inv_data=mysqli_fetch_array($result));
			echo ('</div>');
		?>
	</body>
</html>