$(document).ready(function(){
	$("#headimg").click(function(){
		$("#headinfo").slideDown("slow");
	});
	
	$("#headbarr").mouseleave(function(){
		$("#headinfo").slideUp("slow");
	});
	
	$("#logout").click(function(){
		$.post("dologout.php",{hide:"4"},
		function(data){
			if(data=='0')
				window.location.href="login.php";
		},"text");
	});
	
	$(document.body).on('click',".path",function(){
		$.post("dopath.php",{hide:"4",id:$(this).attr("pathid")},
		function(data){
			if(data=='0')
			$.post("domain.php",{hide:"4",type:0},
			function(mainfile){
				$("#alllist").html(mainfile);
			},"text");
		},"text");
		$(this).nextAll().remove();
		$(this).attr("id","last-path");
	});
	
	$(document.body).on('click',".foldername",function(){
		var foldername=$(this).text();
		var count=$("#last-path").attr("pathid");
		count++;
		$("#last-path").removeAttr("id");
		$(".crumbpath").append("<span class='path-separator'>></span>");
		$(".crumbpath").append("<span class='path' id='last-path' pathid="+count+">"+foldername+"</span>");
		$.post("openfile.php",{hide:"4",id:$(this).parents(".row").attr("listid"),pathid:count},
		function(data){
			if(data=='0')
				$.post("domain.php",{hide:"4",type:0},
				function(mainfile){
					$("#alllist").html(mainfile);
				},"text");
		},"text");
	});
	
	$("#tbbt").click(function(){
		$(".shade").css("display","block");
		$("#new-dialog").css("display","block");
	});
	
	$("#tbnew").click(function(){
		$("#newrow").css("display","block");
		$("#newinput").focus();
	});
	
	$(document.body).on('blur',"#newinput",function(){
		$.post("dohash.php",{hide:"4",hash:"SYSTEM_FILE",filename:$(this).val(),filetype:"folder"},
		function(data){
			if(data=='1')
				$.post("domain.php",{hide:"4",type:0},
				function(mainfile){
					$("#alllist").html(mainfile);
				},"text");
		},"text");
	});
	
	$(document.body).on('click',".download",function(){
		var url="include/lib/download.php?id="+$(this).parents(".row").attr("listid");
		$("#downloadiframe").attr("src",url);
	});
	
	$(document.body).on('click',".res",function(){
		$.post("restore.php",{hide:"4",id:$(this).parents(".row").attr("listid")},
		function(data){
			if(data=='0')
				$.post("domain.php",{hide:"4",type:97},
				function(mainfile){
					$("#recyclelist").html(mainfile);
				},"text");
		},"text");
	});
	
	$(document.body).on('click',".del",function(){
		$.post("delete.php",{hide:"4",id:$(this).parents(".row").attr("listid")},
		function(data){
			if(data=='0')
			{
				switch(location.hash)
				{
					case "#all":
					$("#selall").trigger("click");
					break;
					case "#doc":
					$("#seldoc").trigger("click");
					break;
					case "#img":
					$("#selimg").trigger("click");
					break;
					case "#music":
					$("#selmusic").trigger("click");
					break;
					case "#video":
					$("#selvideo").trigger("click");
					break;
					case "#exe":
					$("#selexe").trigger("click");
					break;
					case "#zip":
					$("#selzip").trigger("click");
					break;
					case "#bt":
					$("#selbt").trigger("click");
					break;
					case "#other":
					$("#selother").trigger("click");
					break;
					case "#share":
					$("#sharetxt").trigger("click");
					break;
					case "#recycle":
					$("#recycle").trigger("click");
					break;
				}
			}
		},"text");
	});
	
	$("#selall").click(function(){
		switch(location.hash)
		{
			case "#doc":
			$("#doc").css("display","none");
			break;
			case "#img":
			$("#img").css("display","none");
			break;
			case "#music":
			$("#music").css("display","none");
			break;
			case "#video":
			$("#video").css("display","none");
			break;
			case "#exe":
			$("#exe").css("display","none");
			break;
			case "#zip":
			$("#zip").css("display","none");
			break;
			case "#bt":
			$("#bt").css("display","none");
			break;
			case "#other":
			$("#other").css("display","none");
			break;
			case "#share":
			$("#share").css("display","none");
			break;
			case "#recycle":
			$("#recyclepage").css("display","none");
			break;
		}
		location.hash="#all";
		$(".seled").removeAttr("class");
		$(this).attr("class","seled");
		$.post("domain.php",{hide:"4",type:0},
		function(mainfile){
			$("#all").css("display","block");
			$("#alllist").html(mainfile);
		},"text");
	})
	
	$("#seldoc").click(function(){
		switch(location.hash)
		{
			case "#all":
			$("#all").css("display","none");
			break;
			case "#img":
			$("#img").css("display","none");
			break;
			case "#music":
			$("#music").css("display","none");
			break;
			case "#video":
			$("#video").css("display","none");
			break;
			case "#exe":
			$("#exe").css("display","none");
			break;
			case "#zip":
			$("#zip").css("display","none");
			break;
			case "#bt":
			$("#bt").css("display","none");
			break;
			case "#other":
			$("#other").css("display","none");
			break;
			case "#share":
			$("#share").css("display","none");
			break;
			case "#recycle":
			$("#recyclepage").css("display","none");
			break;
			default:
			$("#all").css("display","none");
			break;
		}
		location.hash="#doc";
		$(".seled").removeAttr("class");
		$(this).attr("class","seled");
		$.post("domain.php",{hide:"4",type:1},
		function(mainfile){
			$("#doc").css("display","block");
			$("#doclist").html(mainfile);
		},"text");
	})
	
	$("#selimg").click(function(){
		switch(location.hash)
		{
			case "#all":
			$("#all").css("display","none");
			break;
			case "#doc":
			$("#doc").css("display","none");
			break;
			case "#music":
			$("#music").css("display","none");
			break;
			case "#video":
			$("#video").css("display","none");
			break;
			case "#exe":
			$("#exe").css("display","none");
			break;
			case "#zip":
			$("#zip").css("display","none");
			break;
			case "#bt":
			$("#bt").css("display","none");
			break;
			case "#other":
			$("#other").css("display","none");
			break;
			case "#share":
			$("#share").css("display","none");
			break;
			case "#recycle":
			$("#recyclepage").css("display","none");
			break;
			default:
			$("#all").css("display","none");
			break;
		}
		location.hash="#img";
		$(".seled").removeAttr("class");
		$(this).attr("class","seled");
		$.post("domain.php",{hide:"4",type:10},
		function(mainfile){
			$("#img").css("display","block");
			$("#imglist").html(mainfile);
		},"text");
	})
	
	$("#selmusic").click(function(){
		switch(location.hash)
		{
			case "#all":
			$("#all").css("display","none");
			break;
			case "#doc":
			$("#doc").css("display","none");
			break;
			case "#img":
			$("#img").css("display","none");
			break;
			case "#video":
			$("#video").css("display","none");
			break;
			case "#exe":
			$("#exe").css("display","none");
			break;
			case "#zip":
			$("#zip").css("display","none");
			break;
			case "#bt":
			$("#bt").css("display","none");
			break;
			case "#other":
			$("#other").css("display","none");
			break;
			case "#share":
			$("#share").css("display","none");
			break;
			case "#recycle":
			$("#recyclepage").css("display","none");
			break;
			default:
			$("#all").css("display","none");
			break;
		}
		location.hash="#music";
		$(".seled").removeAttr("class");
		$(this).attr("class","seled");
		$.post("domain.php",{hide:"4",type:11},
		function(mainfile){
			$("#music").css("display","block");
			$("#musiclist").html(mainfile);
		},"text");
	})
	
	$("#selvideo").click(function(){
		switch(location.hash)
		{
			case "#all":
			$("#all").css("display","none");
			break;
			case "#doc":
			$("#doc").css("display","none");
			break;
			case "#img":
			$("#img").css("display","none");
			break;
			case "#music":
			$("#music").css("display","none");
			break;
			case "#exe":
			$("#exe").css("display","none");
			break;
			case "#zip":
			$("#zip").css("display","none");
			break;
			case "#bt":
			$("#bt").css("display","none");
			break;
			case "#other":
			$("#other").css("display","none");
			break;
			case "#share":
			$("#share").css("display","none");
			break;
			case "#recycle":
			$("#recyclepage").css("display","none");
			break;
			default:
			$("#all").css("display","none");
			break;
		}
		location.hash="#video";
		$(".seled").removeAttr("class");
		$(this).attr("class","seled");
		$.post("domain.php",{hide:"4",type:12},
		function(mainfile){
			$("#video").css("display","block");
			$("#videolist").html(mainfile);
		},"text");
	})
	
	$("#selexe").click(function(){
		switch(location.hash)
		{
			case "#all":
			$("#all").css("display","none");
			break;
			case "#doc":
			$("#doc").css("display","none");
			break;
			case "#img":
			$("#img").css("display","none");
			break;
			case "#music":
			$("#music").css("display","none");
			break;
			case "#video":
			$("#video").css("display","none");
			break;
			case "#zip":
			$("#zip").css("display","none");
			break;
			case "#bt":
			$("#bt").css("display","none");
			break;
			case "#other":
			$("#other").css("display","none");
			break;
			case "#share":
			$("#share").css("display","none");
			break;
			case "#recycle":
			$("#recyclepage").css("display","none");
			break;
			default:
			$("#all").css("display","none");
			break;
		}
		location.hash="#exe";
		$(".seled").removeAttr("class");
		$(this).attr("class","seled");
		$.post("domain.php",{hide:"4",type:15},
		function(mainfile){
			$("#exe").css("display","block");
			$("#exelist").html(mainfile);
		},"text");
	})
	
	$("#selzip").click(function(){
		switch(location.hash)
		{
			case "#all":
			$("#all").css("display","none");
			break;
			case "#doc":
			$("#doc").css("display","none");
			break;
			case "#img":
			$("#img").css("display","none");
			break;
			case "#music":
			$("#music").css("display","none");
			break;
			case "#video":
			$("#video").css("display","none");
			break;
			case "#exe":
			$("#exe").css("display","none");
			break;
			case "#bt":
			$("#bt").css("display","none");
			break;
			case "#other":
			$("#other").css("display","none");
			break;
			case "#share":
			$("#share").css("display","none");
			break;
			case "#recycle":
			$("#recyclepage").css("display","none");
			break;
			default:
			$("#all").css("display","none");
			break;
		}
		location.hash="#zip";
		$(".seled").removeAttr("class");
		$(this).attr("class","seled");
		$.post("domain.php",{hide:"4",type:13},
		function(mainfile){
			$("#zip").css("display","block");
			$("#ziplist").html(mainfile);
		},"text");
	})
	
	$("#selbt").click(function(){
		switch(location.hash)
		{
			case "#all":
			$("#all").css("display","none");
			break;
			case "#doc":
			$("#doc").css("display","none");
			break;
			case "#img":
			$("#img").css("display","none");
			break;
			case "#music":
			$("#music").css("display","none");
			break;
			case "#video":
			$("#video").css("display","none");
			break;
			case "#exe":
			$("#exe").css("display","none");
			break;
			case "#zip":
			$("#zip").css("display","none");
			break;
			case "#other":
			$("#other").css("display","none");
			break;
			case "#share":
			$("#share").css("display","none");
			break;
			case "#recycle":
			$("#recyclepage").css("display","none");
			break;
			default:
			$("#all").css("display","none");
			break;
		}
		location.hash="#bt";
		$(".seled").removeAttr("class");
		$(this).attr("class","seled");
		$.post("domain.php",{hide:"4",type:16},
		function(mainfile){
			$("#bt").css("display","block");
			$("#btlist").html(mainfile);
		},"text");
	})
	
	$("#selother").click(function(){
		switch(location.hash)
		{
			case "#all":
			$("#all").css("display","none");
			break;
			case "#doc":
			$("#doc").css("display","none");
			break;
			case "#img":
			$("#img").css("display","none");
			break;
			case "#music":
			$("#music").css("display","none");
			break;
			case "#video":
			$("#video").css("display","none");
			break;
			case "#exe":
			$("#exe").css("display","none");
			break;
			case "#zip":
			$("#zip").css("display","none");
			break;
			case "#bt":
			$("#bt").css("display","none");
			break;
			case "#share":
			$("#share").css("display","none");
			break;
			case "#recycle":
			$("#recyclepage").css("display","none");
			break;
			default:
			$("#all").css("display","none");
			break;
		}
		location.hash="#other";
		$(".seled").removeAttr("class");
		$(this).attr("class","seled");
		$.post("domain.php",{hide:"4",type:99},
		function(mainfile){
			$("#other").css("display","block");
			$("#otherlist").html(mainfile);
		},"text");
	})
	
	$("#sharetxt").click(function(){
		switch(location.hash)
		{
			case "#all":
			$("#all").css("display","none");
			break;
			case "#doc":
			$("#doc").css("display","none");
			break;
			case "#img":
			$("#img").css("display","none");
			break;
			case "#music":
			$("#music").css("display","none");
			break;
			case "#video":
			$("#video").css("display","none");
			break;
			case "#exe":
			$("#exe").css("display","none");
			break;
			case "#zip":
			$("#zip").css("display","none");
			break;
			case "#bt":
			$("#bt").css("display","none");
			break;
			case "#other":
			$("#other").css("display","none");
			break;
			case "#recycle":
			$("#recyclepage").css("display","none");
			break;
			default:
			$("#all").css("display","none");
			break;
		}
		location.hash="#share";
		$(".seled").removeAttr("class");
		$(this).attr("class","seled");
		$.post("domain.php",{hide:"4",type:98},
		function(mainfile){
			$("#share").css("display","block");
			$("#sharelist").html(mainfile);
		},"text");
	})
	
	$("#recycle").click(function(){
		switch(location.hash)
		{
			case "#all":
			$("#all").css("display","none");
			break;
			case "#doc":
			$("#doc").css("display","none");
			break;
			case "#img":
			$("#img").css("display","none");
			break;
			case "#music":
			$("#music").css("display","none");
			break;
			case "#video":
			$("#video").css("display","none");
			break;
			case "#exe":
			$("#exe").css("display","none");
			break;
			case "#zip":
			$("#zip").css("display","none");
			break;
			case "#bt":
			$("#bt").css("display","none");
			break;
			case "#other":
			$("#other").css("display","none");
			break;
			case "#share":
			$("#share").css("display","none");
			break;
			default:
			$("#all").css("display","none");
			break;
		}
		location.hash="#recycle";
		$(".seled").removeAttr("class");
		$(this).attr("class","seled");
		$.post("domain.php",{hide:"4",type:97},
		function(mainfile){
			$("#recyclepage").css("display","block");
			$("#recyclelist").html(mainfile);
		},"text");
	})
	
	$("#upbutton").click(function(){
		$("#upinput").trigger("click");
	});
	
	switch(location.hash)
	{
		case "#all":
		$("#selall").trigger("click");
		break;
		case "#doc":
		$("#seldoc").trigger("click");
		break;
		case "#img":
		$("#selimg").trigger("click");
		break;
		case "#music":
		$("#selmusic").trigger("click");
		break;
		case "#video":
		$("#selvideo").trigger("click");
		break;
		case "#exe":
		$("#selexe").trigger("click");
		break;
		case "#zip":
		$("#selzip").trigger("click");
		break;
		case "#bt":
		$("#selbt").trigger("click");
		break;
		case "#other":
		$("#selother").trigger("click");
		break;
		case "#share":
		$("#sharetxt").trigger("click");
		break;
		case "#recycle":
		$("#recycle").trigger("click");
		break;
		default:
		$(".seled").removeAttr("class");
		$("#selall").attr("class","seled");
		break;
	}
	
	$("#upinput").change(function(){
		if(!$("#upinput").val())
		{
			alert("没有选择文件");
			return;
		}
		file=this.files[0];
		var filesize=file.size
		if(filesize>4294967296)
		{
			alert("文件过大");
			return;
		}
		else
		{
			var buffer_size=4194304;		//4M
			var start=0;
			var hash="";
			var end= filesize > buffer_size ? buffer_size :filesize;
			var blob;
			var c=CryptoJS.algo.SHA1.create();
			function swapendian32(val) {
				return (((val & 0xFF) << 24)
				   | ((val & 0xFF00) << 8)
				   | ((val >> 8) & 0xFF00)
				   | ((val >> 24) & 0xFF)) >>> 0;
			}
			function arrayBufferToWordArray(arrayBuffer) {
				var fullWords = Math.floor(arrayBuffer.byteLength / 4);
				var bytesLeft = arrayBuffer.byteLength % 4;
				var u32 = new Uint32Array(arrayBuffer, 0, fullWords);
				var u8 = new Uint8Array(arrayBuffer);
				var cp = [];
				for (var i = 0; i < fullWords; ++i) {
					cp.push(swapendian32(u32[i]));
				}

				if (bytesLeft) {
					var pad = 0;
					for (var i = bytesLeft; i > 0; --i) {
						pad = pad << 8;
						pad += u8[u8.byteLength - i];
					}
					for (var i = 0; i < 4 - bytesLeft; ++i) {
						pad = pad << 8;
					}
					cp.push(pad);
				}

				return CryptoJS.lib.WordArray.create(cp, arrayBuffer.byteLength);
			};
			function readBlob(){
				blob = file.slice(start, end);
				reader.readAsArrayBuffer(blob);	
			}
			reader = new FileReader();
			readBlob();
			reader.onload = function (e){
				var wordArray=arrayBufferToWordArray(e.target.result);
				var b=c.update(wordArray);
				if(end !== filesize) {
				start += buffer_size;
				end += buffer_size;
				if(end > filesize) {
					end = filesize;
				}
				readBlob();
				}
				else
				{
					hash=c.finalize().toString();
					$.post("dohash.php",{hide:"4",hash:hash,filename:file.name,filetype:file.type},
					function(data){
						if(data!="1")
						{
							if(data=="0")
							{
								var filedata=new FormData();
								$.each($("#upinput")[0].files,function(i,upfile){
									filedata.append('hide',"4");
									filedata.append('hash',hash);
									filedata.append('upload_file',upfile);
								});
								$.ajax({
									url:'doupload.php',
									type:'POST',
									data:filedata,
									cache: false,
									contentType: false,
									processData: false,
									success:function(data1){
										if(data1=="0")
										{
											$.post("domain.php",{hide:"4",type:0},
											function(mainfile){
												$("#alllist").html(mainfile);
											},"text");
										}
										else
										{
											alert(data1);
										}
									}
								});
							}
							else
							{
								alert(data);
							}
						}
						else
						{
							$.post("domain.php",{hide:"4",type:0},
							function(mainfile){
								$("#alllist").html(mainfile);
							},"text");
						}
					},"text");
				}
			};
		}
	});
	
	$("#new-x").click(function(){
		$(".shade").css("display","none");
		$("#new-dialog").css("display","none");
		$.post("domain.php",{hide:"4",type:0},
		function(mainfile){
			$("#alllist").html(mainfile);
		},"text");
	});
	
	$("#newlink").click(function(){
		$("#new-dialog").css("display","none");
		$("#link-dialog").css("display","block");
	});
	
	$("#link-x").click(function(){
		$(".shade").css("display","none");
		$("#link-dialog").css("display","none");
	});
	
	$("#linkstart").click(function(){
		if($("#linkinput").val()=="")
		{
			$("#errormsg").html("请输入下载链接");
			return;
		}
		
		$.post("dodownload.php",{hide:"4",downloadurl: decodeURI($("#linkinput").val())},
		function(msg){
			$("#errormsg").html(msg);
		},"text");
	});
});