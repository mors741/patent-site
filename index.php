<html>
    <head>
        <title>Оформление авторских свидетельств</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
		<script type="text/javascript">
			var total_pics_num = 3; // колличество изображений
			var interval = 5000;    // задержка между изображениями
			var time_out = 1;       // задержка смены изображений
			var i = 0;
			var timeout;
			var opacity = 100;

			function fade_to_next() {
    			opacity--;
    			var k = i + 1;
    			var image_now = 'image_' + i;
    			if (i == total_pics_num) k = 1;
    			var image_next = 'image_' + k;
    			document.getElementById(image_now).style.opacity = opacity/100;
    			document.getElementById(image_now).style.filter = 'alpha(opacity='+ opacity +')';
    			document.getElementById(image_next).style.opacity = (100-opacity)/100;
    			document.getElementById(image_next).style.filter = 'alpha(opacity='+ (100-opacity) +')';
    			timeout = setTimeout("fade_to_next()",time_out);
    			if (opacity==1) {
      				opacity = 100;
      				clearTimeout(timeout);
    			}
			}

			setInterval (
    			function() {
      				i++;
      				if (i > total_pics_num) i=1;
      				fade_to_next();
    			}, interval
			);
		</script>		
	</head>
    <body>
        <div id="right">
			<h3><b><p class="sample">Скажи НЕТ плагиату и пиратству!</p></b></h3>
			<img src='Pictures/plagiat3.png' id="image_1"  width="350" height="250" style="position: absolute;" />
			<img src='Pictures/plagiat2.jpg' id="image_2" width="350" height="250" style="opacity: 0; filter: alpha(opacity=0); position: absolute;" />
			<img src='Pictures/plagiat1.jpg' id="image_3" width="350" height="250" style="opacity: 0; filter: alpha(opacity=0); position: absolute;" />			
		</div>
		
        <link rel="stylesheet" type="text/css" href="CSS/fon.css"/>
        <link rel="stylesheet" type="text/css" href="CSS/menu.css"/>
        <link rel="stylesheet" type="text/css" href="CSS/button.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/message.css"/>
		
		<div id="header">
			<center><img src="Pictures/Top.jpg"/></center>
		</div>
		
		<div id="right">

		</div>
		<?php
			//mysql_connect('localhost','root','') or die (mysql_error());
			//mysql_select_db('site');
			$link = mysqli_connect('localhost','root','','patent') or die("Error " . mysqli_error($link));
			session_start();
			$_SESSION['timeout']=10;
			if (isset($_COOKIE['a'])) {
				$_SESSION['name']=$_COOKIE['a'];
				setcookie("a",'$elogin',time()+$_SESSION['timeout']);
			} 
			else {
				if (isset($_SESSION['name'])) {
					echo '<div id="m_auth_err">Извините, время вашей сессии истекло</div>';
				}
				unset($_SESSION['name']);
				unset($_SESSION['admin']);
				unset($_SESSION['login']);
			}
			
			if(isset($_POST['logout'])){
				unset($_SESSION['name']);
				unset($_SESSION['admin']);
				unset($_SESSION['login']); 
				setcookie("a",'',time()-$_SESSION['timeout']);
            }
			
			if(isset($_POST['enter'])){
				$elogin=$_POST['elogin'];
				$epassword=md5($_POST['epassword']);
				//$query=mysql_query("SELECT * FROM users WHERE login='$elogin'");
				$query = "SELECT login, password FROM users WHERE login='$elogin'" or die("Ошибка при выполнении запроса.." . mysqli_error($link)); 
				$result = $link->query($query);
				$user_data = mysqli_fetch_array($result);
				//$user_data=mysql_fetch_array($query);
				$login=$user_data['login'];
				if($login=="admin")
				$_SESSION['admin']=1;
				if($user_data['password']==$epassword){
					$_SESSION['name']=$elogin; 
					$_SESSION['login']=$elogin;
					setcookie("a",'$elogin',time()+$_SESSION['timeout']);
				
					echo '<div id="m_auth_suc">Здравствуйте, ';
					echo "$elogin</div>";
				}
				else {
					echo '<div id="m_auth_err">Неверный логин или пароль</div>';
				}
 
			}
			if (isset($_SESSION['name'])) {
				echo '<br><div id="vhod"><form method="post" action="index.php">
                <input type="submit" name="logout" value="Выйти"/>
                </form></div>';
            }
            else{
				echo '<div id="vhod"><form method="post" action="index.php">
						<input type="text" name="elogin" placeholder="Логин"/><br />
						<input type="password" name="epassword" placeholder="Пароль"/><br />
						<input type="submit" name="enter" value="Вход"/>             
					</form></div>';
			}
		?>
		<div id="menu">
			<a href="index.php" class="button" />Главная</a>
			<a href="services.php" class="button"/>Услуги</a>
			<a href="news.php" class="button"/>Новости</a>
			<a href="inventions.php" class="button"/>Изобретения</a>
			<a href="registration.php" class="button"/>Регистрация</a>
		</div>

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