<html>
<head>
<TITLE>Задание 1. Числа Фибоначчи</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>

<body leftmargin="20" topmargin="20" >
F0=1<br>
F1=1<br>
<?php
$f1=1;
$f2=1;
for ($i=2; $i<=40; $i++)
{
$ftmp=$f2;
$f2=$f1+$f2;
$f1=$ftmp;

echo "F".$i."=".$f2."<br>\n";

}

?>
</body></html>