<?
include_once '../function_header.php';
include_once '../datapack-functions/datapack.php';

/*
$dataTable = getTicketsTable($conexion, $_GET);
$tableString =  "<table class='listing form' cellpadding='0' cellspacing='0' ><tr>";
for($k = 1; $k < sizeOf($dataTable[0]); $k++) {
	$tableString.="<th>".$dataTable[0][$k]."</th>";
}
$tableString.="</tr>";
for($i = 1; $i < sizeOf($dataTable); $i++) {
	$tableString.="<tr ticketId='".$dataTable[$i][0]."' id='ticket".$dataTable[$i][0]."' class='doubleClickable'>";
	for($j = 1; $j < sizeOf($dataTable[$i]); $j++) {
		$tableString.='<td align="right">'.$dataTable[$i][$j].'</td>';
	}
	$tableString.="</tr>";
}*/
$paidR = false;
$unpaidR = false;

switch($_GET['paid']){
	case '2':
	$unpaidR = true;
	break;
	case '1':
	$paidR = true;
	break;
}

$queryReports = "
	SELECT *
	FROM
		report
		JOIN broker USING (brokerId)
		LEFT JOIN driver ON (driver.driverId = report.reportType)
		JOIN term ON  (term.termId = if(driverId is null, broker.termId, driver.termId))
	WHERE
		reportId <> 0
";
if($_GET['brokerId'] != 0) { $queryReports.=" AND report.brokerId = ".$_GET['brokerId'];}
if($_GET['driverId'] != 0) { $queryReports.=" AND reportType = ".$_GET['driverId'];}
if($_GET['afterDate'] != 0) { $queryReports.=" AND reportEndDate >= '".$_GET['afterDate']."'";}
if($_GET['beforeDate'] != 0) { $queryReports.=" AND reportEndDate <= '".$_GET['beforeDate']."'";}
$queryReports.=" ORDER BY reportEndDate desc limit 200";
$reports = mysql_query($queryReports, $conexion);
$tbody = "<table class='listing form' cellpadding='0' cellspacing='0' ><tr>
	<th>ID</th>
	<th>Broker</th>
	<th>Driver</th>
	<th>Report Date</th>
	<th>Start Date</th>
	<th>End Date</th>
	<th>Due Date</th>
	<th>Total</th>
	<th>Paid</th>
	<th>Balance</th>
	<th colspan='2'></th>
</tr>";
while($invoice = mysql_fetch_assoc($reports)) {
	$reportTotal = getReportTotal($invoice['reportId'], $conexion);
	$paidTotal = getReportPaid($invoice['reportId'], $conexion);
	
	if($paidTotal == null || $paidTotal <= 0 ){ $paid = 'Unpaid';if($paidR)continue;}
	if($paidTotal != null && $paidTotal >= $reportTotal && $paidTotal != 0){ $paid = 'Paid'; if($unpaidR)continue;}
	if($paidTotal != null && $paidTotal > 0 && $paidTotal < $reportTotal){ $paid = 'Warning';if($paidR)continue;}
	if($paidTotal != null && $paidTotal > $reportTotal){ $paid = 'Overpaid'; if($unpaid)continue;}
	
	if($colorFlag){$tbody.= "<tr class='even".$paid." printable' id='report".$invoice['reportId']."' invoice='".$invoice['reportId']."'>";}
	else{$tbody.= "<tr class='odd".$paid." printable' id='report".$invoice['reportId']."' invoice='".$invoice['reportId']."'>";}
	$colorFlag=!$colorFlag;
	
	$tbody.= "<td>".$invoice['reportId']."</td>";
	$tbody.= "<td>".$invoice['brokerPid']."</td>";
	$tbody.= "<td>".($invoice['driverFirstName']==null?'----':$invoice['driverLastName'].", ".$invoice['driverFirstName'])."</td>";
	$tbody.= "<td>".to_MDY($invoice['reportDate'])."</td>";
	$tbody.= "<td>".to_MDY($invoice['reportStartDate'])."</td>";
	$tbody.= "<td>".to_MDY($invoice['reportEndDate'])."</td>";
	$tbody.= "<td>".(date('m/d/Y', strtotime('+'.$invoice['termValue'].' days', strtotime($invoice['reportEndDate']))))."</td>";
	$tbody.= "<td>".decimalPad($reportTotal)."</td>";
	$tbody.= "<td>".decimalPad($paidTotal)."</td>";
	$tbody.= "<td>".decimalPad($reportTotal - $paidTotal)."</td>";
	
	if($paid == "Overpaid" || $paid == "Paid")$tbody.="<td></td>";
	else $tbody.="<td>".createActionIcon(IMG_PAY,'pay'.$invoice['reportId'],'Pay Report','../nyros/payBrokerInvoice.php','reportId='.$invoice['reportId'],'show'," width='22' height='22'")."</td>";
	
	if($paid == "Unpaid") {
		$tbody.= "<td>".createActionIcon(IMG_DELETE,'delete'.$invoice['reportId'],'Delete Invoice','../submit/deleteBrokerInvoice.php','reportId='.$invoice['reportId'],'delete'," width='22' height='22'")."</td>";
	} else {
		$tbody.="<td>".createActionIcon(IMG_MNG,'','Manage Invoice','../nyros/manageBrokerInvoice.php','reportId='.$invoice['reportId'],'show'," width='22' height='22'")."</td>";
	}
	$tbody.="</tr>";
}
$tbody.="</table>";

$jsondata['objectId'] = 'brokerInvoicesTable';
$jsondata['table'] = $tbody;
echo json_encode($jsondata);


mysql_close();
?>
