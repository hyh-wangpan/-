<?php
session_start();
header('Content-type:image/png');
//创建画布
$im=imagecreate($x=130,$y=45);
$bg=imagecolorallocate($im,255,255,255);	//填充背景色
$fontcolor=imagecolorallocate($im,53,86,172);		//字体的颜色
$fontstyle='../fonts/arial.ttf';		//字体

//产生随机验证码，随机的产生一个4到6为的包括数字和字母的验证码
$checkcode="";
$num=rand(4,6);
switch ($num)
	{
		case 4:
		$chrwidth=30;
		break;
		case 5:
		$chrwidth=24;
		break;
		case 6:
		$chrwidth=20;
		break;
	}

	//在画布上画出验证码
for($i=0;$i<$num;$i++)
{
	$randasciinumarray=array(rand(48,57),rand(65,90),rand(97,122));
	$randasciinum=$randasciinumarray[rand(0,2)];
	$randstr=chr($randasciinum);
	imagettftext($im,$chrwidth,rand(0,20)-rand(0,25),5+$i*$chrwidth,rand(30,35),$fontcolor,$fontstyle,$randstr);
	$checkcode.=$randstr;
}

//存入到session
$_SESSION['checkcode']=strtolower($checkcode);

//竖向细线条
for($i=0;$i<8;$i++)
{
	$linecolor=imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
	imageline($im,rand(0,$x),0,rand(0,$x),$y,$linecolor);
}

imagesetthickness($im ,4);
$linecolor=imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
$start_y=rand(5,40);
$end_y=rand(5,40);

//横向粗线条
for($i=0;$i<$num;$i++)
{
	imageline($im,$i*$chrwidth,$start_y,($i+1)*$chrwidth,$end_y,$fontcolor);
	$start_y=$end_y;
	$end_y=rand(5,40);
}

//随机噪点
for($i=0;$i<350;$i++)
{
	imagesetpixel($im,rand(0,$x),rand(0,$y),$fontcolor);
}

imagepng($im);
imagedestroy($im);