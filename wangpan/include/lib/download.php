<?php
$dir = dirname(__FILE__);
require_once $dir."/sqltool.php";
session_start();
function dowmload($filepath,$filename,$filesize)
{
	
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$filename");
	
	$totalsize=$filesize-1;
	
	if(isset($_SERVER['HTTP_RANGE']))
	{
		
		list($a,$range)=explode("=",$_SERVER['HTTP_RANGE']);
		list($range,$end)=explode("-",$range);
		if($range>$totalsize)
		{
			exit();
		}
		
		if($end>$totalsize)
		{
			$end=$totalsize;
		}
		
		if($end==NULL)
		{
			$newsize=$totalsize-$range;
			$end=$totalsize;
		}
		elseif($range==NULL)
		{
			$newsize=$end;
			$range=$totalsize-$end;
		}
		else
		{
			$newsize=$end-$range;
		}
		
		header("HTTP/1.1 206 Partial Content");
		header("Content-Length: $newsize");
		header("Accept-Ranges: bytes");
		header("Content-Range: bytes $range-$totalsize/$filesize");
		$filecount=$range;
	}
	else
	{
		header("Content-Length: $filesize");
		header("Accept-Ranges: bytes");
		header("Content-Range: bytes 0-$totalsize/$filesize");
		$filecount=0;
		$newsize=$filesize;
		$end=$filesize;
		$range=0;
	}
	
	if($end<$range)
	{
		exit(0);
	}
	
	$buffersize=4194304;
	
	$fp=fopen($filepath,"rb");
	fseek($fp,$filecount);
	
	if($buffersize>$newsize)
	{
		$buffersize=$newsize;
	}
	
	while(!feof($fp) && $filecount<$filesize)
	{
		set_time_limit(0);
		$buffer=fread($fp,$buffersize);
		$filecount+=$buffersize;
		echo $buffer;
	}
	
	fclose($fp);
}
if(!isset($_SERVER['HTTP_REFERER']))
{
	header("location:../../index.php");
}
else
{
	if(7!=strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))
	{
		header("location:../../index.php");
	}
	else
	{
		if(empty($_GET['id']))
		{
			header("location:../../index.php");
		}
		else
		{
			$listid=$_GET['id'];
			$userfileid=$_SESSION['fileid'];
			$id=$userfileid[$listid];
			$sqltool=new sqltool();
			$userfile=$sqltool->select('userfile',"id=$id");
			$fileid=$userfile[0]['fileid'];
			$filename=$userfile[0]['filename'];
			$file=$sqltool->select('file',"id=$fileid");
			$filesize=$file[0]['size'];
			$hash=$file[0]['hash'];
			$sqltool->close();
			$hashpath=chunk_split($hash,4,'/');
			$hasharr=str_split($hashpath,5);
			$path='../../upload_file/';
			for($i=0;$i<9;$i++)
			{
			$path.=$hasharr[$i];
			}
			dowmload($path.$hash,$filename,$filesize);
		}
	}
}
?>