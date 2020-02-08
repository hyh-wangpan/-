<?php
$dir = dirname(__FILE__);
require_once $dir."/include/lib/sqltool.php";
session_start();
if(isset($_COOKIE["id"]))
{
	if($_COOKIE["wangpan"]==sha1($_SERVER['DOCUMENT_ROOT'].KEY))
	{
		$id=$_COOKIE["id"];
		$sqltool=new sqltool;
		$row=$sqltool->select('users','id='.$id);
		$sqltool->close();
		$pw=$row[0]['password'];
		$hash=sha1(sha1($id.KEY).sha1($pw.KEY).sha1($_COOKIE["time"].KEY).sha1($_SERVER['HTTP_USER_AGENT']));
		$hmac=sha1(sha1($id.KEY).sha1($_COOKIE["time"].KEY).sha1($hash));
		if($hmac==$_COOKIE["hmac"])
		{
			$_SESSION['id']=$id;
			$_SESSION['email']=$row[0]['email'];
			$_SESSION['nickname']=$row[0]['nickname'];
			$_SESSION['totalsize']=$row[0]['totalsize'];
			$_SESSION['usedsize']=$row[0]['usedsize'];
			header("location:main.php");
		}
	}
}
require 'login.php';
?>