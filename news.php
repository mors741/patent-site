<html>
    <head>
		<title>Научные новости</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
		<link rel="shortcut icon" href="Pictures/idea.ico">
		
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script language="javascript" src="js/counter.js"></script>
		<script language="javascript" src="js/dropdown.js"></script>
		
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
			?>
		<div id="content">
			<?php
				$handle = fopen("Other/rss.xml","w+");
				$res=@file_get_contents("http://news.yandex.ru/science.rss"); 
				if ($res and preg_match("/<rss/i",$res))
				{
					fwrite($handle,$res);
					fclose($handle);
					$xmlfile = simplexml_load_file("Other/rss.xml");
					echo('<h1>Свежие новости в области науки:</h1>');
					echo ('<br/><b>Последнее обновление новостей от '.date("d.m.Y").'</b>');
					$i = 0;
					foreach($xmlfile->channel->item as $data)
					{
						$i++;
						echo '<div class="sector">';
						echo 	'<h3><a href="'.$data->link.'">'.$data->title.'</a></h3>';
						echo '<p style="margin-right: 500; text-decoration: underline;">'.date("d.m.Y H:i:s",strtotime($data->pubDate)).'</p>' ;
						echo 	'<p>'.$data->description.'</p>';
						echo '</div>';
						if ($i>4) break;
					}
				} else {
					echo('<h3>Извините, просмотр новостей доступен только в онлайн режиме...</h3>');
				}
			?>
		</div>
			<?php
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