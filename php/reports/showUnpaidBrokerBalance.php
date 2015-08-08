<?php
$title = "Broker";
$subtitle = "Unpaid Balance";
$type = $_GET['type'];
include_once '../report_header.php';
include '../datapack-functions/datapack.php';

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
		<img src='/mfi/img/logo2print.gif' width="140" height="100" />
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

$tableHolder = "";
$ticketTotaled = 0;
$reportedTotaled = 0;
$paidTotaled = 0;

$totalDebt = 0;

$brokersArr = array();
$reportsArr = array();

$reportsQuery = "
	SELECT
		*
	FROM
		report
		JOIN broker using (brokerId)
		LEFT JOIN driver ON (driver.driverId = report.reportType)
	WHERE
		reportDate BETWEEN '$fromDate' AND '$toDate'
	ORDER BY
		reportEndDate desc
";
//echo $brokersQuery;
$reports = mysql_query($reportsQuery, $conexion);
while($report = mysql_fetch_assoc($reports)){
	
	//$reportBalance = getReportBalance($report['reportId'], $conexion);
	$reportTotal = getReportTotal($report['reportId'], $conexion);
	$paidTotal = getReportPaid($report['reportId'], $conexion);
	//echo $reportTotal." -- ".$paidTotal;
	$reportBalance = decimalPad($reportTotal - $paidTotal);
	if($reportBalance > 0){
		$tableHolder.= "<tr>";
			$tableHolder.= "<td>".to_MDY($report['reportStartDate'])."</td>";
			$tableHolder.= "<td>".to_MDY($report['reportEndDate'])."</td>";
			$tableHolder.= "<td>".($report['brokerId']==64?"<strong>MFI</strong> ".$report['driverLastName'].", ".$report['driverFirstName']:$report['brokerName'])."</td>";
			$tableHolder.= "<td>".$report['reportId']."</td>";
			$tableHolder.= "<td align='right'>".decimalPad($reportTotal)."</td>";
			$tableHolder.= "<td align='right'>".decimalPad($reportBalance)."</td>";
		$tableHolder.= "</tr>\n";
		$totalDebt+=$reportBalance;
	}else continue;
	
}
?>
	<tr>
		<td colspan='5'></td>
		<td align='right'><? echo decimalPad($totalDebt);?></td>
	</tr>
	<tr>
		<th colspan='2' >Dates</th>
		<th >Broker</th>
		<th >Report</th>
		<th >Reported</th>
		<th >Balance</th>
	</tr>

<?php

echo $tableHolder;
?>
</table>

</body>
</html>
