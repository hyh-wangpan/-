<?php
$dir = dirname(__FILE__);
require_once $dir."/include/lib/sqltool.php";
header("content-type: text/html;charset=utf-8");
session_start();

function parse_http_response($string) 
{
	$headers = array();
	$content = '';
	$str = strtok($string, "\n");
	$h = null;
	while ($str !== false) {
		if ($h and trim($str) === '') {                
			$h = false;
			continue;
		}
		if ($h !== false and false !== strpos($str, ':')) {
			$h = true;
			list($headername, $headervalue) = explode(':', trim($str), 2);
			$headername = strtolower($headername);
			$headervalue = ltrim($headervalue);
			if (isset($headers[$headername])) 
				$headers[$headername] .= ',' . $headervalue;
			else 
				$headers[$headername] = $headervalue;
		}
		if ($h === false) {
			$content .= $str."\n";
		}
		$str = strtok("\n");
	}
	return array($headers, trim($content));
}

function headresponse($url)
{
	$user_agent=$_SERVER['HTTP_USER_AGENT'];
	$httpheader=array('Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8','Accept-Encoding:gzip,deflate,sdch','Accept-Language:zh-CN,zh;q=0.8');
	
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$httpheader);
	curl_setopt($ch,CURLOPT_USERAGENT,$user_agent);  //发送用户系统和浏览器信息
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0); // 对认证证书来源的检查      
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2); // 从证书中检查SSL加密算法是否存在      
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);   //设置重定向
	curl_setopt($ch,CURLOPT_AUTOREFERER,1);  //自动重定向
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  //以字符串返回
	curl_setopt($ch,CURLOPT_HEADER,1);  //返回响应头
	curl_setopt($ch,CURLOPT_NOBODY,1);  //不返回响应体
	$output=curl_exec($ch);
	
	if(curl_errno($ch))
		return false;
	else
		return parse_http_response($output);
	curl_close($ch);
}

function getresponse($url)
{
	$user_agent=$_SERVER['HTTP_USER_AGENT'];
	$httpheader=array('Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8','Accept-Encoding:gzip,deflate,sdch','Accept-Language:zh-CN,zh;q=0.8');
	
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$httpheader);
	curl_setopt($ch,CURLOPT_USERAGENT,$user_agent);  //发送用户系统和浏览器信息
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0); //对认证证书来源的检查      
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2); //从证书中检查SSL加密算法是否存在      
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);   //设置重定向
	curl_setopt($ch,CURLOPT_AUTOREFERER,1);  //自动重定向
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  //以字符串返回
	curl_setopt($ch,CURLOPT_HEADER,1);  //返回响应头
	curl_setopt($ch,CURLOPT_HTTPGET,1);  //GET方式请求
	curl_setopt($ch,CURLOPT_TIMEOUT,1);  //执行的最长时间
	$output=curl_exec($ch);
	
	if(curl_errno($ch))
	{
		curl_close($ch);
		return false;
	}
	else
	{
		curl_close($ch);
		$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($output, 0, $headerSize);
		return parse_http_response($header);
	}
}

function gethash($url)
{
	$user_agent=$_SERVER['HTTP_USER_AGENT'];
	$httpheader=array('Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8','Accept-Encoding:gzip,deflate,sdch','Accept-Language:zh-CN,zh;q=0.8');
	
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$httpheader);
	curl_setopt($ch,CURLOPT_USERAGENT,$user_agent);  //发送用户系统和浏览器信息
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0); //对认证证书来源的检查      
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2); //从证书中检查SSL加密算法是否存在      
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);   //设置重定向
	curl_setopt($ch,CURLOPT_AUTOREFERER,1);  //自动重定向
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  //以字符串返回
	curl_setopt($ch,CURLOPT_HTTPGET,1);  //GET方式请求
	curl_setopt($ch,CURLOPT_TIMEOUT,10);  //执行的最长时间
	curl_setopt($ch,CURLOPT_RANGE,"0-2048");
	$output=curl_exec($ch);
	curl_setopt($ch,CURLOPT_RANGE,"-2048");
	$output.=curl_exec($ch);
	curl_close($ch);
	return sha1($output);
}

