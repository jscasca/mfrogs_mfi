<?php
$title = "Broker";
$subtitle = "Payments Report";
$type = $_GET['type'];
include_once '../report_header.php';
include '../datapack-functions/datapack.php';


$broker = $_GET['brokerId'];
$driver = $_GET['driverId'];
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

if($_GET['fromDate']==''){$fromDate='0000-00-00';}
else{$fromDate=to_YMD(mysql_real_escape_string($_GET['fromDate']));}

if($_GET['toDate']==''){$toDate=date("Y-m-d");}
else{$toDate=to_YMD(mysql_real_escape_string($_GET['toDate']));}

?>

<table class="topt" align="center" >
<tr>
	<td width="30%" align="left" >
		<table class="invinfo" width='100%'>
			<caption>Martinez Frogs Inc.</caption>
			<tr><td width='177'><?echo$mfiInfo['addressLine1'];?></td></tr>
			<tr><td><?echo$mfiInfo['addressCity'].", ".$mfiInfo['addressState'].". ".$mfiInfo['addressZip'];?></td></tr>
			<tr><td><? echo "Ph # ".showPhoneNumber($mfiInfo['mfiTel']); ?></td></tr>
			<tr><td><? echo "Fax # ".showPhoneNumber($mfiInfo['mfiFax']); ?></td></tr>
		</table>
	</td>
	<td width="30%" align="center" >
		<img src='/trucking/img/logo2print.gif' width="140" height="100" />
	</td>
	<td width="30%" align="right" >
		<table class="invinfo">
			<tr><th>Date</th><td><? echo to_MDY($mfiInfo['CURDATE()']);?></td></tr>
		</table>
	</td>
</tr>
</table>

<table align="center" class="report" width="100%" cellspacing="0" >
<?php
$globalTotal = 0;
$toPayTotal = 0;
$paidTotal = 0;

$totalPaid = 0;
$totalCheques = 0;

$tableHolder = "";
$reportsQuery = "SELECT SUM(paidchequesAmount) as sumCheques, paidchequeNumber, paidchequesDate FROM paidcheques JOIN report USING (reportId) WHERE brokerId = $broker AND paidchequesDate BETWEEN '$fromDate' AND '$toDate' GROUP BY paidchequeNumber order by paidchequesDate desc";
//echo $reportsQuery;
$reports = mysql_query($reportsQuery, $conexion);
while($reportInfo=mysql_fetch_assoc($reports)){
	//For each report
	
	$tableHolder.= "<tr>\n";
		$tableHolder.= "<td>".$reportInfo['paidchequeNumber']."</td>";
		$tableHolder.= "<td>".to_MDY($reportInfo['paidchequesDate'])."</td>";
		$tableHolder.= "<td align='right'>".decimalPad($reportInfo['sumCheques'])."</td>";
		
		$totalPaid += decimalPad($reportInfo['sumCheques']);
		$totalCheques++;
	
	$tableHolder.= "</tr>\n";
}
echo "<tr><td></td><td>Total Paid</td><td align='right'>".decimalPad($totalPaid)."</td></tr>";
$tableHolder.="<tr><td>$totalCheques cheques</td><td>Total Paid</td><td align='right'>".decimalPad($totalPaid)."</td></tr>";
//echo "<tr><td colspan='6'></td><td>".decimalPad($globalTotal)."</td><td>".decimalPad($globalTotal - $paidTotal)."</td><td>".decimalPad($paidTotal)."</td><td colspan='3'></td></tr>";
?>
<tr>
	<th width="8%" >Cheque</th>
	<th width="8%" >Date</th>
	<th width="8%" >Amount</th>
</tr>
<?
echo $tableHolder;
?>
</table>

</body>
</html>
