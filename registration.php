<html>
    <head>
        <title>Регистрация нового пользователя</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	</head>
    <body>

		<link rel="stylesheet" type="text/css" href="CSS/fon.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/menu.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/button.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/message.css"/>

		<div id="header">
			<a href="main.php"><center><img src="Pictures/Top.jpg"/></center></a>
		</div> 
		
		<div id="reg">
			<form method="post" action="registration.php" id="test">
				Поля, помеченные звёздочкой, обязательны для заполнения<br>
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
						<td><input type="text" name="mail" required /></td>
					</tr>
				</table>
				<input type="submit" name="register" value="Зарегистрироваться" />
			</form> 
		
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
			//mysql_connect('localhost','root','') or die (mysql_error());
			//mysql_select_db('site');
			$link = mysqli_connect('localhost','root','','patent') or die("Ошибка при соединении с базой данных.." . mysqli_error($link));
			if (isset($_COOKIE['a'])) {
				$c=$_COOKIE['a'];
				setcookie("a",'$elogin',time()+$_SESSION['timeout']);
			} else {
				if (isset($_SESSION['name'])) {
					echo '<div id="m_auth_err">Извините, время вашей сессии истекло</div>';
				}
				unset($_SESSION['name']);
				unset($_SESSION['admin']);
				unset($_SESSION['login']);
			}

			if(isset($_POST['register'])){
				$login=$_POST['login'];
				$password=$_POST['password'];
				$rpassword=$_POST['rpassword'];
				$fname=$_POST['fname'];
				$lname=$_POST['lname'];
				$name=$_POST['name'];
				$mail=$_POST['mail'];
				if($password == $rpassword){
					$password=md5($password);
					//$result = mysql_query("SELECT id FROM logins WHERE login='$login'");
					$query = "SELECT id FROM users WHERE login='$login'" or die("Ошибка при выполнении запроса.." . mysqli_error($link));
					$result = $link->query($query);
					$myrow = mysqli_fetch_array($result);
					//$myrow = mysql_fetch_array($result);
					if (!empty($myrow['id'])) {
						echo ('<div id="m_error">Извините, введённый вами логин уже зарегистрирован. Введите другой логин</div>');
					} else {
						$query = "set names 'cp1251'" or die("Ошибка при выполнении запроса.." . mysqli_error($link)); 
						$link->query($query);
						$query = "INSERT INTO users VALUES ('','$login','$password', '$fname','$name','$lname','$mail')" or die("Ошибка при выполнении запроса.." . mysqli_error($link));
						$result = $link->query($query);
						if ($result='TRUE'){
							echo '<div id="m_success">Вы успешно зарегистрированы! Теперь вы можете зайти на сайт</div>';    
						}  
					}			
				} 
				else{
					echo '<div id="m_error">Пароли не совпадают</div>';
					echo "\n";
				}  
			}
			
			if (isset($_SESSION['name'])) {
				echo '<br><div id="vhod"><form method="post" action="index.php">
					<input type="submit" name="logout" value="Выйти"/>
					</form></div>';
			}
		?>
	</body>
</html>