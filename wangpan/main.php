<?php
$dir = dirname(__FILE__);
require_once $dir."/include/lib/sqltool.php";
session_start();
if(!isset($_SESSION['id']))
{
	header("location:index.php");
}
else
{
	$id=$_SESSION['id'];
}
$sqltool=new sqltool();
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>云盘-全部文件</title>
<link rel="stylesheet" type="text/css" href="include/css/common.css">
<link rel="stylesheet" type="text/css" href="include/css/main.css">
<script src="include/js/jquery-3.1.0.js"></script>
<script src="include/js/sha1.js"></script>
<script src="include/js/main.js"></script>
</head>
<body>
<div id="head">
<ul id="headbar">
<li><a href="main.php"><img src="include/img/logo.png"></a></li>
<li><a id="wangpan" href="main.php"></a></li>
<li><a id="fenxiang" href=""></a></li>
<li><a id="gongxiang" href=""></a></li>
</ul>
<div id="headbarr">
<div id="headimg">
<img src="include/img/head.png">
</div>
<div id="headinfo">
<ul>
<li id="infotext"><?php
$row=$sqltool->select('users',"id=$id");
$usedsize=$row[0]['usedsize'];
$totalsize=$row[0]['totalsize'];
echo $row[0]['nickname'];
?></li>
<li><a href="user.php">个人资料</a></li>
<li id="logout"><a>退出</a></li>
</ul>
</div>
</div>
</div>
<div id="sidebar">
<ul>
<li id="selall"><a>所有文件</a></li>
<li id="seldoc"><a>文档</a></li>
<li id="selimg"><a>图片</a></li>
<li id="selmusic"><a>音乐</a></li>
<li id="selvideo"><a>视频</a></li>
<li id="selexe"><a>程序</a></li>
<li id="selzip"><a>压缩包</a></li>
<li id="selbt"><a>种子</a></li>
<li id="selother"><a>其他</a></li>
<li id="sharetxt"><a>分享</a></li>
<li id="recycle"><a>回收站</a></li>
</ul>
<div id="space">
<span id="total">
<span id="used" style="width: <?php echo $usedsize/$totalsize*100 ?>%;"></span>
</span>
<div id="space-content">
<span><?php
		if($usedsize<1024)
		{
			echo $usedsize.'B';
		}
		elseif($usedsize<1048576)
		{
			echo round($usedsize/1024).'K';
		}
		elseif($usedsize<1073741824)
		{
			echo round($usedsize/1048576,1).'M';
		}
		elseif($usedsize<1099511627776)
		{
			echo round($usedsize/1073741824,2).'G';
		} 
		?>/<?php
		if($totalsize<1024)
		{
			echo $totalsize.'B';
		}
		elseif($totalsize<1048576)
		{
			echo round($totalsize/1024).'K';
		}
		elseif($totalsize<1073741824)
		{
			echo round($totalsize/1048576,1).'M';
		}
		elseif($totalsize<1099511627776)
		{
			echo round($totalsize/1073741824,2).'G';
		} 
		?></span>
