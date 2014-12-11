<html>
    <head>
        <title>Регистрация нового пользователя</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8">
		<link rel="shortcut icon" href="Pictures/idea.ico">
		
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/dropdown.js"></script>
		<script type="text/javascript" src="js/counter.js"></script>
		<script type="text/javascript" src="js/registration.js"></script>
		
		<!-- Сделать локальными -->
		<script src="//code.jquery.com/jquery-1.9.1.js"></script>
		<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
		<!-- -->
		
		<link rel="stylesheet" type="text/css" href="CSS/menu.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/button.css" />
		<link rel="stylesheet" type="text/css" href="CSS/message.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/dropdown.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/registration.css"/>
		
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
		<!--
		
		<div id="reg">
			<form method="post" action="registration.php" id="test">
				Поля, помеченные звёздочкой <font color="red">*</font>, обязательны для заполнения<br>
				<table>
					<tr>
						<td><b>Логин</b> <font color="red">*</font>:</td>
						<td><input type="text" name="login" required /></td>
					</tr>
					<tr>
						<td><b>Пароль</b> <font color="red">*</font>:</td>
						<td><input type="password" class="password" name="password" id="password" required /></td>
					</tr>
					<tr>
						<td><b>Повторите пароль</b> <font color="red">*</font>:</td>
						<td><input type="password" name="rpassword" id="pass2" required /></td>
					</tr>
					<tr>
						<td><b>Фамилия</b> <font color="red">*</font>:</td>
						<td><input type="text" name="fname" required /></td>
					</tr>
					<tr>
						<td><b>Имя</b> <font color="red">*</font>:</td>
						<td><input type="text" name="name" required /></td>
					</tr>
					<tr>
						<td><b>Отчество</b>:</td>
						<td><input type="text" name="lname" /></td>
					</tr>
					<tr>
						<td><b>eMail</b> <font color="red">*</font>:</td>
						<td><input type="text" name="mail" required/></td>
						<td>(К сожалению, домены на кириллице пока не поддерживаются)</td>
					</tr>
				</table>
				<input type="submit" name="register" value="Зарегистрироваться" />
			</form> 
			-->

		<?php
			session_start();
			$link = mysqli_connect('localhost','root','','patent') or die("Ошибка при соединении с базой данных.." . mysqli_error($link));

			if (isset($_SESSION['login'])){
				if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $_SESSION['timeout'])) {
					// last request was more than 2 minutes ago
					session_unset();     // unset $_SESSION variable for the run-time 
					session_destroy();   // destroy session data in storage
					echo '<div class="m_auth error">Извините, время вашей сессии истекло</div>';
				}
				$_SESSION['last_activity'] = time(); // update last activity time stamp
			}

			if(isset($_POST['register'])){
				$login=$_POST['login'];
				$password=md5($_POST['password']);
				$fname=$_POST['fname'];
				$lname=$_POST['lname'];
				$pname=$_POST['pname'];
				$email=$_POST['email'];
				
				$query = "SELECT id FROM users WHERE login='$login'" or die("Ошибка при выполнении запроса.." . mysqli_error($link));
				$result = $link->query($query);
				$myrow = mysqli_fetch_array($result);
				$result->close();
				if (!empty($myrow['id'])) {
					echo ('<div class="m_auth m_error">Извините, введённый вами логин<br> уже зарегистрирован.<br>Введите другой логин</div>');
				} else {
					$query = "set names 'cp1251'" or die("Ошибка при выполнении запроса.." . mysqli_error($link)); 
					$link->query($query);
					$query = "INSERT INTO users VALUES ('','$login','$password', '$fname','$pname','$lname','$email',0)" or die("Ошибка при выполнении запроса.." . mysqli_error($link));
					$result = $link->query($query);
					if ($result == 'TRUE'){
						echo '<div class="m_auth m_success">Вы успешно зарегистрированы!<br>Теперь вы можете зайти на сайт</div>';    
					}  
					else {
						echo '<div class="m_auth m_error">Ошибка при регистрации<br>нового пользователя</div>';  
					}
				}			
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
		<div id="reg">
			<form action="" method="post" id="register-form" novalidate="novalidate">
				<h1>Регистрация</h1>
			 
				<div id="form-content">
					<fieldset>
						
						<div class="fieldgroup">
							<label for="login">Логин</label>
							<input type="text" name="login" id="login" class="medium inputs"/>
							<img id="tick" src="Pictures/tick.png" width="16" height="16"/>
							<img id="cross" src="Pictures/cross.png" width="16" height="16"/>
						</div>
						
						<div class="fieldgroup">
							<label for="password">Пароль</label>
							<input type="password" id="password" name="password" class="medium inputs"/>
						</div>
						
						<div class="fieldgroup">
							<label for="rpassword">Повторите пароль</label>
							<input type="password" id="rpassword" name="rpassword" class="medium inputs"/>
						</div>
						
						<div class="fieldgroup">
							<label for="lname">Фамилия</label>
							<input type="text" name="lname" class="medium inputs"/>
						</div>
						
						<div class="fieldgroup">
							<label for="ftname">Имя</label>
							<input type="text" name="fname" class="medium inputs"/>
						</div>
						
						<div class="fieldgroup">
							<label for="pname">Отчество</label>
							<input type="text" name="pname" class="medium inputs"/>
						</div>
			 
						<div class="fieldgroup">
							<label for="email">Email</label>
							<input type="text" name="email" class="medium inputs"/>
						</div>
			 
						<div class="fieldgroup" >
							<input type="submit" class="button subit" value="Зарегистрироваться" name="register" style="margin-right: 100;"/>
						</div>
			 
					</fieldset>
				</div>
			 
				<div class="fieldgroup">
					<p>Уже зарегистрированы? <a href="index.php">Войти</a>.</p>
				</div>
			</form>
		
		</div> 
	</body>
</html>