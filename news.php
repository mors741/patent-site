<html>
    <head>
		<title>Научные новости</title>
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
			?>
		<div id="content">
			<?php
				$handle = fopen("Other/rss.xml","w+");
				$res=file_get_contents("http://news.yandex.ru/science.rss"); 
				if ($res and preg_match("/<rss/i",$res))
				{
					fwrite($handle,$res);
					fclose($handle);
					$xmlfile = simplexml_load_file("Other/rss.xml");
					echo('<h2><a href="'.$xmlfile->channel->link.'">'.$xmlfile->channel->title.'</a></h2>');
					echo ($xmlfile->channel->description.'<br/><b>Последнее обновление новостей от '.date("d.m.Y").'</b>');
					$i = 0;
					foreach($xmlfile->channel->item as $data)
					{
						$i++;
						echo '<h3><a href="'.$data->link.'">'.$data->title.'</a></h3>';
						echo $time = date("d.m.Y H:i:s",strtotime($data->pubDate)) ;
						echo '<p>'.$data->description.'</p>';
						if ($i>4) break;
					}
				}
			?>
		</div>
			<?php
				if (isset($_SESSION['name'])) {
					echo '<div id="vhod"><form method="post" action="index.php">
					<input type="submit" name="logout" value="Выйти"/>
					</form></div>';
				}
			?>
	</body>
 </html>