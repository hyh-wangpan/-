<?php
$dir = dirname(__FILE__);
require_once $dir."/include/lib/sqltool.php";
header("content-type: text/html;charset=utf-8");
session_start();
if(!isset($_SESSION['id'])||!isset($_POST['hide'])||$_POST['hide']==NULL||$_POST['hide']!='4'||!isset($_POST['id'])||$_POST['id']==NULL)
	header("location:index.php");
else
{
	$id=$_POST['id'];
	$pathid=$_SESSION['pathid'];
	$folderid=$pathid[$id];
	$_SESSION['parentid']=$folderid;
	echo '0';
}
?>