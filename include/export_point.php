<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename='Teamperformance.xls'");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Process Export json</title>
</head>

<body>
<?php
mysql_connect("localhost","root","")or die(mysql_error());
mysql_select_db("history_trello")or die(mysql_error());
mysql_query("SET NAMES utf8");

echo "<table border='1' cellspacing='0'>";
    echo "<tr>";
        echo "<th rowspan='2' ></th>";
    	echo "<th colspan='2' ><center>Total</center></th>";
        $sql_project = "SELECT project_id FROM report WHERE file_id ='$_GET[file_id]' GROUP BY project_id";
		$queryproject = mysql_query( $sql_project) or die (mysql_error());
		while (list($project_id) = mysql_fetch_row($queryproject)) {
			echo "<th colspan='3'><center>$project_id</center></th>" ;
    	}			
    echo "</tr>";
    echo "<tr>";
    	echo "<th><center>Assign</center></th>";
    	echo "<th><center>Done</center></th>";
	    $colspan = 3;
		$numproject = mysql_num_rows($queryproject);
		for ($i=0; $i < $numproject ; $i++ ) { 
		 	echo "<th><center>Assign</center></th>";
        	echo "<th><center><center>Done</center></th>";
        	echo "<th><center>คงเหลือ</center></th>";
    		$colspan +=3 ;
		}
        $sql_total_ass = "SELECT project_id FROM report WHERE file_id ='$_GET[file_id]' ";
		$countpro = mysql_query($sql_total_ass) or die(mysql_error());
		$numjob = mysql_num_rows($countpro);
   	echo "</tr>";	
  	echo "<tr>";
   		echo "<td colspan='$colspan' style='background:#CC66FF'><b><font color='black'>ESD</font></b></td>";
   	echo "</tr>";
    echo "<tr>";
   		echo "<td>Nm of Prj Assignments</td>";
   		echo "<td></td>";	
   		echo "<td></td>";	
		for($i=0;$i<$numproject;$i++){
			echo "<td></td>";
			echo "<td></td>";
        	echo "<td></td>";
   		}
   	echo "</tr>";
    echo "<tr>";
        echo "<td>Nm of Job Assignment</td>";
        $sql_num_total = "SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND MID(status_card,1,3)!='Bla' AND (member_fullname!=''OR team!='')";
        $sql_num_done = "SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND MID(status_card,1,3)='Don' AND (member_fullname!=''OR team!='') ";
        $query_num_total = mysql_query($sql_num_total) or die(mysql_error());
        $query_num_done = mysql_query($sql_num_done) or die(mysql_error());
        list($num_total,$sum_total) = mysql_fetch_row($query_num_total);
        list($num_done,$sum_done) = mysql_fetch_row($query_num_done);   
    	echo "<td>$num_total</td>";
    	echo "<td>$num_done</td>";
        $sql_project = "SELECT project_id FROM report WHERE file_id ='$_GET[file_id]' GROUP BY project_id";
		$queryproject = mysql_query($sql_project) or die (mysql_error());
        $array_sum_total =array();
        $array_sum_done =array();
        $array_sum_result =array();
		while (list($project_id) = mysql_fetch_row($queryproject)) {
            $sql_num_project_total = "SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND project_id='$project_id' AND MID(status_card,1,3)!='Bla' AND (member_fullname!=''OR team!='')";
            $sql_num_project_done = "SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND project_id='$project_id' AND MID(status_card,1,3)='Don' AND (member_fullname!=''OR team!='') ";
			$query_num_project_total = mysql_query($sql_num_project_total) or die(mysql_error());
            $query_num_project_done = mysql_query($sql_num_project_done) or die(mysql_error());
			list($num_project_total,$sum_project_total)=mysql_fetch_row($query_num_project_total);
			list($num_project_done,$sum_project_done)=mysql_fetch_row($query_num_project_done);
			$result = $num_project_total - $num_project_done;
			echo "<td> $num_project_total </td>";
   			echo "<td> $num_project_done </td>";
   			echo "<td> $result </td>";
            $sum_project_total=empty($sum_project_total)?0:$sum_project_total;
            $sum_project_done=empty($sum_project_done)?0:$sum_project_done;
            array_push($array_sum_total,$sum_project_total);
            array_push($array_sum_done,$sum_project_done);
            array_push($array_sum_result,($sum_project_total-$sum_project_done));
    	}	
   	echo "</tr>";
 	echo "<tr>";
   		echo "<td>Fibonancy points</td>";
   		echo "<td>$sum_total</td>";
   		echo "<td>$sum_done</td>";
        for($i=0 ;$i<count($array_sum_result);$i++) {
            echo "<td> $array_sum_total[$i] </td>";
            echo "<td> $array_sum_done[$i] </td>";
            echo "<td> $array_sum_result[$i] </td>";
        }		
  	echo "</tr>";
	$numteam = mysql_query("SELECT team FROM report GROUP BY team  ") or die(mysql_error());
	while (list($team) = mysql_fetch_row($numteam)) {
    	if(empty($team)){
            $query_member = mysql_query("SELECT project_id,point_card,MID(status_card,1,3),member_fullname FROM report WHERE file_id ='$_GET[file_id]' AND team='' AND member_fullname!='' AND MID(status_card,1,3)!='Bla' ");
            while(list($project,$point,$status,$member)=mysql_fetch_row($query_member)){
             $query_team = mysql_query("SELECT team FROM members WHERE member_fullname = '$member' ")or die(mysql_error());
             list($search_team)=mysql_fetch_row($query_team);

             $array_push_team[] = array('team'=>$search_team,'project'=>$project,'point'=>$point,'status'=>$status);
            }
        }else{
            switch ($team) {
                case 'C&#39;Kirk': $color = "#FFCCFF"; break;
                case 'Chekov': $color = "#66FF99"; break;
                case 'Scotty': $color = "#FFCC33"; break;
                case 'Spok': $color = "#FFFF99"; break;
                case 'Sulu': $color = "#66CCFF"; break;
            }
   	echo "<tr style = 'background-color:$color'>";
    	echo "<td colspan='$colspan'><b><font color=black>$team </font></b></td>";
    echo "</tr>";
    echo "<tr>";
    	echo "<td>Nm of Prj Assignments</td>";
        echo "<td></td>";
        echo "<td></td>";
    $queryproject = mysql_query( $sql_project) or die (mysql_error());
    while (list($project_id) = mysql_fetch_row($queryproject)) {
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
    }
   	echo "</tr>";


    echo "<tr>";
    	echo "<td>Nm of Job Assignment</td>";
    $sql_count_team_total = "SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]'  AND team='$team' AND MID(status_card,1,3)!='bla'";
    $sql_count_team_done ="SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]'  AND team='$team' AND MID(status_card,1,3)='Don'";
    $querypoint_total = mysql_query($sql_count_team_total) or die(mysql_error());
    $querypoint_done = mysql_query($sql_count_team_done) or die(mysql_error());
    list($count_total,$sum_total)=mysql_fetch_row($querypoint_total);
    list($count_done,$sum_done)=mysql_fetch_row($querypoint_done);

    $num_not_team_total =0;
    $num_not_team_done =0;
    $sum_not_team_total=0;
    $sum_not_team_done=0;
    foreach ($array_push_team as $value) {
        $pass_team =0;
        if($value['team']==$team){
            $pass_team=1;
        }
        if($pass_team==1){
            $pass_done=0;
            $num_not_team_total++;
            $sum_not_team_total+=$value['point'];
            if($value['status']=="Don"){
                $num_not_team_done++;
                $pass_done=1;
            }
            if($pass_done==1){
                $sum_not_team_done+=$value['point'];
            }
        }
    }

        echo "<td>".($count_total+$num_not_team_total)."</td>";
        echo "<td>".($count_done+$num_not_team_done)."</td>";
    $queryproject = mysql_query($sql_project) or die (mysql_error());
    while (list($project_id) = mysql_fetch_row($queryproject)){
            $querypoint_total = mysql_query("SELECT COUNT(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND project_id ='$project_id'  AND team='$team' AND MID(status_card,1,3)!='bla' ") or die(mysql_error());
            list($count_total)=mysql_fetch_row($querypoint_total);
            $querypoint_done = mysql_query("SELECT COUNT(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND project_id ='$project_id' AND team='$team' AND MID(status_card,1,3)='Don' ") or die(mysql_error());
            list($count_done)=mysql_fetch_row($querypoint_done);

            $num_not_project_total =0;
            $num_not_project_done =0;
            foreach ($array_push_team as $value) {
                $pass_team =0;
                $pass_project =0;
                if($value['team']==$team){
                    $pass_team=1;
                }
                if($value['project']==$project_id){
                    $pass_project=1;
                }
                if($pass_team==1&&$pass_project==1){
                    $num_not_project_total++;
                    if($value['status']=="Don"){
                        $num_not_project_done++;
                    }
                }
            }

            $result = (($count_total+$num_not_project_total)-($count_done+$num_not_project_done));
            echo "<td>".($count_total+$num_not_project_total)."</td>";
            echo "<td>".($count_done+$num_not_project_done)."</td>";
            echo "<td>".($result)."</td>";
        }

   	echo "</tr>";
    echo "<tr>";
    	echo "<td>Fibonancy points</td>";
        echo "<td>".($sum_total+$sum_not_team_total)."</td>";
        if(empty($sum_done)){
            $sum_done = 0;
        }
        echo "<td>".($sum_done+$sum_not_team_done)."</td>";
        $queryproject = mysql_query("SELECT project_id FROM report WHERE file_id ='$_GET[file_id]' GROUP BY project_id") or die (mysql_error());
        while (list($project_id) = mysql_fetch_row($queryproject)){
            $querypoint_total = mysql_query("SELECT SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND project_id ='$project_id'  AND team='$team' AND MID(status_card,1,3)!='bla'") or die(mysql_error());
            list($sum_total)=mysql_fetch_row($querypoint_total);
            $querypoint_done = mysql_query("SELECT SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND project_id ='$project_id' AND team='$team' AND MID(status_card,1,3)='Don' ") or die(mysql_error());
            list($sum_done)=mysql_fetch_row($querypoint_done);
            if(empty($sum_done)){
                $sum_done=0;
            }
            if(empty($sum_total)){
                $sum_total=0;
            }

            $sum_not_team_total=0;
            $sum_not_team_done=0;
            foreach ($array_push_team as $value) {
                $pass_team =0;
                $pass_project =0;
                if($value['team']==$team){
                    $pass_team=1;
                }
                if($value['project']==$project_id){
                    $pass_project=1;
                }
                if($pass_team==1&&$pass_project==1){
                    $pass_done=0;
                    $sum_not_team_total+=$value['point'];
                    if($value['status']=="Don"){
                        $pass_done=1;
                    }
                    if($pass_done==1){
                        $sum_not_team_done+=$value['point'];
                    }
                }
            }
            $result = (($sum_total+$sum_not_team_total) - ($sum_done+$sum_not_team_done));
            echo "<td>".($sum_total+$sum_not_team_total)."</td>";
            echo "<td>".($sum_done+$sum_not_team_done)."</td>";
            echo "<td>$result</td>";
        }
   	echo "</tr>";
		}
 	} 
