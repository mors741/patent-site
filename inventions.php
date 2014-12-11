<html>
    <head>
        <title>Изобретения</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
		<link rel="shortcut icon" href="Pictures/idea.ico">
		
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script language="javascript" src="js/dropdown.js"></script>
		<script language="javascript" src="js/counter.js"></script>
		
		<link rel="stylesheet" type="text/css" href="CSS/menu.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/button.css" />
		<link rel="stylesheet" type="text/css" href="CSS/message.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/dropdown.css"/>
	</head>
    <body>
		<div id="menu">
			<a href="index.php" class="logo" onclick="myFunction()" ><p id="counter"><?php echo $_COOKIE['count']; ?></p></a>
			<a href="index.php"><img src="Pictures/Logo.png"></a> <br>
			
			<a href="index.php" class="button" />Главная</a>
			<a href="services.php" class="button"/>Услуги</a>
			<a href="news.php" class="button"/>Новости</a>
			<a href="inventions.php" class="button"/>Изобретения</a>
			<a href="registration.php" class="button"/>Регистрация</a>
		</div>
		
		<?php
			session_start();

			if (isset($_SESSION['login'])){
				if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $_SESSION['timeout'])) {
					// last request was more than 2 minutes ago
					session_unset();     // unset $_SESSION variable for the run-time 
					session_destroy();   // destroy session data in storage
					echo '<div class="m_auth m_error">Извините, время вашей сессии истекло</div>';
				}
				$_SESSION['last_activity'] = time(); // update last activity time stamp
			}
			
			$link = mysqli_connect('localhost','root','','patent') or die("Ошибка при соединении с базой данных.." . mysqli_error($link)); 
			$query = "set character_set_results='utf8'" or die("Ошибка при изменении кодировки.." . mysqli_error($link)); 
			$link->query($query);
			
			if (isset($_SESSION['login'])) {
				$login=$_SESSION['login'];
				$query = "SELECT id, name, description, photo, date, 
						(SELECT CONCAT(fname,\" \", lname, \" \", patronymic) FROM users WHERE id = author_id) as author
						FROM inventions
						WHERE author_id =(SELECT id FROM users WHERE login = '$login')
						ORDER BY date DESC" 
						or die("Ошибка при выполнении запроса.." . mysqli_error($link));
				$result = $link->query($query);
				//$inv_data = mysqli_fetch_array($result);	
				
				echo ('<div id="content">');
				echo "<h1>Ваши изобретения</h1>";
				while ($inv_data=mysqli_fetch_array($result)){
					echo ('<div class="sector">
								<h3>'.$inv_data['name'].'</h3>'
								.'<center><img src="'.$inv_data['photo'].'" width="300" height="300" style="border: 1.5px solid #b0b0b0;"/></center><br>'."\n"
								.'<p>'.$inv_data['description']."</p>\n"
								."<p>Автор: ".$inv_data['author']."</p>\n"
								."<p>Зарегистрировано: ".$inv_data['date']."</p>\n"
								//."<p><a href='http://www.finas.su/images/avtor/ouc131206author700kav.jpg'>Свидетельство</a></p>\n"
							);
					echo ('</div>');
				};
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
			if (isset($_SESSION['login'])) {
				echo '	<div id="sign-out">
							<div class="dropdown">
								<a class="account button" style="font:12px/normal sans-serif;">'.$_SESSION["login"].'<img src="Pictures/arrow.png" style="margin-left: 7px;"/></a>
				
								<div class="submenu" style="display: none; ">
									<ul class="root">
										<li><a href="inventions.php">Мои изобретения</a></li>
										<li><a href="patent.php">Новое изобретение</a></li>
										<li>
											<form method="post" action="index.php">
												<input type="submit" name="logout" value="Выйти"/>
											</form>
										</li>
									</ul>
								</div>
							</div>				
						</div>';
			}
		?>
	</body>
</html>