<?php
$title = "Report";
$subtitle = "Broker Balance";
$type = $_GET['type'];
include_once '../report_header.php';
include '../datapack-functions/datapack.php';

$broker = $_GET['brokerId'];
$driver = $_GET['driverId'];

if($_GET['startDate']==''){$fromDate='0000-00-00';}
else{$fromDate=to_YMD(mysql_real_escape_string($_GET['startDate']));}

if($_GET['endDate']==''){$toDate=date("Y-m-d");}
else{$toDate=to_YMD(mysql_real_escape_string($_GET['endDate']));}

$brokerInfo = mysql_fetch_assoc(mysql_query("select * from broker where brokerId = $broker",$conexion));

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
<tr>
	<td colspan='3'><hr></td>
</tr>
<tr>
<td colspan='2'>
	<table class="subcontractor">
		<caption><? if($projectInfo['reportType']==0)echo "";else echo"Drivers";?></caption>
		<tr>
			<td><?echo "<strong>".$brokerInfo['brokerName']."</strong> ".($driver!=0 ? " for ".$driverInfo['driverLastName'].", ".$driverInfo['driverFirstName']: "");?></td>
		</tr>
		<tr>
			<td>
				<table class='insurance'>
					<tr>
						<th>Liability Ins Policy No:</th><td><?echo $brokerInfo['brokerInsuranceLiability'];?></td>
						<th>Wc Ins Policy No:</th><td><?echo $brokerInfo['brokerInsuranceWc'];?></td>
					</tr>
					<tr>
						<th>Liability Ins Expiration Date:</th><td><?echo to_MDY($brokerInfo['brokerLbExpire']);?></td>
						<th>WC Ins Expiration Date:</th><td><?echo to_MDY($brokerInfo['brokerWcExpire']);?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</td>
<td>
	<table class="dates">
		<tr>
			<th><strong>Date Issued: </strong></th>
			<td><?echo to_MDY(date("Y-m-d"));?></td>
			<td></td>
		</tr>
	</table>
</td>
</tr>
</table>

<table align="center" class="report" width="100%" cellspacing="0" >
<?php
$queryReports="
SELECT
	*
FROM
	report
	JOIN broker using (brokerId)
	LEFT JOIN driver ON (driver.driverId = report.reportType)
	JOIN term ON (term.termId = if(driverId is null, broker.termId, driver.termId) )
WHERE 
	report.brokerId=".$broker."
	AND ( reportStartDate between '$fromDate' AND '$toDate' OR reportEndDate between '$fromDate' AND '$toDate')
	";
	
if($driver != 0){ 
	$queryReports.= " AND reportType = ".$driver;
	$driverInfo = mysql_fetch_assoc(mysql_query("select * from driver where driverId = $driver",$conexion));
}

$queryReports.=" ORDER BY reportEndDate desc";

$reports = mysql_query($queryReports,$conexion);


$globalTotal = 0;
$toPayTotal = 0;
$paidTotal = 0;

$tableHolder = "";

while($reportInfo=mysql_fetch_assoc($reports)){
	//For each report
	
	//get the number of checks
	$cheques = mysql_query("select * from paidcheques where reportId = ".$reportInfo['reportId'],$conexion);
	
	$payments = mysql_query("SELECT sum(paidchequesAmount) as paid from paidcheques where reportId = ".$reportInfo['reportId'],$conexion);
	$mysqlPaid = mysql_fetch_assoc($payments);
	$totalPaid = decimalPad($mysqlPaid['paid'] == null ? 0 : $mysqlPaid['paid']);
	$paidTotal += $totalPaid;
	
	$reportTotal = getReportTotal($reportInfo['reportId'], $conexion);
	//$reportTotal = decimalPad($reportTotalInfo['reportTotal']) ;
	
	$globalTotal += $reportTotal;
	
	$countCheques = mysql_num_rows($cheques);
	if($countCheques <2){
		$rows = 1;
		$rowspan = "";
	}else{
		$rows = $countCheques;
		$rowspan = " rowspan='$countCheques'";
	}
	
	$dueDate = (date('m/d/Y', strtotime('+'.$reportInfo['termValue'].' days', strtotime($reportInfo['reportEndDate']))));
	
	$tableHolder.= "<tr>";
	$tableHolder.= "<td $rowspan>".$reportInfo['reportId']."</td>";
	$tableHolder.= "<td $rowspan>".to_MDY($reportInfo['reportStartDate'])."</td>";
	$tableHolder.= "<td $rowspan>".to_MDY($reportInfo['reportEndDate'])."</td>";
	$tableHolder.= "<td $rowspan>".$dueDate."</td>";
	$tableHolder.= "<td $rowspan>".($reportInfo['driverId']!=null?$reportInfo['driverLastName'].", ".$reportInfo['driverFirstName']:"N/A")."</td>";
	$tableHolder.= "<td $rowspan>".decimalPad($reportInfo['driverId']==null?$brokerInfo['brokerPercentage']:$reportInfo['driverPercentage'])."%</td>";
	
	$tableHolder.= "<td $rowspan>".decimalPad($reportTotal)."</td>";
	$tableHolder.= "<td $rowspan>".decimalPad($reportTotal - $totalPaid)."</td>";
	$tableHolder.= "<td $rowspan>".decimalPad($totalPaid)."</td>";
	$first = true;
	
	if($countCheques == 0){
		$tableHolder.= "<td >--</td>";
		$tableHolder.= "<td >--</td>";
		$tableHolder.= "<td >--</td>";
	}
	
	if($countCheques == 1){
		$rowChequeInfo = mysql_fetch_assoc($cheques);
		$tableHolder.= "<td >".decimalPad($rowChequeInfo['paidchequesAmount'])."</td>";
		$tableHolder.= "<td >".$rowChequeInfo['paidchequeNumber']."</td>";
		$tableHolder.= "<td >".to_MDY($rowChequeInfo['paidchequesDate'])."</td>";
	}else if($countCheques > 1){
		
		while($rowChequeInfo = mysql_fetch_assoc($cheques)){
			if($first){
				$first = false;
			}else{
				$tableHolder.= "</tr><tr>\n";
			}
			$tableHolder.= "<td >".decimalPad($rowChequeInfo['paidchequesAmount'])."</td>";
			$tableHolder.= "<td >".$rowChequeInfo['paidchequeNumber']."</td>";
			$tableHolder.= "<td >".to_MDY($rowChequeInfo['paidchequesDate'])."</td>";
		}
	}
	
	
	//if($countCheques = 0) echo "";
	
	//$chequesInfo = mysql_fetch_assoc($cheques);
	//get the total
	
	$tableHolder.= "</tr>\n";
}
echo "<tr><td colspan='6'></td><td>".decimalPad($globalTotal)."</td><td>".decimalPad($globalTotal - $paidTotal)."</td><td>".decimalPad($paidTotal)."</td><td colspan='3'></td></tr>";
?>
<tr>
	<th width="8%" >Report</th>
	<th colspan='2' >Date Range</th>
	<th width="8%" >Due Date</th>
	<th colspan='2' >Driver</th>
	<th width="16%" >Bill Total</th>
	<th width="8%" >To Pay</th>
	<th width="16%" >Paid</th>
	<th width="8%" >Amount</th>
	<th width="5%" >Cheque Number</th>
	<th width="6%" >Date</th>
</tr>
<?
echo $tableHolder;
mysql_close();
?>
</table>

</body>
</html>
