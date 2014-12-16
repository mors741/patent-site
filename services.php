<html>
    <head>
        <title>Услуги патентного отдела</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
		<link rel="shortcut icon" href="Pictures/idea.ico">
		
        <script type="text/javascript" src="js/ajax.js"></script>
		<script type="text/javascript" src="js/counter.js"></script>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/dropdown.js"></script>
		
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
					echo '<div class="m_auth m_error">Извините, время Вашей сессии истекло</div>';
				}
				$_SESSION['last_activity'] = time(); // update last activity time stamp
			}
			
			$link = mysqli_connect('localhost','root','','patent') or die("Ошибка при соединении с базой данных.." . mysqli_error($link)); 
			$query = "set character_set_results='utf8'" or die("Ошибка при изменении кодировки.." . mysqli_error($link)); 
			$link->query($query);
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
			if (isset($_SESSION['admin'])==1){
				echo'<div id="right">
						<h2>Найти плагиат</h2>
						<form id="searchForm" name="searchForm" method="post" action="javascript:insertTask();">
							<div class="searchInput">
							<input name="searchq" type="text" id="searchq" size="30" class="inputs long" onkeyup="javascript:searchNameq()"/>
						</form>
					</div>
					
					<h2>Подсказки:</h2>
					<div id="msg"></div>
					<div id="search-result"></div>
					</div>
					<div id="left">
						<h2>Удалить изобретение</h2>
						<form method="get" action="services.php">
							<input type="text" class="inputs long" name="inv"><br>
							<br>
							<input type="checkbox" name="del_user" value="true">Вместе с пользователем
							<br><br>
							<input type="submit" class="danger button" value="Удалить"/>
						</form>
					</div>';
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
							$result = $link->query($query);
						} else {
							$query="UPDATE users SET inv_count=inv_count - 1 WHERE id = (SELECT author_id 
																							FROM inventions 
																							WHERE name = '$del_inv');" 
									or die("Ошибка при выполнении запроса.." . mysqli_error($link)); 
							$result = $link->query($query);
							
							$query = "DELETE FROM inventions WHERE name ='$del_inv'" or die("Ошибка при выполнении запроса.." . mysqli_error($link));
							$result = $link->query($query);
						}
						if ($link->affected_rows > 0){
							echo ('<div class="m_success m_delete">Изобретение успешно удалено</div>');
						} else {
							echo ('<div class="m_delete m_error">Изобретения с таким названием не существует.</div>');
						}
					}
			}
			else {
				echo'<div id="right">
						<h2>Найти похожие изобретения</h2>
						<form id="searchForm" name="searchForm" method="post" action="javascript:insertTask();">
						<div class="searchInput">
						<input name="searchq" type="text" id="searchq" class="inputs long" size="30" onkeyup="javascript:searchNameq()"/>
					</div>
				</form>
				<h2>Результаты:</h2>
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
			echo "<h1>Последние 10 зарегистрированных изобретений:</h1>";
			do {
				echo ('<div class="sector">
							<h3>'.$inv_data['name'].'</h3>'
							.'<center><img src="'.$inv_data['photo'].'" width="300" height="300"  class="f_image"/></center><br>'."\n"
							.'<p>'.$inv_data['description']."</p>\n"
							."<p>Автор: ".$inv_data['author']."</p>\n"
							."<p>Зарегистрировано: ".$inv_data['date']."</p>\n"
							//."<p><a href='http://www.finas.su/images/avtor/ouc131206author700kav.jpg'>Свидетельство</a></p>\n"
						);
				if (isset($_SESSION['admin'])==1){
					echo ('<a href="services.php?inv='.$inv_data['name'].'" class="button danger" style="text-align:center;">Удалить</a><br><br>');
				}
				echo ('</div>');
			} while ($inv_data=mysqli_fetch_array($result));
			echo ('</div>');
		?>
	</body>
</html>