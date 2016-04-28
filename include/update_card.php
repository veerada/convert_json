<?php
if($_POST['member']=="nomember"||empty($_POST['member'])){
	$_POST['member']='';
}	
if($_POST['team']=='ไม่พบข้อมูล'||empty($_POST['team'])){
	$_POST['team']='';
}
$_POST['team'] = str_replace("'", "&#39;", $_POST['team']);
	mysql_query("UPDATE report SET project_id='$_POST[pro]',tor_id='$_POST[tor]',assign_id='$_POST[ass]',component='$_POST[component]',status_card='$_POST[status]',member_fullname='$_POST[member]',team='$_POST[team]' WHERE file_id='$_POST[file_id]' AND line_id='$_POST[number]'")or die(mysql_error());
	//echo "UPDATE report SET project_id='$_POST[pro]',tor_id='$_POST[tor]',assign_id='$_POST[ass]',component='$_POST[component]',status_card='$_POST[status]',member_fullname='$_POST[member]',team='$_POST[team]' WHERE file_id='$_POST[file_id]' AND line_id='$_POST[number]'";
echo "<script>alert('แก้ไขข้อมูลเสร็จเรียบร้อย');window.location='index.php?file=detail&file_id=$_POST[file_id]&title=$_POST[title]'</script>";

?>