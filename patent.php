<html>
    <head>
        <title>����������� ������ ������������</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
		<style>
   td {
    vertical-align: top; /* ������������ �� �������� ���� ����� */
   }
  </style>
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
			<form method="post" action="patent.php" id="test" enctype="multipart/form-data">
				����, ���������� ���������, ����������� ��� ����������<br />
				<table>
					<tr>
						<td><b>��������<font color="red">*</font>:</b> </td>
						<td><input type="text" name="name" required /></td>
					</tr>
					<tr>
						<td><b>����������:</b></td>
						<td><input type="file" name="filename"></td>
					</tr>
					<tr>
						<td><b>��������<font color="red">*</font>:</b></td>
						<td>
						<textarea name="description" rows="7" cols="50" required ></textarea></td>
					</tr>
				</table>
				 <input type="submit" name="register_p" value="����������������" />
			</form> 
		
		</div> 
		
		<div id="menu">
			<a href="index.php" class="button" />�������</a>
			<a href="services.php" class="button"/>������</a>
			<a href="news.php" class="button"/>�������</a>
			<a href="inventions.php" class="button"/>�����������</a>
			<a href="registration.php" class="button"/>�����������</a>
		</div>

		<?php
			session_start();
			$link = mysqli_connect('localhost','root','','patent') or die("������ ��� ���������� � ����� ������.." . mysqli_error($link));
			if (isset($_COOKIE['a'])) {
				$c=$_COOKIE['a'];
			} else {
				unset($_SESSION['name']);
				unset($_SESSION['admin']);
				unset($_SESSION['login']);
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
				  echo("������ �������� �����");
			   }
			
				$name=$_POST['name'];
				//$photo=$_POST['photo'];
				$photo="Uploads/".$_FILES["filename"]["name"];
				$description=$_POST['description'];
				
				$query = "START TRANSACTION;" or die("������ ��� ���������� �������.." . mysqli_error($link));
				$result = $link->query($query);
				
				$query = "CALL CheckInventions('$name', '$description', @p_out);" or die("������ ��� ���������� �������.." . mysqli_error($link));
				$result = $link->query($query);
				
				$query = "SELECT @p_out AS count;" or die("������ ��� ���������� �������.." . mysqli_error($link));
				$result = $link->query($query);
				
				$myrow = mysqli_fetch_array($result);
				if ($myrow['count']>0) {
					echo '<div id="m_error">������� ���������� ������ ������ �����������! ����������, ��������� �������� ��� �������� �����������.</div>';
				}else{
					$query = "set names 'cp1251'" or die("������ ��� ���������� �������.." . mysqli_error($link)); 
					$link->query($query);
					

							
					$query="INSERT INTO inventions (name, description, photo, author_id)
								VALUES ('$name', '$description', '$photo', (SELECT id FROM users WHERE login = '".$_SESSION['login']."'))" or die("������ ��� ���������� �������.." . mysqli_error($link)); 
					$result = $link->query($query);
					if ($result='TRUE'){
						echo '<div id="m_success">
								����������� ������� ����������������.<br> 
								�� ������ �������� ����� ���������� ������������� ����� ������!<br><br> 
								<a href="inventions.php" class="button">��� �����������</a></p>
							</div>';
					} else {
						echo '<div id="m_error">
								������ ��� ���������� � ���� ������.
							</div>';
					}
					
				}
				$query = "COMMIT;" or die("������ ��� ���������� �������.." . mysqli_error($link));
				$result = $link->query($query);
			}
			mysqli_close($link);
			
			if (isset($_SESSION['name'])) {
				echo '<br><div id="vhod"><form method="post" action="index.php">
					<input type="submit" name="logout" value="�����"/>
					</form></div>';
			}
		?>
	</body>
</html>