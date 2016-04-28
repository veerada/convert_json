<?php
echo "<br>";
echo "<div class='container-fluid'>";
echo "<div class='panel panel-primary'>";
	echo "<div class='panel-heading'><b>แก้ไขเนื้อหาข้อมูล</b></div>";
	echo "<div class='panel-body'>";
			echo "<b>รายระเอียดข้อมูล</b>";
			echo "<br><br>";
			echo "<form action='index.php?file=update_card' method='post'>";
			echo "<table class='table'>";
			$query_card=mysql_query("SELECT * FROM report WHERE file_id='$_GET[file_id]' AND line_id='$_GET[number]'")or die(mysql_error());
			list($file_id,$line_id,$project,$tor,$ass,$component,$point_card,$status_card,$member,$team)=mysql_fetch_row($query_card);
			echo "<input type='hidden' name='file_id' value='$file_id'>";
			echo "<input type='hidden' name='number' value='$line_id'>";
			echo "<input type='hidden' name='title' value='$_GET[title]'>";
			echo "<tr><td ><p align='right'><b>Project ID :</b></td><td> <input class='form-control' type='text' name='pro' value='$project'></td></tr>";
			echo "<tr class='something'><td><p align='right'><b>TOR ID :</b></td><td> <input class='form-control' type='text' name='tor' value='$tor'></td></tr>";
			echo "<tr><td ><p align='right'><b>Assignment ID :</b></td><td > <input class='form-control' type='text' name='ass' value='$ass'></td></tr>";
			echo "<tr><td class='col-md-2'><p align='right'><b>Component :</b></td><td><textarea class='form-control' name='component'> $component </textarea></td></tr>";
			echo "<tr><td><p align='right'><b>Point :</b></td><td class='col-md-10'> $point_card</td></tr>";
			$array_status = array("Checkin","Doing","Tester","Done");
			echo "<tr><td><p align='right'><b>Status :</b></td>";
			echo "<td><select class='form-control' name='status'>";
				foreach ($array_status as $value) {
					if($value==$status_card){
						echo "<option selected='selected' value='$value'>$value</option>";
					}else{
						echo "<option value='$value'>$value</option>";
					}
				}
			echo "</select></td></tr>";
			echo "<tr><td><p align='right'><b>Member :</b></td>";
			echo "<td><select class='form-control' name='member' id='member'>";
			$query_member=mysql_query("SELECT member_fullname,name_ldc FROM members WHERE team!='' ORDER BY name_ldc ASC")or die(mysql_error());
			if(!empty($member)){
				while (list($member_fullname,$name_ldc )=mysql_fetch_row($query_member)){
						if($member == $member_fullname){
						echo "<option value='$member_fullname' selected='selected'>$name_ldc</option>";
						}else{
							echo "<option value='$member_fullname'>$name_ldc</option>";
						}
					}
			}else{
				echo "<option value='nomember' selected='selected'>ไม่พบข้อมูล</option>";
				while (list($member_fullname,$name_ldc )=mysql_fetch_row($query_member)){
						echo "<option value='$member_fullname'>$name_ldc</option>";
				}
			}	
			echo "</select></td></tr>";
			echo "<tr><td><p align='right'><b>Team :</b></td><td> <input class='form-control' type='text' name='team' id='team' value='$team'></td></tr>";
			echo "</table>";
			echo "<center><input class='btn btn-sm btn-success' type='submit' value='แก้ไขข้อมูล' onclick='return confirm(\"ยืนยันการแก้ไขข้อมูล\")'> &nbsp;&nbsp;&nbsp;&nbsp;";
			echo "<a href='index.php?file=detail&file_id=$_GET[file_id]&title=$_GET[title]'><input class='btn btn-sm btn-danger' type='button' value='ยกเลิกการแก้ไข'></center>";
			echo "</form>";
	echo "</div>";
echo "</div>";
echo "</div>";


?>