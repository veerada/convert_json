<?php
if(!empty($_POST["Submit"])){
	if(!empty($_FILES["import_file"]["name"])){
		$filename = $_FILES["import_file"]["name"];
		$sizefile = $_FILES["import_file"]["size"];
		$type= strrchr($filename,".");
		if($type==".json"||$type==".js"){
			$date_file=(date("Y-m-d"))."_".((date("H"))+6).".".(date("i"));
			$date_upload=(date("Y-m-d"))." ".((date("H"))+6).":".(date("i"));
			$filename_copy =$date_file."_".$filename;
			$type= strrchr($filename,".");
			copy($_FILES["import_file"]["tmp_name"],"json/".$filename_copy);
			$json_url = "json/$filename_copy";
			$json = file_get_contents($json_url);
			$data = json_decode($json, TRUE);
			$lastview_time=substr($data['dateLastView'],11,8);
			$lastview_date=substr($data['dateLastView'],0,10);
			$lastview_time_hour =substr($lastview_time,0,2);
			$lastview_time_minute=substr($lastview_time,3,2);
			$lastview_time_second=substr($lastview_time,6,2);
			$lastview_date_year =substr($lastview_date,0,4);
			$lastview_date_month =substr($lastview_date,5,2);
			$lastview_date_day =substr($lastview_date,8,2);

			$lastview_time_hour+=7;
			if($lastview_time_hour>=24){
				$lastview_time_hour=0;
				$lastview_date_day++;
			}

			$query_month =mysql_query("SELECT day_amount FROM month WHERE month_id='$lastview_date_month'")or die(mysql_error());
			list($day_amount)=mysql_fetch_row($query_month);
			if($lastview_date_year%4==0){
				if($lastview_date_month==2){
					$day_amount=29;
				}
			}else{
				if($lastview_date_month==2){
					$day_amount=28;
				}
			}

			if($lastview_date_day>$day_amount){
				$lastview_date_day=1;
				$lastview_date_month++;
			}

			if($lastview_date_month>12){
				$lastview_date_month=1;
				$lastview_date_year++;
			}
			$filename = str_replace("'", "&#39;", $filename);
			$filename_copy = str_replace("'", "&#39;", $filename_copy);

			
			mysql_query("INSERT INTO history VALUES('','$filename','$filename_copy','$data[name]','$date_upload','$lastview_date_year-$lastview_date_month-$lastview_date_day $lastview_time_hour:$lastview_time_minute:$lastview_time_second')")or die(mysql_error());

			$file = mysql_query("SELECT file_id FROM history ORDER BY file_id DESC")or die(mysql_error());
			list($file_id)=mysql_fetch_row($file);

			$lists = array();
			$lists_closed = array();
			$member_initials = array();
			$member_fullname = array();
			foreach ($data['lists'] as $key => $value) {
					if(empty($value['closed'])){
						$id=$value['id'];
						$lists[$id] = $value['name'];
					}else{
						$id=$value['id'];
						$lists_closed[$id] = $value['name'];
					}
			}
			foreach ($data['members'] as $value) {
					$id=$value['id'];
					$member_fullname[$id] = $value['fullName'];
					$member_initials[$id] = $value['initials'];
			}
			
// ----------------- เพิ่มข้อมูลผู้ใช้จาก trello ไปยัง database ------------------------
			$query_member=mysql_query("SELECT member_fullname FROM members")or die(mysql_error());
			$count_memner_db = mysql_num_rows($query_member);
			if($count_memner_db!=count($member_fullname)){
				if(count($member_fullname)>$count_memner_db){
					$count_insert_member=count($member_fullname)-$count_memner_db;
					$add_member = "INSERT INTO members VALUES";
					$add=1;
					foreach ($member_fullname as $key => $value) {
						$query_member=mysql_query("SELECT member_fullname FROM members WHERE member_fullname='$member_fullname[$key]'")or die(mysql_error());
						list($select_member)=mysql_fetch_row($query_member);
						$initials = $member_initials[$key];
						if($select_member!=$value){
							$add_member.="('','$initials','$value','$value','')";
							$add++;
							if($add<=$count_insert_member){
								$add_member.=",";
							}
						}
					}
					mysql_query($add_member)or die("mysql_error()");
				}			
			}
			

			$cards_name=array();
			$cards_list=array();
			$cards_label=array();
			$cards_member=array();
			foreach ($data['cards'] as $index_header => $index_value) {
				$close = 0;
				foreach ($index_value as $header => $value) {
					if($header=="closed"){
						if(!empty($value)){
							$close=1;
						}
					}
					if($close!=1){			
						if($header=="name"){
							array_push($cards_name,"$value");
						}
						if($header=="idList"){
							if(count($lists_closed)!=0){
								foreach ($lists_closed as $list_closed_id => $list_closed_value) {
									if($value==$list_closed_id){
											array_push($cards_list,"");
									}else{
										foreach ($lists as $list_id => $list_value) {
											if($value==$list_id){
												array_push($cards_list,"$list_value");
											}
										}
									}
								}
							}else{
								foreach ($lists as $list_id => $list_value) {
									if($value==$list_id){
										array_push($cards_list,"$list_value");
									}
								}
							}
						}
						if($header=="labels"){
							if(empty($value[0]['name'])){
								$value[0]['name']="";
							}
							array_push($cards_label,$value[0]['name']);
						}
						if($header=="idMembers"){
							if(empty($value[0])){
								array_push($cards_member,"");
							}else{
								foreach ($member_fullname as $member_id => $member_name) {
									if($value[0]==$member_id){
										array_push($cards_member,"$member_name");
									}
								}
							}
						}
					}
				}
			}

			$insert_report = "INSERT INTO report VALUES ";

			$number=1;
			for($i=0;$i<count($cards_name);$i++){
				
					$str=explode(" ",$cards_name[$i],5);
					$point=str_replace("(","",$str[0]);
					$point =str_replace(")","",$point);

					if(!is_numeric($point)){
						if(empty($str[3])){
							$str[3]="";
						}
						if(empty($str[4])){
							$str[4]="";
						}

						$string3 =$str[3];
						$string4 = $str[4];
						$str[4] = $string3." ".$string4;
						if(empty($str[2])){
							$str[2]="";
						}
						$str[3] = $str[2];
						$str[2] = $str[1]; 
						$str[1] = $point;
						$point="";
					}

					if((substr($str[1], 0, 1))!="#"&&(substr($str[2], 0, 1))!="#"&&(substr($str[3], 0, 1))!="#"){
						$string1 =	$str[1];
						$string2 =	$str[2];
						$string3 =	$str[3];
						$string4 =	$str[4];
						$pro_id = "";
						$tor_id = "";
						$ass_id = "";
						$card_data = $string1." ".$string2." ".$string3." ".$string4;
					}else{
						if((substr($str[1], 0, 1))!="#"){
							$pro_id = $str[2];
							$tor_id = $str[3];
							$ass_id = $str[4];
						}else{
							$pro_id = $str[1];
							$tor_id = $str[2];
							$ass_id = $str[3];
						}

						if(!empty($str[4])){
							$card_data = $str[4];
						}else{
							$card_data ="";
						}

						if(substr($pro_id, 0, 4)=="#TOR"){
						$string1 = $pro_id;
						$string2 = $tor_id;
						$string3 = $ass_id;
						$string4 = $card_data;
						$pro_id = "";
						$tor_id = $string1;
						$ass_id = $string2;
						$card_data = $string3." ".$string4;
						}

						if(substr($ass_id, 0, 4)=="#TOR"){
						$string1 = $ass_id;
						$string2 = $tor_id;
						$ass_id = $string2;
						$tor_id = $string1;
						}

						if(substr($tor_id, 0, 4)!="#TOR"){
						$string1 = $tor_id;
						$string2 = $ass_id;
						$string3 = $card_data; 
						$tor_id = "";
						$ass_id = $string1;
							if($string2 == $string3){
								$card_data = $string2;
							}else{
								$card_data = $string2." ".$string3;
							}
						}
						if(substr($ass_id, 0, 1)!="#"){
							$string1 = $ass_id;
							$string2 = $card_data;
							$ass_id = "";
							$card_data = $string1." ".$string2;

						}

						$split_card_data =explode(" ",$ass_id,2);
						if(count($split_card_data)==2){
							$ass_id = $split_card_data[0];
							$card_data =  $split_card_data[1];
						}
					}
					$cards_label[$i] = str_replace("'" , "&#39;" , $cards_label[$i]);
					$pro_id = substr($pro_id, 1);
					$ass_id = substr($ass_id, 1);
					$tor_id = substr($tor_id, 4);
					if(empty($point)){
						$value_card = $cards_name[$i];
					}else{
						$value_card = substr($cards_name[$i],3);
					}
					if($cards_list[$i]!=""){
					switch (substr($cards_list[$i], 0, 4)) {
						case "Blac": $value_list = "Blacklog";
							break;
						case "Chec": $value_list = "Checkin";
							break;
						case "Doin": $value_list = "Doing";
							break;
						case "Test": $value_list = "Tester";
							break;
						case "DONE"||"Done": $value_list = "Done";
							break;		
						default:
							$value_list = $cards_list[$i];
							break;
					}
					}
					$value_card = str_replace("'", "&#39;", $value_card);
					$insert_report.="('$file_id','$number','$pro_id','$tor_id','$ass_id','$value_card','$point','$value_list','$cards_member[$i]','$cards_label[$i]')";
					if($i<((count($cards_name))-1)){
						$insert_report.=",";
					}
					$number++;
				
			}
			mysql_query($insert_report) or die(mysql_error());
			echo "<script> alert('บันทึกไฟล์เสร็จสิ้น สามารถดูรายละเอียดของไฟล์จากรายการที่ถูดอัพโหลด/'); window.location='index.php?file=detail&file_id=total' </script>";
		}else{
			echo "<script> alert('ระบบสามารถแปลงไฟล์ json ได้เพียงอย่างเดียว'); window.location='index.php' </script>";
		}
	}else{
		echo "<script> alert('กรุณาเลือกไฟล์ก่อนทำการแปลงไฟล์'); window.location='index.php';</script>";
	}

}
?>