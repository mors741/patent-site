<html>
    <head>
        <title>����������� ������ ������������</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
		<link rel="shortcut icon" href="Pictures/idea.ico">
		
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script language="javascript" src="js/dropdown.js"></script>
		<script language="javascript" src="js/counter.js"></script>
		<script language="javascript" src="js/registration.js"></script>
		
		<!-- ������� ���������� -->
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<!-- -->
		
		<link rel="stylesheet" type="text/css" href="CSS/menu.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/button.css" />
		<link rel="stylesheet" type="text/css" href="CSS/message.css"/>
		<link rel="stylesheet" type="text/css" href="CSS/dropdown.css"/>
	</head>
    <body>
		<div id="menu">
			<a href="index.php" class="logo" onclick="myFunction()" ><p id="counter"><?php echo $_COOKIE['count']; ?></p></a>
			<a href="index.php"><img src="Pictures/Logo.png"></a> <br>
			
			<a href="index.php" class="button" />�������</a>
			<a href="services.php" class="button"/>������</a>
			<a href="news.php" class="button"/>�������</a>
			<a href="inventions.php" class="button"/>�����������</a>
			<a href="registration.php" class="button"/>�����������</a>
		</div>
		
		<div id="reg">
			<form method="post" action="registration.php" id="test">
				����, ���������� ��������� <font color="red">*</font>, ����������� ��� ����������<br>
				<table>
					<tr>
						<td><b>�����</b> <font color="red">*</font>:</td>
						<td><input type="text" name="login" required /></td>
					</tr>
					<tr>
						<td><b>������</b> <font color="red">*</font>:</td>
						<td><input type="password" class="password" name="password" id="password" required /></td>
					</tr>
					<tr>
						<td><b>��������� ������</b> <font color="red">*</font>:</td>
						<td><input type="password" name="rpassword" id="pass2" required /></td>
					</tr>
					<tr>
						<td><b>�������</b> <font color="red">*</font>:</td>
						<td><input type="text" name="fname" required /></td>
					</tr>
					<tr>
						<td><b>���</b> <font color="red">*</font>:</td>
						<td><input type="text" name="name" required /></td>
					</tr>
					<tr>
						<td><b>��������</b>:</td>
						<td><input type="text" name="lname" /></td>
					</tr>
					<tr>
						<td><b>eMail</b> <font color="red">*</font>:</td>
						<td><input type="text" name="mail" required/></td>
						<td>(� ���������, ������ �� ��������� ���� �� ��������������)</td>
					</tr>
				</table>
				<input type="submit" name="register" value="������������������" />
			</form> 
		
		</div> 

		<?php
			session_start();
			$link = mysqli_connect('localhost','root','','patent') or die("������ ��� ���������� � ����� ������.." . mysqli_error($link));

			if (isset($_SESSION['login'])){
				if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $_SESSION['timeout'])) {
					// last request was more than 2 minutes ago
					session_unset();     // unset $_SESSION variable for the run-time 
					session_destroy();   // destroy session data in storage
					echo '<div class="m_auth error">��������, ����� ����� ������ �������</div>';
				}
				$_SESSION['last_activity'] = time(); // update last activity time stamp
			}

			if(isset($_POST['register'])){
				$login=$_POST['login'];
				$password=$_POST['password'];
				$rpassword=$_POST['rpassword'];
				$fname=$_POST['fname'];
				$lname=$_POST['lname'];
				$name=$_POST['name'];
				$mail=$_POST['mail'];
				
				if($password != $rpassword) {
					echo '<div id="m_error">��������, ��������� ���� ������ �� ���������</div>';
				} else {
					if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
						echo ('<div id="m_error">��������, ��������� ���� eMail ����� ����� ������������ ������.</div>');
					} else {
						$password=md5($password);
						$query = "SELECT id FROM users WHERE login='$login'" or die("������ ��� ���������� �������.." . mysqli_error($link));
						$result = $link->query($query);
						$myrow = mysqli_fetch_array($result);
						$result->close();
						if (!empty($myrow['id'])) {
							echo ('<div id="m_error">��������, �������� ���� ����� ��� ���������������. ������� ������ �����</div>');
						} else {
							$query = "set names 'cp1251'" or die("������ ��� ���������� �������.." . mysqli_error($link)); 
							$link->query($query);
							$query = "INSERT INTO users VALUES ('','$login','$password', '$fname','$name','$lname','$mail',0)" or die("������ ��� ���������� �������.." . mysqli_error($link));
							$result = $link->query($query);
							if ($result == 'TRUE'){
								echo '<div id="m_success">�� ������� ����������������! ������ �� ������ ����� �� ����</div>';    
							}  
							else {
								echo '<div id="m_error">������ ��� ����������� ������ ������������</div>';  
							}
						}			
					} 
				}
			}
			
			if (isset($_SESSION['login'])) {
				echo '	<div id="sign-out">
							<div class="dropdown">
								<a class="account button" style="font:12px/normal sans-serif;">'.$_SESSION["login"].'<img src="Pictures/arrow.png" style="margin-left: 7px;"/></a>
				
								<div class="submenu" style="display: none; ">
									<ul class="root">
										<li><a href="inventions.php">��� �����������</a></li>
										<li><a href="patent.php">����� �����������</a></li>
										<li>
											<form method="post" action="index.php">
												<input type="submit" name="logout" value="�����"/>
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