$(document).ready(function(){
	$("#loginbutton").click(function(){
		if($("#email").val()=="")
		{
			$("#errorprompt").html("请输入用户名");
			return;
		}
		
		if($("#password").val()=="")
		{
			$("#errorprompt").html("请输入密码");
			return;
		}
		
		$.post("dologin.php",{hide:"4",email:$("#email").val(),password:$("#password").val(),checkcode:$("#checktxt").val(),dxk:$("#dxk:checked").val()},
		function(data){
			if(data=="0")
			{
				window.location.href="main.php";
			}
			else if(data=="1")
			{
				$("#errorprompt").html("用户名或密码错误");
				$("#checktxt").val("");
				$("#checkimg").attr('src','include/lib/checkcode.php');
			}
			else
				$("#errorprompt").html(data);
		},"text");
	});
	$("#checkimg").attr('src','include/lib/checkcode.php');
	$("#checktxt").val("");
	$("#checkimg").click(function(){
		$(this).attr('src','include/lib/checkcode.php');
	});
});