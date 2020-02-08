<?php
$dir = dirname(__FILE__);
require_once $dir."/include/lib/sqltool.php";
header("content-type: text/html;charset=utf-8");
session_start();

if(!isset($_SESSION['id'])||!isset($_POST['hide'])||$_POST['hide']!='4'||!isset($_POST['id'])||$_POST['id']==NULL)
	header("location:index.php");
else
{
	$userid=$_SESSION['id'];
	$listid=$_POST['id'];
	$userfileid=$_SESSION['fileid'];
	$id=$userfileid[$listid];
	$sqltool=new sqltool();
	$row=$sqltool->select('userfile',"id=$id");
	$fileid=$row[0]['fileid'];
	$row=$sqltool->select('file',"id=$fileid");
	$filesize=$row[0]['size'];
	$successrow=$sqltool->update('userfile',"id=$id",'state=1');
	if($successrow!=-1)
	{
		
		$_SESSION['usedsize']-=$filesize;
		$sqltool->update('users',"id=$userid",'usedsize=usedsize-'.$filesize);
		echo '0';
	}
}
?>