<html>
    <head>
        <title>Оформление авторских свидетельств</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8"/>	
		<link rel="shortcut icon" href="Pictures/idea.ico">
		
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script language="javascript" src="js/dropdown.js"></script>
		<script language="javascript" src="js/counter.js"></script>
		<link rel="stylesheet" type="text/css" href="CSS/dropdown.css"/>	
		<link rel="stylesheet" type="text/css" href="CSS/menu.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/button.css" />
		<link rel="stylesheet" type="text/css" href="CSS/message.css"/>
		
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
		
        <div id="right">
			<h3>Скажи НЕТ плагиату и пиратству!</h3>
			<img src='Pictures/plagiat3.png' id="image_1"  width="320" height="240" style="border: 1.5px solid #b0b0b0;" />		
		</div>
		
		<?php
			$link = mysqli_connect('localhost','root','','patent') or die("Error " . mysqli_error($link));
			session_start();
			
			$_SESSION['timeout']=10;  //Поменьше
			
			if (isset($_SESSION['login'])){
				if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $_SESSION['timeout'])) {
					// last request was more than 2 minutes ago
					session_unset();     // unset $_SESSION variable for the run-time 
					session_destroy();   // destroy session data in storage
					echo '<div class="m_auth error">Извините, время вашей сессии истекло</div>';
				}
				$_SESSION['last_activity'] = time(); // update last activity time stamp
			}
			
			if (isset($_COOKIE['count']) === FALSE){
				$t = strtotime('tomorrow');
				setcookie("count",'0',"$t");
			}
			
			if(isset($_POST['enter'])){
				$_POST['password']=md5($_POST['password']);
				$query = "SELECT password FROM users WHERE login='".$_POST['login']."';" or die("Ошибка при выполнении запроса.." . mysqli_error($link)); 
				$result = $link->query($query);
				$user_data = mysqli_fetch_array($result);
				$result->close();
				if($_POST['login']=="admin")
					$_SESSION['admin']=1;
				if($user_data['password']==$_POST['password']){
					$_SESSION['login']=$_POST['login'];
					setcookie("login",$_POST['login'],time()+86400); // 1 day
					echo ('<div class="m_auth success">Добро пожаловать, '.$_SESSION["login"].'!</div>');
				}
				else {
					echo '<div class="m_auth error">Неверный логин или пароль</div>';
				}
			}
			
			if(isset($_POST['logout'])){
				session_unset();     // unset $_SESSION variable for the run-time 
				session_destroy();   // destroy session data in storage
            }
			
			if (isset($_SESSION['login'])){				
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
				
			} else {
				echo '<div id="sign-up"><form method="post" action="index.php">
						<input type="text" class="inputs" name="login" placeholder="Логин"/><br />
						<input type="password" class="inputs" name="password" placeholder="Пароль"/><br />
						<input type="submit" class="button primary" name="enter" value="Вход"/>             
					</form></div>';
			}
		?>

		<div id="content"> <br>
			<h1>Добро пожаловать!</h1>
			<p>Уважаемый посетитель, Вы попали на сайт Патентного отдела.
				Тут вы сможете подать заявку на изобретение, оформить авторское свидетельство.
				А так же ознакомиться с уже зарегистрированными изобретениями
				и просто почитать новости в области науки.</p>
			<p>Перед началом работы, пожалуйста, пройдите авторизацию.</p>
			<table border="1">
				<tr>
					<td><a href="http://www.copyright.ru/ru/library/zakonoproekti/pravovoe_regulirovanie_in/prinyatie_zakona_o_borbe_/"><img src="Pictures/main1.jpg" width="300" height="300"/></a></td>
					<td><a href="http://www.vaap.ru/content/registratsiya-avtorskogo-prava"><img src="Pictures/main3.jpg" width="300" height="300"/></a></td>
				</tr>
				<tr>
					<td><a href="http://elvin-dill.livejournal.com/29186.html?thread=182274"><img src="Pictures/main4.jpg" width="300" height="300"/></a></td>
					<td><a href="https://ru.wikipedia.org/wiki/%D0%9D%D0%B0%D1%80%D1%83%D1%88%D0%B5%D0%BD%D0%B8%D0%B5_%D0%B0%D0%B2%D1%82%D0%BE%D1%80%D1%81%D0%BA%D0%BE%D0%B3%D0%BE_%D0%BF%D1%80%D0%B0%D0%B2%D0%B0"><img src="Pictures/main2.jpg" width="300" height="300"/></a></td>				
				</tr>
			</table>
		</div>
	</body>
</html>