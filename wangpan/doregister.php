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
					$password=$_POST['password'];
					$reg='/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/';
					if(!preg_match($reg,$email))
					{
						echo '邮箱格式不正确';
						exit(0);
					}
					
					$reg='/^.{6,20}$/';
					if(!preg_match($reg,$password))
					{
						echo '至少6个字符，最多20个字符';
						exit(0);
					}
					
					$reg='/\w+\d+\W+/';
					if(!preg_match($reg,$password))
					{
						echo '必须包含字母、数字、特殊字符';
						exit(0);
					}
					
					$pw=sha1($password.KEY);			//密码
					$sqltool=new sqltool;
					$row=$sqltool->select('users',"email='$email'");
					if(empty($row))
					{
						$iniuser=$sqltool->insert('users',"NULL,'$email','$pw','$email',NULL,NOW(),$totalsize,0");
						if($iniuser!=-1)
						{
							$iniuserfile=$sqltool->insert('userfile',"NULL,$iniuser,1,1,'SYSTEM_DRIVER',1,NOW(),0,0");
							if($iniuserfile!=-1)
							{
								echo '0';
							}
						}
					}
					else
					{
						echo '1';
					}
				}
			}
		}
	}
}
?>