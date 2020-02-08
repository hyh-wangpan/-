<?php
$dir = dirname(__FILE__);
require_once $dir."/include/lib/sqltool.php";
header("content-type: text/html;charset=utf-8");
session_start();

if(!isset($_POST['hide'])||$_POST['hide']!='4')
	header("location:index.php");
else
{
	if(!isset($_POST['checkcode'])||$_POST['checkcode']==NULL)
	{
		echo "请填写验证码";
	}
	else
	{
		$checkcode=strtolower($_POST['checkcode']);			//验证码
		if($checkcode!=$_SESSION['checkcode'])
		{
			echo "验证码错误";
		}
		else
		{
			if(!isset($_POST['email'])||$_POST['email']==NULL)
			{
				echo "请填写邮箱";
			}
			else
			{
				$email=$_POST['email'];		//用户名
				if(!isset($_POST['password'])||$_POST['password']==NULL)
					echo "请填写密码";
				else
				{
					$pw=sha1($_POST['password'].KEY);			//密码
					$sqltool=new sqltool;
					$row=$sqltool->select('users',"email='$email' OR phone='$email'");
					$sqltool->close();
					if(!empty($row))
					{
						if($row[0]['password']==$pw)
						{
							$id=$row[0]['id'];
							$_SESSION['id']=$id;
							$_SESSION['email']=$row[0]['email'];
							$_SESSION['nickname']=$row[0]['nickname'];
							$_SESSION['totalsize']=$row[0]['totalsize'];
							$_SESSION['usedsize']=$row[0]['usedsize'];
							if(!empty($_POST['dxk']))
							{
								$time=time();
								$hash=sha1(sha1($id.KEY).sha1($pw.KEY).sha1($time.KEY).sha1($_SERVER['HTTP_USER_AGENT']));
								$hmac=sha1(sha1($id.KEY).sha1($time.KEY).sha1($hash));
								setcookie("wangpan",sha1($_SERVER['DOCUMENT_ROOT'].KEY),time()+864000);
								setcookie("id",$id,time()+864000);
								setcookie("hmac",$hmac,time()+864000);
								setcookie("time",$time,time()+864000);
							}
							//跳转到主页面
							echo '0';
						}
						else
						{
							echo "1";
						}
					}
					else
					{
						echo "1";
					}
				}
			}
		}
	}
}
?>