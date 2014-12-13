<html>
    <head>
        <title>Регистрация нового пользователя</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
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
			if (isset($_SESSION['login'])) {
				echo '	<div id="reg">
							<form method="post" action="patent.php" id="test" enctype="multipart/form-data">
							Поля, помеченные звёздочкой <font color="red">*</font>, обязательны для заполнения<br>
							<table>
								<tr>
									<td><b>Название<font color="red">*</font>:</b> </td>
									<td><input type="text" name="name" class="inputs long" required /></td>
								</tr>
								<tr>
									<td><b>Фотография:</b></td>
									<td><input type="file" name="filename"></td>
								</tr>
								<tr>
									<td><b>Описание<font color="red">*</font>:</b></td>
									<td>
									<textarea name="description" rows="7" cols="50" class="inputs long" required ></textarea></td>
								</tr>
								<tr>
									<td></td>
									<td><input type="checkbox" name="sleep" value="true">Включить задержку (для демонстрации)<br><br></td>
								</tr>
								<tr>
									<td></td>
									<td><input type="submit" name="register_p" class="button" value="Зарегистрировать"/></td>
								</tr>
							</table>
						</form> 
					</div> ';

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
			}else{
				echo '<div id="content"><h3>Извините, регистрация нового изобретения доступна только авторизованным пользователям</h3></div>';
			}
		?>
		
		<?php
			$link = mysqli_connect('localhost','root','','patent') or die("Ошибка при соединении с базой данных.." . mysqli_error($link));

			if (isset($_SESSION['login'])){
				if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $_SESSION['timeout'])) {
					// last request was more than 2 minutes ago
					session_unset();     // unset $_SESSION variable for the run-time 
					session_destroy();   // destroy session data in storage
					echo '<div class="m_auth m_error">Извините, время вашей сессии истекло</div>';
				}
				$_SESSION['last_activity'] = time(); // update last activity time stamp
			}
			
			if(isset($_POST['register_p'])){		
				if($_FILES["filename"]["size"] > 1024*3*1024)
				{
					echo ("Размер файла превышает три мегабайта");
					exit;
				}
				// Проверяем загружен ли файл
				if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
				{
					// Если файл загружен успешно, перемещаем его
					// из временной директории в конечную
					move_uploaded_file($_FILES["filename"]["tmp_name"], "Uploads/".$_FILES["filename"]["name"]);
				} else {
				  //echo("Ошибка загрузки файла");
			   }
				$login = $_SESSION['login'];
				$name = $_POST['name'];
				$description=$_POST['description'];
				$photo = "Uploads/".$_FILES["filename"]["name"];
				
				if($photo == "Uploads/"){
					$photo = "Uploads/no_photo.jpg";
				}
				
				$query = "START TRANSACTION;" or die("Ошибка при выполнении запроса.." . mysqli_error($link));
				$link->query($query);				
				
				$query = "CALL CheckInventions('$name', '$description', @p_out);" or die("Ошибка при выполнении запроса.." . mysqli_error($link));
				$link->query($query);
				
				$query = "SELECT @p_out AS count;" or die("Ошибка при выполнении запроса.." . mysqli_error($link));
				$result = $link->query($query);
				
				$myrow = mysqli_fetch_array($result);
				$result->close();
				if ($myrow['count']>0) {
					echo ('<div class="m_new_inv m_error">Система обнаружила крайне схожее изобретение! Пожалуйста, исправьте название или описание изобретения.</div>');
				}else{
					$query = "set names 'cp1251'" or die("Ошибка при выполнении запроса.." . mysqli_error($link)); 
					$link->query($query);
					
					$query="INSERT INTO inventions (name, description, photo, author_id)
								VALUES ('$name', '$description', '$photo', (SELECT id FROM users WHERE login = '".$_SESSION['login']."'))" or die("Ошибка при выполнении запроса.." . mysqli_error($link)); 
					$ins_res = $link->query($query);
					
					if ($ins_res == 'TRUE'){
											
						$query="UPDATE users SET inv_count=inv_count + 1 WHERE login = '$login';" or die("Ошибка при выполнении запроса.." . mysqli_error($link)); 
						$link->query($query);
						echo ('<div class="m_new_inv m_success">Изобретение успешно зарегистрировано!</div>');
					} else {
						echo ('<div class="m_new_inv m_error">Система обнаружила крайне схожее изобретение! Пожалуйста, исправьте название или описание изобретения.</div>');
					}
				}
				if (isset($_POST['sleep'])){
					$query = "DO SLEEP(5);" or die("Ошибка при выполнении запроса.." . mysqli_error($link));
					$link->query($query);
				}
				
				$query = "COMMIT;" or die("Ошибка при выполнении запроса.." . mysqli_error($link));
				$link->query($query);
				

				
			}
			mysqli_close($link);
		?>
	</body>
</html>