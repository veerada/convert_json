<?php
	include("include/function.php");
	connect_db();
	$file=empty($_GET['file'])?"":$_GET['file'];
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Export Json for Trello</title>
 <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
 <link rel="stylesheet" type="text/css" href="css/mystyle.css">
 <script src="js/jquery-1.11.3.min.js"></script>
 <script src="js/bootstrap.min.js"></script>
 <script>
 	$(function(){  
     $(window).scroll(function(){    
         if($(document).scrollTop()>112){   
            $(".menu").css({"position":"fixed","padding-top":"0","height":"100%"}); 
         }else{
 			$(".menu").css({"position":"absolute","padding-top":"100px","height":"115%"}); 
         }      
     });
    });
 </script>
</head>
<body>
<div class="header">
	<br>
	<h2 class="unset_margin" style="color:white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระบบสารสนเทศทางคอมพิวเตอร์</h2>
	<h4 class="unset_margin" style="color:white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;แปลงไฟล์ json จาก Trello ให้ออกมาในรูปแบบตาราง</h4>
</div>
<div class="menu">
	<center><h3 style="margin:10px">เพิ่มไฟล์<h3></center>
	<div class="list-group">
	<a href="index.php" class="list-group-item"> แปลงไฟล์ Json </a>
	</div>
	
	<center><h3 style="margin:10px">รายการที่ถูกอัพโหลด<h3></center>
	<div class="list-group">
	  <a href="index.php?file=detail&file_id=total" class="list-group-item">
	    <b>ดูรายการที่ถูกอัพโหลดทั้งหมด</b>
	  </a>
<?php
	$history=mysql_query("SELECT * FROM history ORDER BY file_id DESC LIMIT 5")or die(mysql_error());
	while(list($file_id,$file_original,$file_json,$name,$date_upload)=mysql_fetch_row($history)){
		if(strlen($file_json)>42){
			$file_json=substr($file_json,0,42)."...";
		}
		echo"<a href='index.php?file=detail&file_id=$file_id&title=$name' class='list-group-item'><b>$name</b> $date_upload</a>";
	}
?>
	</div>
</div>
<div class="main">
<?php
	if(empty($_GET['file'])){
	   include("include/add_json.php");
	}else{
		get_file($file);
	}       
?>

</div>


</body>
</html>