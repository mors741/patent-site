<html>
    <head>
        <title>Изобретения</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
	</head>
    <body>
		<link rel="stylesheet" type="text/css" href="CSS/fon.css" />
		<link rel="stylesheet" type="text/css" href="CSS/menu.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/button.css" />
		<link rel="stylesheet" type="text/css" href="CSS/message.css"/>
		
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
			if (isset($_COOKIE['login'])) {
				$c=$_COOKIE['login'];
				setcookie("login",'$elogin',time()+$_SESSION['timeout']);
			} else {
				if (isset($_SESSION['name'])) {
					echo '<div id="m_auth_err">Извините, время вашей сессии истекло</div>';
				}
				unset($_SESSION['name']);
				unset($_SESSION['admin']);
				unset($_SESSION['login']);
			}
			
			$link = mysqli_connect('localhost','root','','patent') or die("Ошибка при соединении с базой данных.." . mysqli_error($link)); 
			$query = "set character_set_results='utf8'" or die("Ошибка при изменении кодировки.." . mysqli_error($link)); 
			$link->query($query);
			
			if (isset($_SESSION['name'])) {
				if(isset($_SESSION['login'])){
					$login=$_SESSION['login'];
				}
				$query = "SELECT id, name, description, photo, date, 
						(SELECT CONCAT(fname,\" \", lname, \" \", patronymic) FROM users WHERE id = author_id) as author
						FROM inventions
						WHERE author_id =(SELECT id FROM users WHERE login = '$login')
						ORDER BY date DESC" 
						or die("Ошибка при выполнении запроса.." . mysqli_error($link));
				$result = $link->query($query);
				$inv_data = mysqli_fetch_array($result);	
				
				echo ('<div id="content">');
				echo "<center><h2>Ваши изобретения</h2></center>";
				do echo ('<center><h3>'.$inv_data['name'].'</h3>'
							.'<img src="'.$inv_data['photo'].'" width="300" height="300"/></center><br>'."\n"
							."&nbsp;&nbsp;&nbsp;".$inv_data['description']."<br>\n"
							."&nbsp;&nbsp;&nbsp;Автор: ".$inv_data['author']."<br>\n"
							."&nbsp;&nbsp;&nbsp;Зарегистрировано: ".$inv_data['date']."<br>\n"
							."&nbsp;&nbsp;&nbsp;<a href='http://www.finas.su/images/avtor/ouc131206author700kav.jpg'>Свидетельство</a><br><br>\n"
							.'<b><center><font size="6">* * *</font></center></b>'."\n");            
				while ($inv_data=mysqli_fetch_array($result));
				echo ('</div>');
			} else {
				echo '<div id="content"><h2>Для просмотра своих изобретений и получения копии авторского свидетельства вы должны быть авторизованы <h2></div>';
			}
			if (isset($_COOKIE['login'])) {
				$c=$_COOKIE['login'];
			} else {
				unset($_SESSION['name']);
				unset($_SESSION['admin']);
				unset($_SESSION['login']);
			}
			if (isset($_SESSION['name'])) {
				echo '<br><div id="vhod"><form method="post" action="index.php">
					<input type="submit" name="logout" value="Выйти"/>
					</form></div>';
			}
		?>
	</body>
</html>