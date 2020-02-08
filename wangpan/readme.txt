目前只实现单文件上传 断点续传
将要实现的功能：
	分段上传
	多文件上传
	文件夹上传
	文件历史版本
	上传文件名相同
	文件分享
	
enum state(normal,deleted,previous);
enum share(no,yes);
enum filetype(disk,driver,folder);


txt 3
doc
docx 4
ppt
pptx  5
xls
xlsx  6
pdf  7
chm  8

css= 代码
text/html=  网页
text/plain==  代码
vbs====daima
text/xml===daima
text/plain/daima  9

image/*===============tupian   10

audio/*============audio  11

video/* ==========shipin  12

zip/rar/gz================= 13

font/*==============font    14

exe apk           15

bt============== 16

other ==============99

需要在显示文件的部分添加一个数组
键为显示的列表id值为文件id