</div>
</div>
<div><span></span></div>
</div>
<div class="main">
<div id="all">
<div id="toolbar">
<div id="toolbar-left">
<div>
<div class="item">
<span id="tbupload"><input type="file" id="upinput"><a id="upbutton">上传</a></span>
</div>
<div class="item">
<span id="tbnew">新建文件夹</span>
</div>
<div class="item">
<span id="tbbt">离线下载</span>
</div>
</div>
</div>
<div id="toolbar-right">
<div id="search">
<span id="searchimg"><img src="include/img/ico.png"></span>
<input id="searchinput" type="text" placeholder="搜索文件">
</div>
</div>
</div>
<div id="crumb">
<div class="crumbpath"><?php
$row=$sqltool->select('userfile',"userid=$id AND filetype=1");
if(!isset($_SESSION['parentid']))
{
	$_SESSION['parentid']=$row[0]['id'];
	$pathid=array(0 => $row[0]['id']);
	$_SESSION['pathid']=$pathid;
	?><span class="path" id="last-path" pathid="0">全部文件</span><?php
}
else
{
	$parentid=$_SESSION['parentid'];
	if($parentid==$row[0]['id'])
	{
		echo '<span class="path" id="last-path" pathid="0">全部文件</span>';
	}
	else
	{
	?><span class="path" pathid="0">全部文件</span><?php
	$pathid=$_SESSION['pathid'];
	for($i=1;$parentid!=$pathid[$i];$i++)
	{
		$row=$sqltool->select('userfile',"userid=$id AND id=".$pathid[$i]);
		echo '<span class="path-separator">></span>';
		echo '<span class="path" pathid="'.$i.'">'.$row[0]['filename'].'</span>';
	}
	$row=$sqltool->select('userfile',"userid=$id AND id=".$parentid);
	echo '<span class="path-separator">></span>';
	echo '<span class="path" id="last-path" pathid="'.$i.'">'.$row[0]['filename'].'</span>';
	}
}
?></div>
</div>
<div id="filelist">
<div class="" id="filelisthead">
<div></div>
<div class="column column-name">
<span>文件名</span>
</div>
<div class="column column-size">大小</div>
<div class="column column-time">修改时间</div>
</div>
<div class="filelistmain">
<script>
var height=$(document).height()-186;
$(".filelistmain").css("height",height);
</script>
<div id="alllist">
<ul id="list">
<li class="row" id="newrow">
<div></div>
<div class="column-name">
<span class="fileicon folderico"></span>
<span><input type="text" id="newinput" value="新建文件夹"></span>
</div>
<div class="column-size">-</div>
<div class="column-time">-</div>
</li>
<?php
$row=$sqltool->select('userfile',"userid=$id AND parentid=".$_SESSION['parentid'].' AND state=0');
$fileid=array();
foreach($row as $key => $value)
{
	$fileid[$key+1]=$value['id'];
	?>
	<li class="row" listid="<?php echo $key+1; ?>">
	<div></div>
	<div class="column-name">
	<span class="fileicon <?php 
	switch($value['filetype'])
	{
		case 2:
		echo 'folderico';
		break;
		case 3:
		echo 'txtico';
		break;
		case 4:
		echo 'docico';
		break;
		case 5:
		echo 'pptico';
		break;
		case 6:
		echo 'xlsico';
		break;
		case 7:
		echo 'pdfico';
		break;
		case 9:
		echo 'codeico';
		break;
		case 10:
		echo 'imgico';
		break;
		case 11:
		echo 'audioico';
		break;
		case 12:
		echo 'videoico';
		break;
		case 13:
		echo 'zipico';
		break;
		case 14:
		echo 'fontico';
		break;
		case 15:
		echo 'exeico';
		break;
		case 16:
		echo 'btico';
		break;
		case 8:
		default:
		echo 'otherico';
	}
	?>"></span>
	<span class="filename<?php
	if($value['filetype']==2)
		echo ' foldername';	
	?>"><?php echo $value['filename'] ?></span>
	<?php
	if($value['filetype']!=2)
	{
		?>
	<span class="operate">
	<a href="javascript:void(0);" class="del">删除</a>
	</span>
	<span class="operate">
	<a href="javascript:void(0);" class="download" target="_blank" download="<?php echo $value['filename']; ?>">下载</a>
	</span>
		<?php
	}
	?>
	</div>
	<div class="column-size"><?php 
	if(2!=$value['filetype'])
	{
		$file=$sqltool->select('file','id='.$value['fileid']);
		$filesize=$file[0]['size'];
		if($filesize<1024)
		{
			echo $filesize.'B';
		}
		elseif($filesize<1048576)
		{
			echo round($filesize/1024).'K';
		}
		elseif($filesize<1073741824)
		{
			echo round($filesize/1048576,1).'M';
		}
		elseif($filesize<1099511627776)
		{
			echo round($filesize/1073741824,2).'G';
		}
	}
	else
	{
		echo '-';
	}
	?></div>
	<div class="column-time"><?php echo $value['modified']; ?></div>
	</li>
	<?php
}
$sqltool->close();
$_SESSION['fileid']=$fileid;
?>
</ul>
</div>
</div>
</div>
</div>
<div id="doc" style="display: none;">
<div id="toolbar">
<div id="toolbar-left">
<div>
<div class="item">
<span id="tbupload"><input type="file" id="upinput"><a id="upbutton">上传</a></span>
</div>
<div class="item">
<span id="tbbt">离线下载</span>
</div>
</div>
</div>
<div id="toolbar-right">
<div id="search">
<span id="searchimg"><img src="include/img/ico.png"></span>
<input id="searchinput" type="text" placeholder="搜索文件">
</div>
</div>
</div>
<div class="genus">
文档
</div>
<div id="filelist">
<div class="" id="filelisthead">
<div></div>
<div class="column column-name">
<span>文件名</span>
</div>
<div class="column column-size">大小</div>
<div class="column column-time">修改时间</div>
</div>
<div class="filelistmain">
<script>
var height=$(document).height()-186;
$(".filelistmain").css("height",height);
</script>
<div id="doclist">
</div>
</div>
</div>
</div>
<div id="img" style="display: none;">
<div id="toolbar">
<div id="toolbar-left">
<div>
<div class="item">
<span id="tbupload"><input type="file" id="upinput"><a id="upbutton">上传</a></span>
</div>
<div class="item">
<span id="tbbt">离线下载</span>
</div>
</div>
</div>
<div id="toolbar-right">
<div id="search">
<span id="searchimg"><img src="include/img/ico.png"></span>
<input id="searchinput" type="text" placeholder="搜索文件">
</div>
</div>
</div>
<div class="genus">
图片
</div>
<div id="filelist">
<div class="" id="filelisthead">
<div></div>
<div class="column column-name">
<span>文件名</span>
</div>
<div class="column column-size">大小</div>
<div class="column column-time">修改时间</div>
</div>
<div class="filelistmain">
<script>
var height=$(document).height()-186;
$(".filelistmain").css("height",height);
</script>
<div id="imglist">
</div>
</div>
</div>
</div>
<div id="music" style="display: none;">
<div id="toolbar">
<div id="toolbar-left">
<div>
<div class="item">
<span id="tbupload"><input type="file" id="upinput"><a id="upbutton">上传</a></span>
</div>
<div class="item">
<span id="tbbt">离线下载</span>
</div>
</div>
</div>
<div id="toolbar-right">
<div id="search">
<span id="searchimg"><img src="include/img/ico.png"></span>
<input id="searchinput" type="text" placeholder="搜索文件">
</div>
</div>
</div>
<div class="genus">
音乐
</div>
<div id="filelist">
<div class="" id="filelisthead">
<div></div>
<div class="column column-name">
<span>文件名</span>
</div>
<div class="column column-size">大小</div>
<div class="column column-time">修改时间</div>
</div>
<div class="filelistmain">
<script>
var height=$(document).height()-186;
$(".filelistmain").css("height",height);
</script>
<div id="musiclist">
</div>
</div>
</div>
</div>
<div id="video" style="display: none;">
<div id="toolbar">
<div id="toolbar-left">
<div>
<div class="item">
<span id="tbupload"><input type="file" id="upinput"><a id="upbutton">上传</a></span>
</div>
<div class="item">
<span id="tbbt">离线下载</span>
</div>
</div>
</div>
<div id="toolbar-right">
<div id="search">
<span id="searchimg"><img src="include/img/ico.png"></span>
<input id="searchinput" type="text" placeholder="搜索文件">
</div>
</div>
</div>
<div class="genus">
视频
</div>
<div id="filelist">
<div class="" id="filelisthead">
<div></div>
<div class="column column-name">
<span>文件名</span>
</div>
<div class="column column-size">大小</div>
<div class="column column-time">修改时间</div>
</div>
<div class="filelistmain">
<script>
var height=$(document).height()-186;
$(".filelistmain").css("height",height);
</script>
<div id="videolist">
</div>
</div>
</div>
</div>
<div id="exe" style="display: none;">
<div id="toolbar">
<div id="toolbar-left">
<div>
<div class="item">
<span id="tbupload"><input type="file" id="upinput"><a id="upbutton">上传</a></span>
</div>
<div class="item">
<span id="tbbt">离线下载</span>
</div>
</div>
</div>
<div id="toolbar-right">
<div id="search">
<span id="searchimg"><img src="include/img/ico.png"></span>
<input id="searchinput" type="text" placeholder="搜索文件">
</div>
</div>
</div>
<div class="genus">
程序
</div>
<div id="filelist">
<div class="" id="filelisthead">
<div></div>
<div class="column column-name">
<span>文件名</span>
</div>
<div class="column column-size">大小</div>
<div class="column column-time">修改时间</div>
</div>
<div class="filelistmain">
<script>
var height=$(document).height()-186;
$(".filelistmain").css("height",height);
</script>
<div id="exelist">
</div>
</div>
</div>
</div>
<div id="zip" style="display: none;">
<div id="toolbar">
<div id="toolbar-left">
<div>
<div class="item">
<span id="tbupload"><input type="file" id="upinput"><a id="upbutton">上传</a></span>
</div>
<div class="item">
<span id="tbbt">离线下载</span>
</div>
</div>
</div>
<div id="toolbar-right">
<div id="search">
<span id="searchimg"><img src="include/img/ico.png"></span>
<input id="searchinput" type="text" placeholder="搜索文件">
</div>
</div>
</div>
<div class="genus">
压缩包
</div>
<div id="filelist">
<div class="" id="filelisthead">
<div></div>
<div class="column column-name">
<span>文件名</span>
</div>
<div class="column column-size">大小</div>
<div class="column column-time">修改时间</div>
</div>
<div class="filelistmain">
<script>
var height=$(document).height()-186;
$(".filelistmain").css("height",height);
</script>
<div id="ziplist">
</div>
</div>
</div>
</div>
<div id="bt" style="display: none;">
<div id="toolbar">
<div id="toolbar-left">
<div>
<div class="item">
<span id="tbupload"><input type="file" id="upinput"><a id="upbutton">上传</a></span>
</div>
<div class="item">
<span id="tbbt">离线下载</span>
</div>
</div>
</div>
<div id="toolbar-right">
<div id="search">
<span id="searchimg"><img src="include/img/ico.png"></span>
<input id="searchinput" type="text" placeholder="搜索文件">
</div>
</div>
</div>
<div class="genus">
种子
</div>
<div id="filelist">
<div class="" id="filelisthead">
<div></div>
<div class="column column-name">
<span>文件名</span>
</div>
<div class="column column-size">大小</div>
<div class="column column-time">修改时间</div>
</div>
<div class="filelistmain">
<script>
var height=$(document).height()-186;
$(".filelistmain").css("height",height);
</script>
<div id="btlist">
</div>
</div>
</div>
</div>
<div id="other" style="display: none;">
<div id="toolbar">
<div id="toolbar-left">
<div>
<div class="item">
<span id="tbupload"><input type="file" id="upinput"><a id="upbutton">上传</a></span>
</div>
<div class="item">
<span id="tbbt">离线下载</span>
</div>
</div>
</div>
<div id="toolbar-right">
<div id="search">
<span id="searchimg"><img src="include/img/ico.png"></span>
<input id="searchinput" type="text" placeholder="搜索文件">
</div>
</div>
</div>
<div class="genus">
其他
</div>
<div id="filelist">
<div class="" id="filelisthead">
<div></div>
<div class="column column-name">
<span>文件名</span>
</div>
<div class="column column-size">大小</div>
<div class="column column-time">修改时间</div>
</div>
<div class="filelistmain">
<script>
var height=$(document).height()-186;
$(".filelistmain").css("height",height);
</script>
<div id="otherlist">
</div>
</div>
</div>
</div>
<div id="share" style="display: none;">
<div class="genus">
全部分享
</div>
<div id="filelist">
<div class="" id="filelisthead">
<div></div>
<div class="column column-name">
<span>文件名</span>
</div>
<div class="column column-size">大小</div>
<div class="column column-time">修改时间</div>
</div>
<div class="filelistmain">
<script>
var height=$(document).height()-186;
$(".filelistmain").css("height",height);
</script>
<div id="sharelist">
</div>
</div>
</div>
</div>
<div id="recyclepage" style="display: none;">
<div class="genus">
回收站
</div>
<div id="filelist">
<div class="" id="filelisthead">
<div></div>
<div class="column column-name">
<span>文件名</span>
</div>
<div class="column column-size">大小</div>
<div class="column column-time">修改时间</div>
</div>
<div class="filelistmain">
<script>
var height=$(document).height()-186;
$(".filelistmain").css("height",height);
</script>
<div id="recyclelist">
</div>
</div>
</div>
</div>
</div>
<div class="shade" style="position: fixed; left: 0px; top: 0px; z-index: 50; opacity: 0.5; width: 100%; height: 100%; display: none; background: rgb(0, 0, 0);">
</div>
<div class="dialog" id="new-dialog">
<div class="dialog-head">
<span class="dialog-head-left">离线下载列表</span>
<span class="dialog-head-right" id="new-x"><img src="/include/img/x.png"></span>
</div>
<div class="dialog-body">
<div class="buttondiv">
<div class="item"><span id="newbt">新建bt下载</span></div>
<div class="item"><span id="newlink">新建链接下载</span></div>
</div>
<div class="dialog-column">
<div class="column dialog-column-name">文件名</div>
<div class="column dialog-column-size">大小</div>
<div class="column dialog-column-status">状态</div>
<div class="column dialog-column-handler">操作</div>
</div>
<div></div>
</div>
</div>
<div class="dialog" id="link-dialog">
<div class="dialog-head">
<span class="dialog-head-left">新建链接任务</span>
<span class="dialog-head-right" id="link-x"><img src="/include/img/x.png"></span>
</div>
<div class="dialog-body">
<div id="linktext">要下载文件的链接：</div>
<input class="inputbox" id="linkinput" type="text" placeholder="http/https链接">
<div id="errormsg"></div>
<input id="linkstart" type="submit" value="开始">
</div>
</div>
<iframe id="downloadiframe"></iframe>
</body>
</html>