if(!isset($_POST['hide'])||$_POST['hide']!='4')
	header("location:index.php");
else
{
	if(!isset($_POST['downloadurl'])||$_POST['downloadurl']==NULL)
	{
		echo "请输入要下载的链接";
	}
	else
	{
		$downloadurl=$_POST['downloadurl'];
		$userid=$_SESSION['id'];
		$sqltool=new sqltool;
		$row=$sqltool->select('offline',"link='$downloadurl'");
		if(empty($row))
		{
			$urlarr=parse_url($downloadurl);
			$filename=substr(strrchr($urlarr['path'],"/"),1);
			
			if(headresponse($downloadurl))
			{
				$header=headresponse($downloadurl);
			}
			elseif(getresponse($downloadurl))
			{
				$header=getresponse($downloadurl);
			}
			else
			{
				echo '链接错误';
				$sqltool->close();
				exit(0);
			}
			$headfilesize=$header[0]['content-length'];   //headfilesize == 头文件里获取的文件大小 downloadsize == 下载文件的实际大小 filesize == 数据库里存储的文件大小
			if(isset($header[0]['content-disposition']))
			{
				list($other,$name) = explode('=',trim($str),2);
				$filename=ltrim($name);
			}
			if(isset($header[0]['etag']))
			{
				$etag=$header[0]['etag'];
			}
			if(isset($GLOBALS['tempname']))
			{
				$temp=$GLOBALS['tempname'];
				$tempname=$temp.'.temp';
				$GLOBALS['tempname']=$temp+1;
			}
			else
			{
				$GLOBALS['tempname']=1;
				$tempname='1.temp';
			}
			file_put_contents($tempname,file_get_contents($downloadurl)); //下载文件
			$hash=sha1_file($tempname);
			$downloadsize=filesize($tempname);
			if(isset($header[0]['content-md5']))
			{
				$md5=$header[0]['content-md5'];
			}
			else
			{
				if($downloadsize<10240)
				{
					$md5=$hash;
				}
				else
				{
					$file=file_get_contents($tempname,NULL,NULL,0,2048);
					$file.=file_get_contents($tempname,NULL,NULL,-2048);
					$md5=sha1($file);
				}
			}
			if($downloadsize!=$headfilesize)
			{
				echo '下载失败请重试';
				error_log('在下载文件'.$tempname."时出错，文件大小不一致。\r\n",3,'error.log');
				exit(0);
			}
			$filedir='./upload_file/';
			$hashpath=chunk_split($hash,4,'/');
			$hasharr=str_split($hashpath,5);
			for($i=0;$i<9;$i++)
			{
				$filedir.=$hasharr[$i];
				if(!file_exists($filedir))
					mkdir($filedir);
			}
			rename($tempname,$filedir.$hash); //移动文件
			$fileid=$sqltool->insert('file',"NULL,'$hash',$headfilesize"); 
			if($fileid!=-1)
			{
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
				$sqltool->insert('offline',"NULL,'$downloadurl',$fileid,'$filename',$filetype,$headfilesize,'$md5','$etag'");
				if($_SESSION['usedsize']+$headfilesize<$_SESSION['totalsize'])
				{
					$successrow=$sqltool->insert('userfile',"NULL,$userid,".$_SESSION['parentid'].",$fileid,'$filename',$filetype,NOW(),0,0");
					if($successrow!=-1)
					{
						$_SESSION['usedsize']+=$headfilesize;
						$sqltool->update('users',"id=$userid",'usedsize=usedsize+'.$headfilesize);
						echo '0';
					}
				}
				else
				{
					echo '离线下载失败，文件超出了总大小。';
				}
			}
			$sqltool->close();
		}
		else
		{
			if(headresponse($downloadurl))
			{
				$header=headresponse($downloadurl);
			}
			elseif(getresponse($downloadurl))
			{
				$header=getresponse($downloadurl);
			}
			else
			{
				list(,,$fileid,$filename,$filetype,$filesize)=$row[0];
				if($_SESSION['usedsize']+$filesize<$_SESSION['totalsize'])
				{
					$successrow=$sqltool->insert('userfile',"NULL,$userid,".$_SESSION['parentid'].",$fileid,'$filename',$filetype,NOW(),0,0");
					if($successrow!=-1)
					{
						$_SESSION['usedsize']+=$filesize;
						$sqltool->update('users',"id=$userid",'usedsize=usedsize+'.$filesize);
						echo '0';
					}
				}
				else
				{
					echo '离线下载失败，文件超出了总大小。';
				}
				$sqltool->close();
			}
			$headfilesize=$header[0]['content-length'];
			$sizearr=[];
			foreach($row as $key=>$val)
			{
				if($headfilesize==$val['size'])
				{
					$sizearr[]=$val;
				}
			}
			if(empty($sizearr))
			{
				if(isset($header[0]['content-disposition']))
				{
					list($other,$name) = explode('=',trim($str),2);
					$filename=ltrim($name);
				}
				if(isset($header[0]['etag']))
				{
					$etag=$header[0]['etag'];
				}
				if(isset($GLOBALS['tempname']))
				{
					$temp=$GLOBALS['tempname'];
					$tempname=$temp.'.temp';
					$GLOBALS['tempname']=$temp+1;
				}
				else
				{
					$GLOBALS['tempname']=1;
					$tempname='1.temp';
				}
				file_put_contents($tempname,file_get_contents($downloadurl)); //下载文件
				$hash=sha1_file($tempname);
				$downloadsize=filesize($tempname);
				if(isset($header[0]['content-md5']))
				{
					$md5=$header[0]['content-md5'];
				}
				else
				{
					if($downloadsize<10240)
					{
						$md5=$hash;
					}
					else
					{
						$file=file_get_contents($tempname,NULL,NULL,0,2048);
						$file.=file_get_contents($tempname,NULL,NULL,-2048);
						$md5=sha1($file);
					}
				}
				if($downloadsize!=$headfilesize)
				{
					echo '下载失败请重试';
					error_log('在下载文件'.$tempname."时出错，文件大小不一致。\r\n",3,'error.log');
					exit(0);
				}
				$filedir='./upload_file/';
				$hashpath=chunk_split($hash,4,'/');
				$hasharr=str_split($hashpath,5);
				for($i=0;$i<9;$i++)
				{
					$filedir.=$hasharr[$i];
					if(!file_exists($filedir))
						mkdir($filedir);
				}
				rename($tempname,$filedir.$hash); //移动文件
				$fileid=$sqltool->insert('file',"NULL,'$hash',$headfilesize"); 
				if($fileid!=-1)
				{
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
					$sqltool->insert('offline',"NULL,'$downloadurl',$fileid,'$filename',$filetype,$headfilesize,'$md5','$etag'");
					if($_SESSION['usedsize']+$headfilesize<$_SESSION['totalsize'])
					{
						$successrow=$sqltool->insert('userfile',"NULL,$userid,".$_SESSION['parentid'].",$fileid,'$filename',$filetype,NOW(),0,0");
						if($successrow!=-1)
						{
							$_SESSION['usedsize']+=$headfilesize;
							$sqltool->update('users',"id=$userid",'usedsize=usedsize+'.$headfilesize);
							echo '0';
						}
					}
					else
					{
						echo '离线下载失败，文件超出了总大小。';
					}
				}
			}
			elseif(count($sizearr)==1)
			{
				$fileid=$sizearr[0]['fileid'];
				$filename=$sizearr[0]['filename'];
				$filetype=$sizearr[0]['filetype'];
				if($_SESSION['usedsize']+$headfilesize<$_SESSION['totalsize'])
				{
					$successrow=$sqltool->insert('userfile',"NULL,$userid,".$_SESSION['parentid'].",$fileid,'$filename',$filetype,NOW(),0,0");
					if($successrow!=-1)
					{
						$_SESSION['usedsize']+=$headfilesize;
						$sqltool->update('users',"id=$userid",'usedsize=usedsize+'.$headfilesize);
						echo '0';
					}
				}
				else
				{
					echo '离线下载失败，文件超出了总大小。';
				}
			}
			else
			{
				$md5=gethash($downloadurl);
				foreach($sizearr as $key=>$val)
				{
					if($md5==$val['hash'])
					{
						$fileid=$val['fileid'];
						$filename=$val['filename'];
						$filetype=$val['filetype'];
						if($_SESSION['usedsize']+$headfilesize<$_SESSION['totalsize'])
						{
							$successrow=$sqltool->insert('userfile',"NULL,$userid,".$_SESSION['parentid'].",$fileid,'$filename',$filetype,NOW(),0,0");
							if($successrow!=-1)
							{
								$_SESSION['usedsize']+=$headfilesize;
								$sqltool->update('users',"id=$userid",'usedsize=usedsize+'.$headfilesize);
								echo '0';
							}
						}
						else
						{
							echo '离线下载失败，文件超出了总大小。';
						}
					}
					else
					{
						if(isset($header[0]['content-disposition']))
						{
							list($other,$name) = explode('=',trim($str),2);
							$filename=ltrim($name);
						}
						if(isset($header[0]['etag']))
						{
							$etag=$header[0]['etag'];
						}
						if(isset($GLOBALS['tempname']))
						{
							$temp=$GLOBALS['tempname'];
							$tempname=$temp.'.temp';
							$GLOBALS['tempname']=$temp+1;
						}
						else
						{
							$GLOBALS['tempname']=1;
							$tempname='1.temp';
						}
						file_put_contents($tempname,file_get_contents($downloadurl)); //下载文件
						$hash=sha1_file($tempname);
						$downloadsize=filesize($tempname);
						if(isset($header[0]['content-md5']))
						{
							$md5=$header[0]['content-md5'];
						}
						else
						{
							if($downloadsize<10240)
							{
								$md5=$hash;
							}
							else
							{
								$file=file_get_contents($tempname,NULL,NULL,0,2048);
								$file.=file_get_contents($tempname,NULL,NULL,-2048);
								$md5=sha1($file);
							}
						}
						if($downloadsize!=$headfilesize)
						{
							echo '下载失败请重试';
							error_log('在下载文件'.$tempname."时出错，文件大小不一致。\r\n",3,'error.log');
							exit(0);
						}
						$filedir='./upload_file/';
						$hashpath=chunk_split($hash,4,'/');
						$hasharr=str_split($hashpath,5);
						for($i=0;$i<9;$i++)
						{
							$filedir.=$hasharr[$i];
							if(!file_exists($filedir))
								mkdir($filedir);
						}
						rename($tempname,$filedir.$hash); //移动文件
						$fileid=$sqltool->insert('file',"NULL,'$hash',$headfilesize"); 
						if($fileid!=-1)
						{
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
							$sqltool->insert('offline',"NULL,'$downloadurl',$fileid,'$filename',$filetype,$headfilesize,'$md5','$etag'");
							if($_SESSION['usedsize']+$headfilesize<$_SESSION['totalsize'])
							{
								$successrow=$sqltool->insert('userfile',"NULL,$userid,".$_SESSION['parentid'].",$fileid,'$filename',$filetype,NOW(),0,0");
								if($successrow!=-1)
								{
									$_SESSION['usedsize']+=$headfilesize;
									$sqltool->update('users',"id=$userid",'usedsize=usedsize+'.$headfilesize);
									echo '0';
								}
							}
							else
							{
								echo '离线下载失败，文件超出了总大小。';
							}
						}
					}
				}
			}
		}
	}
}
?>