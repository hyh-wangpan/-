<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>注册</title>
<link rel="stylesheet" type="text/css" href="include/css/common.css">
<link rel="stylesheet" type="text/css" href="include/css/register.css">
<script src="include/js/jquery-3.1.0.js"></script>
<script src="include/js/register.js"></script>
</head>
<body>
<div class="register">
<h1>注册</h1>
<?php
$register='email';
if(isset($_GET['type']))
$register=$_GET['type'];
if($register=='email')
{
	?>
	<span id="errorprompt"></span>
	<p class="register-main">
	<input type="text" class="inputbox" id="email" placeholder="邮箱">
	</p>
	<p class="register-main">
	<input type="password" class="inputbox" id="password" placeholder="密码">
	</p>
	<p class="register-main">
	<input type="text" class="inputbox" id="checktxt" placeholder="验证码">
	<img id="checkimg" src="include/lib/checkcode.php" alt="验证码">
	</p>
	<?php
}
elseif($register=='phone')
{
	?>
	<!--<p class="registerphonep"><input type="text" id="registerphone" class="registerphone" placeholder="手机号"></p> <p><input type="password"></p>暂无手机号注册选项 -->你是怎么来到这个页面的，这里还没有完成啊！我并没有打算让你用手机好来注册啊！！！我们不会像国内那些公司一样让你绑定手机然后不停的给你发短信的，你要是换了手机号也不会这样问你要你身份证照片的啊。
	<?php
}
?>
<p class="register-main">
<input type="submit" id="registerbutton" value="注册">
<a class="login" href="login.php">登录</a>
</p>
</div>
</body>
</html>