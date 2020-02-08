<?php
$dir = dirname(__FILE__);
require_once $dir."/include/lib/sqltool.php";
header("content-type: text/html;charset=utf-8");
session_start();
if(!isset($_SESSION['id']))
{
	header("location:index.php");
}
else
{
	$id=$_SESSION['id'];
}
if(!isset($_POST['hide'])||$_POST['hide']!='4')
	header("location:index.php");
else
{
	$sqltool=new sqltool();
	?>
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
	$fileid=array();
	$type=$_POST['type'];
	switch ($type)
	{
		case 0:
		$row=$sqltool->select('userfile',"userid=$id AND parentid=".$_SESSION['parentid'].' AND state=0');
		break;
		case 10:
		case 11:
		case 12:
		case 13:
		case 15:
		case 16:
		$row=$sqltool->select('userfile',"userid=$id AND filetype=$type AND state=0");
		break;
		case 1:
		$row=$sqltool->select('userfile',"userid=$id AND filetype IN (3,4,5,6,7,8,9) AND state=0");
		break;
		case 99:
		$row=$sqltool->select('userfile',"userid=$id AND filetype IN (14,99) AND state=0");
		break;
		case 98:
		$row=$sqltool->select('userfile',"userid=$id AND share=1");
		break;
		case 97:
		$row=$sqltool->select('userfile',"userid=$id AND state=1");
		break;
	}
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
		<span class="operate"><?php
		if($type==97)
			echo '<a href="javascript:void(0);" class="res" target="_blank">还原</a>';
		else
			echo '<a href="javascript:void(0);" class="download" target="_blank" download="'.$value['filename'].'">下载</a>';
		?></span>
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
<?php
}
?>