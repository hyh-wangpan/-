<?php
$dir = dirname(__FILE__);
require_once $dir."/include/lib/sqltool.php";
header("content-type: text/html;charset=utf-8");
session_start();
if(empty($_SESSION['id']))
{
	header("location:index.php");
}

if(empty($_POST['hide'])||$_POST['hide']!='4')
	header("location:login.php");
else
{
	if(empty($_POST['hash']))
	{
		header("location:main.php");
	}
	else
	{
		$hash=$_POST['hash'];
		$sqltool=new sqltool();
		$row=$sqltool->select('file',"hash='$hash'");
		$filename=$_POST['filename'];
		if(!empty($row))
		{
			$filesize=$row[0]['size'];
			if($_SESSION['usedsize']+$filesize<$_SESSION['totalsize'])
			{
				$userid=$_SESSION['id'];
				$type=$_POST['filetype'];
				switch(substr($type,0,strpos($type,'/')))
				{
					case 'text':
					switch($type)
					{
						case 'text/html':
						case 'text/plain':
						case 'text/xml':
						$filetype=9;
						break;
						default:
						$filetype=99;
					}
					break;
					case 'image':
					$filetype=10;
					break;
					case 'audio':
					$filetype=11;
					break;
					case 'video':
					$filetype=12;
					break;
					default:
					$filetype=99;
				}
				if($type="folder")
					$filetype=2;
				$ext=substr($filename,strrpos($filename,'.')+1);
				switch($ext)
				{
					case 'txt':
					$filetype=3;
					break;
					case 'doc':
					case 'docx';
					$filetype=4;
					break;
					case 'ppt':
					case 'pptx':
					$filetype=5;
					break;
					case 'xls':
					case 'xlsx':
					$filetype=6;
					break;
					case 'pdf':
					$filetype=7;
					break;
					case 'chm':
					$filename=8;
					break;
					case 'zip':
					case 'rar':
					case '7z':
					case '001':
					case 'bz2':
					case 'gz':
					case 'gzip':
					case 'tar':
					case 'taz':
					case 'tgz':
					case 'txz':
					case 'lha':
					case 'lzh':
					$filetype=13;
					break;
					case 'fon':
					case 'ttf':
					$filetype=14;
					break;
					case 'js':
					case 'vbs':
					case 'py':
					case 'php':
					$filetype=9;
					break;
					case 'exe':
					case 'apk':
					case 'msi':
					$filetype=15;
					break;
					case 'torrent':
					$filetype=16;
					break;
				}
				//$existrow=$sqltool->select('userfile',"userid=$userid AND parentid=".$_SESSION['parentid']." AND filename='$filename'");
				//if(!empty($existrow))
					//$filename.=' - 副本';			//如果有重复文件这重命名文件
				$successrow=$sqltool->insert('userfile',"NULL,$userid,".$_SESSION['parentid'].','.$row[0]['id'].",'$filename',$filetype,NOW(),0,0");
				if($successrow!=-1)
				{
					$_SESSION['usedsize']+=$filesize;
					$sqltool->update('users',"id=$userid",'usedsize=usedsize+'.$filesize);
					echo '1';
				}
			}
			else
			{
				echo '上传失败，文件超出了总大小。';
			}
		}
		else
		{
			echo '0';
		}
		$sqltool->close();
	}
}
?>