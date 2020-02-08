<?php
$dir = dirname(__FILE__);
require_once $dir."/include/lib/sqltool.php";
header("content-type: text/html;charset=utf-8");
session_start();
if(!isset($_SESSION['id']))
{
	header("location:index.php");
}
if(!isset($_POST['hide'])||$_POST['hide']!='4')
	header("location:login.php");
else
{
	if(!isset($_POST['id'])||$_POST['id']==NULL)
	{
		header("location:main.php");
	}
	else
	{
		$listid=$_POST['id'];
		$userfileid=$_SESSION['fileid'];
		$id=$userfileid[$listid];
		$sqltool=new sqltool();
		$userfile=$sqltool->select('userfile',"id=$id");
		$filetype=$userfile[0]['filetype'];
		switch($filetype)
		{
			case 2:
			$deepid=$_POST['pathid'];
			openfolder($id,$deepid);
			break;
		}
	}
}

function openfolder($id,$deepid)
{
	$pathid=$_SESSION['pathid'];
	$pathid[$deepid]=$id;
	$_SESSION['parentid']=$id;
	$_SESSION['pathid']=$pathid;
	echo '0';
}
?>