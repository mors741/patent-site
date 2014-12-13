<html>
    <head>
        <title>����������� ������ ������������</title>
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
			
			<a href="index.php" class="button" />�������</a>
			<a href="services.php" class="button"/>������</a>
			<a href="news.php" class="button"/>�������</a>
			<a href="inventions.php" class="button"/>�����������</a>
			<a href="registration.php" class="button"/>�����������</a>
		</div>
		<?php
			session_start();		
			if (isset($_SESSION['login'])) {
				echo '	<div id="reg">
							<form method="post" action="patent.php" id="test" enctype="multipart/form-data">
							����, ���������� ��������� <font color="red">*</font>, ����������� ��� ����������<br>
							<table>
								<tr>
									<td><b>��������<font color="red">*</font>:</b> </td>
									<td><input type="text" name="name" class="inputs long" required /></td>
								</tr>
								<tr>
									<td><b>����������:</b></td>
									<td><input type="file" name="filename"></td>
								</tr>
								<tr>
									<td><b>��������<font color="red">*</font>:</b></td>
									<td>
									<textarea name="description" rows="7" cols="50" class="inputs long" required ></textarea></td>
								</tr>
								<tr>
									<td></td>
									<td><input type="checkbox" name="sleep" value="true">�������� �������� (��� ������������)<br><br></td>
								</tr>
								<tr>
									<td></td>
									<td><input type="submit" name="register_p" class="button" value="����������������"/></td>
								</tr>
							</table>
						</form> 
					</div> ';

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
			}else{
				echo '<div id="content"><h3>��������, ����������� ������ ����������� �������� ������ �������������� �������������</h3></div>';
			}
		?>
		
		<?php
			$link = mysqli_connect('localhost','root','','patent') or die("������ ��� ���������� � ����� ������.." . mysqli_error($link));

			if (isset($_SESSION['login'])){
				if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $_SESSION['timeout'])) {
					// last request was more than 2 minutes ago
					session_unset();     // unset $_SESSION variable for the run-time 
					session_destroy();   // destroy session data in storage
					echo '<div class="m_auth m_error">��������, ����� ����� ������ �������</div>';
				}
				$_SESSION['last_activity'] = time(); // update last activity time stamp
			}
			
			if(isset($_POST['register_p'])){		
				if($_FILES["filename"]["size"] > 1024*3*1024)
				{
					echo ("������ ����� ��������� ��� ���������");
					exit;
				}
				// ��������� �������� �� ����
				if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
				{
					// ���� ���� �������� �������, ���������� ���
					// �� ��������� ���������� � ��������
					move_uploaded_file($_FILES["filename"]["tmp_name"], "Uploads/".$_FILES["filename"]["name"]);
				} else {
				  //echo("������ �������� �����");
			   }
				$login = $_SESSION['login'];
				$name = $_POST['name'];
				$description=$_POST['description'];
				$photo = "Uploads/".$_FILES["filename"]["name"];
				
				if($photo == "Uploads/"){
					$photo = "Uploads/no_photo.jpg";
				}
				
				$query = "START TRANSACTION;" or die("������ ��� ���������� �������.." . mysqli_error($link));
				$link->query($query);				
				
				$query = "CALL CheckInventions('$name', '$description', @p_out);" or die("������ ��� ���������� �������.." . mysqli_error($link));
				$link->query($query);
				
				$query = "SELECT @p_out AS count;" or die("������ ��� ���������� �������.." . mysqli_error($link));
				$result = $link->query($query);
				
				$myrow = mysqli_fetch_array($result);
				$result->close();
				if ($myrow['count']>0) {
					echo ('<div class="m_new_inv m_error">������� ���������� ������ ������ �����������! ����������, ��������� �������� ��� �������� �����������.</div>');
				}else{
					$query = "set names 'cp1251'" or die("������ ��� ���������� �������.." . mysqli_error($link)); 
					$link->query($query);
					
					$query="INSERT INTO inventions (name, description, photo, author_id)
								VALUES ('$name', '$description', '$photo', (SELECT id FROM users WHERE login = '".$_SESSION['login']."'))" or die("������ ��� ���������� �������.." . mysqli_error($link)); 
					$ins_res = $link->query($query);
					
					if ($ins_res == 'TRUE'){
											
						$query="UPDATE users SET inv_count=inv_count + 1 WHERE login = '$login';" or die("������ ��� ���������� �������.." . mysqli_error($link)); 
						$link->query($query);
						echo ('<div class="m_new_inv m_success">����������� ������� ����������������!</div>');
					} else {
						echo ('<div class="m_new_inv m_error">������� ���������� ������ ������ �����������! ����������, ��������� �������� ��� �������� �����������.</div>');
					}
				}
				if (isset($_POST['sleep'])){
					$query = "DO SLEEP(5);" or die("������ ��� ���������� �������.." . mysqli_error($link));
					$link->query($query);
				}
				
				$query = "COMMIT;" or die("������ ��� ���������� �������.." . mysqli_error($link));
				$link->query($query);
				

				
			}
			mysqli_close($link);
		?>
	</body>
</html>