echo "</table>";

echo "<br><br>";
echo "<table border='1' cellspacing='0'>";
    echo "<thead>";
        echo "<tr>";
            echo "<th rowspan='2' style='padding-bottom:27px;'><center>รายบุคคล/สถานะ</th>";
            echo "<th colspan='2'><center>Checkin</th>";
            echo "<th colspan='2'><center>Doing</th>";
            echo "<th colspan='2'><center>Testing</th>";
            echo "<th colspan='2'><center>Done</th>";
        echo "</tr>";
         echo "<tr>";
            echo "<th>ใบงาน</th>";
            echo "<th>คะแนน</th>";
            echo "<th>ใบงาน</th>";
            echo "<th>คะแนน</th>";
            echo "<th>ใบงาน</th>";
            echo "<th>คะแนน</th>";
            echo "<th>ใบงาน</th>";
            echo "<th>คะแนน</th>";
        echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    $query_team_card = mysql_query("SELECT team FROM report WHERE file_id ='$_GET[file_id]' AND team!=''  GROUP BY team ORDER BY team ASC ")or die(mysql_error());
    while(list($team)=mysql_fetch_row($query_team_card)){
        $count_total_checkin=0;
        $sum_total_checkin=0;
        $count_total_doing=0;
        $sum_total_doing=0;
        $count_total_test=0;
        $sum_total_test=0;
        $count_total_done=0;
        $sum_total_done=0;
        echo "<tr>";
            switch ($team) {
                case 'C&#39;Kirk': $color = "#FFCCFF"; break;
                case 'Chekov': $color = "#66FF99"; break;
                case 'Scotty': $color = "#FFCC33"; break;
                case 'Spok': $color = "#FFFF99"; break;
                case 'Sulu': $color = "#66CCFF"; break;
            }
            echo "<td colspan='9' style='background:$color'><b>$team</b></td>";
        echo "</tr>";
        $query_member = mysql_query("SELECT member_fullname FROM report WHERE file_id ='$_GET[file_id]' AND team ='$team' AND MID(status_card,1,3)!='bla' GROUP BY member_fullname ORDER BY member_fullname DESC")or die(mysql_error());
        $array_member =array();
        while(list($member)=mysql_fetch_row($query_member)){
            echo "<tr>";
            if(!empty($member)){
                echo "<td>$member</td>";
                $query_checkin_first = mysql_query("SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND member_fullname='$member' AND MID(status_card,1,3)='Che' ")or die(mysql_error());
                list($count_checkin_first,$sum_checkin_first)=mysql_fetch_row($query_checkin_first);
                echo "<td>$count_checkin_first </td>";
                $sum_checkin_first=empty($sum_checkin_first)?0:$sum_checkin_first;
                echo "<td>$sum_checkin_first </td>";
                $count_total_checkin+=$count_checkin_first;
                $sum_total_checkin+=$sum_checkin_first;
                $query_doing_first = mysql_query("SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND member_fullname='$member' AND MID(status_card,1,3)='Doi' ")or die(mysql_error());
                list($count_doing_first,$sum_doing_first)=mysql_fetch_row($query_doing_first);
                echo "<td>$count_doing_first </td>";
                $sum_doing_first=empty($sum_doing_first)?0:$sum_doing_first;
                echo "<td>$sum_doing_first </td>";
                $count_total_doing+=$count_doing_first;
                $sum_total_doing+=$sum_doing_first;
                $query_test_first = mysql_query("SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND member_fullname='$member' AND MID(status_card,1,3)='Tes' ")or die(mysql_error());
                list($count_test_first,$sum_test_first)=mysql_fetch_row($query_test_first);
                echo "<td>$count_test_first </td>";
                $sum_test_first=empty($sum_test_first)?0:$sum_test_first;
                echo "<td>$sum_test_first </td>";
                $count_total_test+=$count_test_first;
                $sum_total_test+=$sum_test_first;
                $query_done_first = mysql_query("SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND member_fullname='$member' AND MID(status_card,1,3)='Don' ")or die(mysql_error());
                list($count_done_first,$sum_done_first)=mysql_fetch_row($query_done_first);
                echo "<td>$count_done_first </td>";
                $sum_done_first=empty($sum_done_first)?0:$sum_done_first;
                echo "<td>$sum_done_first </td>";
                $count_total_done+=$count_done_first;
                $sum_total_done+=$sum_done_first;
                array_push($array_member, $member);
            }else{
                $quer_member_not_team = mysql_query("SELECT member_fullname FROM report WHERE file_id ='$_GET[file_id]' AND team ='' AND member_fullname!='' AND MID(status_card,1,3)!='bla' GROUP BY member_fullname ")or die(mysql_error());
                while(list($member_not_team)=mysql_fetch_row($quer_member_not_team)){
                    if(!in_array($member_not_team, $array_member)){
                        $query_team = mysql_query("SELECT team FROM members WHERE member_fullname ='$member_not_team'")or die(mysql_error());
                        list($team_of_member)=mysql_fetch_row( $query_team);
                        if($team_of_member==$team){
                            echo "<tr>";
                                echo "<td>$member_not_team</td>";
                                $query_checkin_sec = mysql_query("SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND member_fullname='$member_not_team' AND team='' AND MID(status_card,1,3)='Che' ")or die(mysql_error());
                                list($count_checkin_sec,$sum_checkin_sec)=mysql_fetch_row($query_checkin_sec);
                                echo "<td>$count_checkin_sec </td>";
                                $sum_checkin_sec=empty($sum_checkin_sec)?0:$sum_checkin_sec;
                                echo "<td>$sum_checkin_sec </td>";
                                $count_total_checkin+=$count_checkin_sec;
                                $sum_total_checkin+=$sum_checkin_sec;
                                $query_doing_sec = mysql_query("SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND member_fullname='$member_not_team' AND team='' AND MID(status_card,1,3)='Doi' ")or die(mysql_error());
                                list($count_doing_sec,$sum_doing_sec)=mysql_fetch_row($query_doing_sec);
                                echo "<td>$count_doing_sec </td>";
                                $sum_doing_sec=empty($sum_doing_sec)?0:$sum_doing_sec;
                                echo "<td>$sum_doing_sec </td>";
                                $count_total_doing+=$count_doing_sec;
                                $sum_total_doing+=$sum_doing_sec;
                                $query_test_sec = mysql_query("SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND member_fullname='$member_not_team' AND team='' AND MID(status_card,1,3)='Tes' ")or die(mysql_error());
                                list($count_test_sec,$sum_test_sec)=mysql_fetch_row($query_test_sec);
                                echo "<td>$count_test_sec </td>";
                                $sum_test_sec=empty($sum_test_sec)?0:$sum_test_sec;
                                echo "<td>$sum_test_sec </td>";
                                $count_total_test+=$count_test_sec;
                                $sum_total_test+=$sum_test_sec;
                                $query_done_sec = mysql_query("SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND member_fullname='$member_not_team' AND team='' AND MID(status_card,1,3)='Don' ")or die(mysql_error());
                                list($count_done_sec,$sum_done_sec)=mysql_fetch_row($query_done_sec);
                                echo "<td>$count_done_sec </td>";
                                $sum_done_sec=empty($sum_done_sec)?0:$sum_done_sec;
                                echo "<td>$sum_done_sec </td>";
                                $count_total_done+=$count_done_sec;
                                $sum_total_done+=$sum_done_sec;

                            echo "</tr>";
                            array_push($array_member,$member_not_team);
                        }
                    }
                }
                $quer_team_not_member = mysql_query("SELECT team FROM report WHERE file_id ='$_GET[file_id]' AND team !='' AND member_fullname='' AND MID(status_card,1,3)!='bla'  GROUP BY team ")or die(mysql_error());
                while(list($team_not_member)=mysql_fetch_row($quer_team_not_member)){
                    if($team_not_member==$team){
                        echo "<tr>";
                            echo "<td>Unkwon</td>";
                            $query_checkin_first = mysql_query("SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND team='$team_not_member' AND member_fullname='' AND MID(status_card,1,3)='Che' ")or die(mysql_error());
                                list($count_checkin_notmember,$sum_checkin_notmember)=mysql_fetch_row($query_checkin_first);
                                echo "<td>$count_checkin_notmember </td>";
                                $sum_checkin_notmember=empty($sum_checkin_notmember)?0:$sum_checkin_notmember;
                                echo "<td>$sum_checkin_notmember </td>";
                                $count_total_checkin+=$count_checkin_notmember;
                                $sum_total_checkin+=$sum_checkin_notmember;
                                $query_doing_first = mysql_query("SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND team='$team_not_member' AND member_fullname='' AND MID(status_card,1,3)='Doi' ")or die(mysql_error());
                                list($count_doing_notmember,$sum_doing_notmember)=mysql_fetch_row($query_doing_first);
                                echo "<td>$count_doing_notmember </td>";
                                $sum_doing_notmember=empty($sum_doing_notmember)?0:$sum_doing_notmember;
                                echo "<td>$sum_doing_notmember </td>";
                                $count_total_doing+=$count_doing_notmember;
                                $sum_total_doing+=$sum_doing_notmember;
                                $query_test_first = mysql_query("SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND team='$team_not_member' AND member_fullname='' AND MID(status_card,1,3)='Tes' ")or die(mysql_error());
                                list($count_test_notmember,$sum_test_notmember)=mysql_fetch_row($query_test_first);
                                echo "<td>$count_test_notmember </td>";
                                $sum_test_notmember=empty($sum_test_notmember)?0:$sum_test_notmember;
                                echo "<td>$sum_test_notmember </td>";
                                $count_total_test+=$count_test_notmember;
                                $sum_total_test+=$sum_test_notmember;
                                $query_done_first = mysql_query("SELECT COUNT(point_card),SUM(point_card) FROM report WHERE file_id ='$_GET[file_id]' AND team='$team_not_member' AND member_fullname='' AND MID(status_card,1,3)='Don' ")or die(mysql_error());
                                list($count_done_notmember,$sum_done_notmember)=mysql_fetch_row($query_done_first);
                                echo "<td>$count_done_notmember </td>";
                                $sum_done_notmember=empty($sum_done_notmember)?0:$sum_done_notmember;
                                echo "<td>$sum_done_notmember </td>";
                                $count_total_done+=$count_done_notmember;
                                $sum_total_done+=$sum_done_notmember;
                        echo "</tr>";

                    }
                }
            }

            echo "</tr>";
        }
            echo "<tr>";
                echo "<td align='right'><b>รวม</b></td>";
                echo "<td>$count_total_checkin</td>";
                echo "<td>$sum_total_checkin</td>";
                echo "<td>$count_total_doing</td>";
                echo "<td>$sum_total_doing</td>";
                echo "<td>$count_total_test</td>";
                echo "<td>$sum_total_test</td>";
                echo "<td>$count_total_done</td>";
                echo "<td>$sum_total_done</td>";
            echo "</tr>";
        
    }

    echo "</tbody>";
echo "</table>";
	
		
?>
<div></div>
</body>
</html>