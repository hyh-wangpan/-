$(document).ready(function(){
	$("#email").change(function(){
		var reg=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/gi;
		if(!reg.test($("#email").val()))
			$("#errorprompt").html("邮箱格式不正确");
		else
			$("#errorprompt").html("");
	});
	
	$("#password").change(function(){
		var reg=/^.{6,20}$/gi;
		if(!reg.test($("#password").val()))
			$("#errorprompt").html("至少6个字符，最多20个字符");
		else
		{
			reg=/\w+\d+\W+/gi;
			if(!reg.test($("#password").val()))
			{
				$("#errorprompt").html("必须包含字母、数字、特殊字符");
			}
			else
				$("#errorprompt").html("");
		}
	});
	
	$("#registerbutton").click(function(){
		$.post("doregister.php",{hide:"4",email:$("#email").val(),password:$("#password").val(),checkcode:$("#checktxt").val()},
		function(data){
			if(data=='0')
			{
				window.location.href="index.php";
			}
			else if(data=='1')
			{
				$("#errorprompt").html("邮箱已被注册请直接登录。");
				$("#checkimg").attr('src','include/lib/checkcode.php');
			}
			else
			{
				$("#errorprompt").html(data);
			}
		},"text");
	});
	$("#checkimg").attr('src','include/lib/checkcode.php');
	$("#checktxt").val("");
	$("#checkimg").click(function(){
		$(this).attr('src','include/lib/checkcode.php');
	});
})