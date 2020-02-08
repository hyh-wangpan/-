<?php
session_start();
if(empty($_SESSION['id']))
{
	header("location:index.php");
}

if(empty($_POST['hide'])||$_POST['hide']!='4')
	header("location:index.php");
else
{
	session_destroy();
	foreach($_COOKIE as $key=>$val){
	 setcookie($key,"",time()-60);
	}
	echo '0';
}
?>