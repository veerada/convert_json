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
  <link rel="stylesheet" type="text/css" href="js/jquery-ui.css">
 <script src="js/jquery-1.11.3.min.js"></script>
 <script src="js/jquery-ui.js"></script>
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
     	$('#member').change(function() {
            $.ajax({
                type: 'POST',
                data: {member: $(this).val()},
                url: 'include/select_team.php',
                success: function(data) {document.getElementById("team").value=data}
            });
       		
     	});
     	$( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true,altField: "#alternate",altFormat: "DD"});
    });

 	function select_team(){
 		if(document.getElementById("team").value==""){
	 		$.ajax({
	            type: 'POST',
	            data: {member: document.getElementById("member").value},
	            url: 'include/select_team.php',
	            success: function(data) {document.getElementById("team").value=data}
	        });
 		}
 		
 	}

 	 $(document).ready(function() {
        $('#sprint_year').change(function() {
            $.ajax({
                type: 'POST',
                data: {sprint_year: $(this).val()},
                url: 'include/select_month.php',
                success: function(data) {$('#sprint_month').html(data);}
            });
             $.ajax({
                type: 'POST',
                data: {sprint_month:1,sprint_year: $(this).val()},
                url: 'include/select_week.php',
                success: function(data) {$('#sprint').html(data);}
            });
       		return false;
        });
 			

        $('#sprint_month').change(function() {
            $.ajax({
                type: 'POST',
                data: {sprint_month: $(this).val(),sprint_year: $('#sprint_year').val()},
                url: 'include/select_week.php',
                success: function(data) {$('#sprint').html(data);}
            });
            
       		return false;
        });
    });


 </script>
</head>
<body onload='select_team()'>

<div class="header" >
	
</div>
<div class="menu">
    <br>
<?php 
    $active_covert = empty($_GET['file'])?"active":"";
    if(!empty($_GET['file'])){
        $active_editmember = ($_GET['file']=="edit_member")?"active":""; 
        $active_editmember = ($_GET['file']=="edit_member")?"active":""; 
        $active_listworksheet = ($_GET['file']=="list_worksheet")?"active":""; 
        $active_card_total = ($_GET['file']=="detail" AND $_GET['file_id']=="total")?"active":""; 

    }
    
?>
	<center><div class="head_menu">ส่วนการจัดการ<div></center>
	<div class="list-group">
	<a href="index.php" class="list-group-item <?php echo "$active_covert";  ?>"><font size='2'><img src='img/upload.png' width="25" height="25" style='margin-top:-5px'> แปลงไฟล์ Json </a></font>
	<a href="index.php?file=edit_member" class="list-group-item <?php echo "$active_editmember";  ?>"><img src='img/people.png' width="25" height="25" style='margin-top:-5px'><font size='2'> แก้ไขรายชื่อ</a></font>
	<a href="index.php?file=list_worksheet" class="list-group-item <?php echo "$active_listworksheet";  ?>"><img src='img/multimedia.png' width="25" height="25" style='margin-top:-5px'><font size='2'> แสดงใบงาน</a></font>
	</div>
	
	<center><div class="head_menu">รายการที่ถูกอัพโหลด</div></center>
	<div class="list-group">
	  <a href="index.php?file=detail&file_id=total" class="list-group-item <?php echo "$active_card_total";  ?>">
	    <b><font style='font-size:12px'><img src='img/multimedia_2.png' width='25' height='25' style='margin-top:-5px'> ดูรายการที่ถูกอัพโหลดทั้งหมด</b>
	  </a>
<?php
	$history=mysql_query("SELECT * FROM history ORDER BY file_id DESC LIMIT 5")or die(mysql_error());
	while(list($file_id,$file_original,$file_json,$name,$date_upload)=mysql_fetch_row($history)){
        if(!empty($_GET['file_id'])){
            $active_card = ($_GET['file']=="detail" AND $_GET['file_id']==$file_id)?"active":""; 
        }else{
            $active_card="";
        }
		echo"<a href='index.php?file=detail&file_id=$file_id&title=$name' class='list-group-item $active_card'><font style='font-size:12px'><img src='img/flag.png' width='25' height='25' style='margin-top:-5px'> <b>$name</b> $date_upload</font></a>";
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
<div style="width: 100%;">
                  <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
                </div>

</body>
</html>