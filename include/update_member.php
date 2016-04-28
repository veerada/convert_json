<?php

	$fullname =$_POST['member_name'];
	$loc = $_POST['loc_name'];
	$team = $_POST['team'];



	for($i=0;$i<count($fullname);$i++){
		if($loc[$i]!=$fullname[$i]){
			$team[$i] = str_replace("'" , "&#39;" , $team[$i]);
			mysql_query(" UPDATE members SET name_ldc = '$loc[$i]',team='$team[$i]' WHERE member_fullname ='$fullname[$i]'")or die(mysql_error());
		}
	}
	
	echo "<script>alert('แก้ไขรายซื่อเสร็จเรียบร้อย');window.location='index.php?file=edit_member'</script>";
	
?>