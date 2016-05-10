<?php
require ('include/function.php');
require_once('mpdf/mpdf.php');
connect_db();
ob_start();
?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">

<style type="text/css">
<!--
@page rotated { size: landscape; }
.style1 {
	font-family: "TH SarabunPSK";
	font-size: 18pt;
	font-weight: bold;
}
.style2 {
	font-family: "TH SarabunPSK";
	font-size: 16pt;
	font-weight: bold;
}
.style3 {
	font-family: "TH SarabunPSK";
	font-size: 16pt;
	
}
.style5 {cursor: hand; font-weight: normal; color: #000000;}
.style9 {font-family: Tahoma; font-size: 12px; }
.style11 {font-size: 12px}
.style13 {font-size: 9}
.style16 {font-size: 9; font-weight: bold; }
.style17 {font-size: 12px; font-weight: bold; }
-->
</style>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
</head>
<body>
<div class=Section2>
<table width="704" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
 
   <?php //ดึงข้อมูล ปี และ ไตรมาส จากค่า POST
$yearid = " SELECT * 
FROM tbbudgetyear where BudgetyearID = ".$_POST['year']."";
$result=mysql_query($yearid);
$year = 0;
$year1 = 0 ;
while ($r=mysql_fetch_array($result)) 
{	

		$year =$r['Budgetyear'];
		$year1 = $year - 1 ;
 
}	 
$year01 = (isset($_POST['year'])) ? $_POST['year'] : '';
?>
    <td width="291" align="center"><span class="style2">รายงานการใช้งบประมาณแผ่นดิน ประจำปีงบประมาณ 
      <?php echo $year; ?> (ต.ค.<?php echo $year1; ?>-ก.ย.<?php echo $year; ?>)</span></td>
 

  </tr>
  <tr>
    <td height="27" align="center"><span class="style2">มทร.ล้านนา ภาคพายัพ (จอมทอง)    </span></td>
  </tr>
  <tr>
    <td height="25" align="center"><span class="style2">มหาวิทยาลัยเทคโนโลยีราชมงคลล้านนา ภาคพายัพ เชียงใหม่</span></td>
  </tr>
</table>
<table width="200" border="0" align="center">
  <tbody>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
  </tbody>
</table>
<table bordercolor="#424242" width="1141" height="78" border="1"  align="center" cellpadding="0" cellspacing="0" class="style3">
  <tr align="center">
    <td width="44" height="23" align="center" bgcolor="#D5D5D5"><strong>ลำดับ</strong></td>
    <td width="178" align="center" bgcolor="#D5D5D5"><strong>งบประมาณประจำปี
      <?php  $year; ?>
    </strong></td>
    <td width="123" align="center" bgcolor="#D5D5D5"><strong>งบที่ได้รับจัดสรร</strong></td>
    <td width="155" align="center" bgcolor="#D5D5D5"><strong>เงินคงเหลือในปัจจุบัน</strong></td>
    <td width="139" align="center" bgcolor="#D5D5D5"><strong>ไตรมาสที่ 1</strong></td>
    <td width="114" align="center" bgcolor="#D5D5D5"><strong>ไตรมาสที่ 2</strong></td>
    <td width="103" align="center" bgcolor="#D5D5D5"><strong>ไตรมาสที่ 3</strong></td>
    <td width="104" align="center" bgcolor="#D5D5D5"><strong>ไตรมาสที่ 4</strong></td>
    <td width="161" align="center" bgcolor="#D5D5D5"><strong>หมายเหตุ</strong></td>
    </tr>
   
    
<?php
$sql11 =  "select * from tbbudgetyear where  BudgetyearID = ".$_POST['year']." "; 
$objQuery11 = mysql_query($sql11);
while($row11 = mysql_fetch_array($objQuery11)) 

{
	$nest = $row11['Budgetyear'];
		}
	?>
    <?php
$yeareng  = $nest - 543 ; 
$yeareng2 = $yeareng-1;


$qr="SELECT tbclearbill.BudgettypeID,tbbudgettype.Budgettype AS cat, tbbudgetcategory.Budgetcategoryamount AS ton,
tbbudgetcategory.Budgetcategoryamount
-
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng2-10-1' AND '$yeareng2-12-31') )
-
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng-01-1' AND '$yeareng-03-31') )
-
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng-04-1' AND  '$yeareng-06-30') )
-
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng-07-1' AND '$yeareng2-09-30')) AS de,

SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng2-10-1' AND   '$yeareng2-12-31'  )) AS t1,
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng-01-1' AND   '$yeareng-03-31' )) AS t2,   
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng-04-1' AND   '$yeareng-06-30' )) AS t3,   
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng-07-1' AND   '$yeareng-09-30' )) AS t4
FROM  tbclearbill,tbbudgetcategory,tbbudgettype,tbbudgetyear
where tbbudgetcategory.BudgettypeID = tbclearbill.BudgettypeID AND tbbudgetcategory.BudgettypeID = tbbudgettype.BudgettypeID AND
 tbclearbill.BudgetyearID = tbbudgetcategory.BudgetyearID AND tbclearbill.BudgetyearID = ".$_POST['year']."
GROUP by tbclearbill.BudgettypeID";
$qr1 = mysql_query($qr);
$i = 1;
while($rerow = mysql_fetch_array($qr1)) 
{
	?>
  <tr>
    <td height="22" align="center"><?php echo $i;?></td>
    <td align="right" class="style3"><?php echo $rerow['cat'];?></td>
    <td align="right" class="style3"><?php echo number_format($rerow['ton'],2); ?></td>
    <td align="right" class="style3"><?php echo number_format($rerow['de'],2); ?></td>
    <td align="right" class="style3"><?php echo number_format($rerow['t1'],2); ?></td>
    <td align="right" class="style3"><?php echo number_format($rerow['t2'],2); ?></td>
    <td align="right" class="style3"><?php echo number_format($rerow['t3'],2); ?></td>
    <td align="right" class="style3"><?php echo number_format($rerow['t4'],2); ?></td>
    <td align="center" class="style3"></td>
    </tr>
    
    <?php  $i++; } ?>
    <?php
$sql11 =  "select * from tbbudgetyear where  BudgetyearID = ".$_POST['year']." "; 
$objQuery11 = mysql_query($sql11);
while($row11 = mysql_fetch_array($objQuery11)) 

{
	$nest = $row11['Budgetyear'];
		}
	?>
    <?php
$yeareng  = $nest - 543 ; 
$yeareng2 = $yeareng-1;


$qr="SELECT tbclearbill.BudgettypeID,tbbudgettype.Budgettype AS cat, tbbudgetcategory.Budgetcategoryamount AS ton,
tbbudgetcategory.Budgetcategoryamount
-
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng2-10-1' AND '$yeareng2-12-31') )
-
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng-01-1' AND '$yeareng-03-31') )
-
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng-04-1' AND  '$yeareng-06-30') )
-
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng-07-1' AND '$yeareng2-09-30')) AS de,

SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng2-10-1' AND   '$yeareng2-12-31'  )) AS t1,
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng-01-1' AND   '$yeareng-03-31' )) AS t2,   
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng-04-1' AND   '$yeareng-06-30' )) AS t3,   
SUM( Clearbillpayer * ( Clearbilldate BETWEEN '$yeareng-07-1' AND   '$yeareng-09-30' )) AS t4
FROM  tbclearbill,tbbudgetcategory,tbbudgettype,tbbudgetyear
where tbbudgetcategory.BudgettypeID = tbclearbill.BudgettypeID AND tbbudgetcategory.BudgettypeID = tbbudgettype.BudgettypeID AND
 tbclearbill.BudgetyearID = tbbudgetcategory.BudgetyearID AND tbclearbill.BudgetyearID = ".$_POST['year']."
GROUP by tbclearbill.BudgettypeID";
$qr1 = mysql_query($qr);
$total = 0 ;
$total1 = 0 ;
$total2 = 0 ;
$total3 = 0 ;
$total4 = 0 ;
$total5 = 0 ;
while($rerow = mysql_fetch_array($qr1)) 
{
   				$total += $rerow['ton'] ;
				$total1 += $rerow['de'] ;
				$total2 += $rerow['t1'] ;
				$total3 += $rerow['t2'] ;
				$total4 += $rerow['t3'] ;
				$total5 += $rerow['t4'] ;
}
?>
    
    
  <tr>
    <td height="23">&nbsp;</td>
    <td align="center" class="style3"><strong>รวม</strong></td>
    <td align="right" class="style3"><strong>
      <?php echo number_format($total,2); ?>
    </strong></td>
    <td align="right" class="style3"><strong>
      <?php echo number_format($total1,2); ?>
    </strong></td>
    <td align="right" class="style3"><strong>
      <?php echo number_format($total2,2); ?>
    </strong></td>
    <td align="right" class="style3"><strong>
      <?php echo number_format($total3,2); ?>
    </strong></td>
    <td align="right" class="style3"><strong>
      <?php echo number_format($total4,2); ?>
    </strong></td>
  
  	<td align="right" class="style3"><strong>
  	  <?php echo number_format($total5,2); ?>
  	</strong></td>
    


    <td class="style3">&nbsp;</td>
    </tr>
</table>
<table width="200" border="0">
  <tbody>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>

</div>
</body>
</html>
<?Php
$html = ob_get_contents();
ob_end_clean();
$pdf = new mPDF('th', 'A4', '0', 'THSaraban');
$pdf->SetAutoFont();
$pdf->SetDisplayMode('fullpage');
$pdf->WriteHTML($html, 2);
$pdf->Output();
?>     
ดาวโหลดรายงานในรูปแบบ PDF <a href="MyPDF/MyPDF.pdf">คลิกที่นี้</a>
