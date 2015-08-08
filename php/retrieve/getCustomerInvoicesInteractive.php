<?
include_once '../function_header.php';
include_once '../datapack-functions/datapack.php';

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
		invoice
		JOIN project USING (projectId)
		JOIN customer USING (customerId)
		JOIN term USING (termId)
	WHERE
		invoiceId <> 0
";
if($_GET['customerId'] != 0) { $queryReports.=" AND invoice.customerId = ".$_GET['customerId'];}
if($_GET['projectId'] != 0) { $queryReports.=" AND invoiceType = ".$_GET['projectId'];}
if($_GET['afterDate'] != 0) { $queryReports.=" AND invoiceEndDate >= '".$_GET['afterDate']."'";}
if($_GET['beforeDate'] != 0) { $queryReports.=" AND invoiceEndDate <= '".$_GET['beforeDate']."'";}
$queryReports.=" ORDER BY invoiceEndDate desc limit 200";
$invoices = mysql_query($queryReports, $conexion);
$tbody = "<table class='listing form' cellpadding='0' cellspacing='0' ><tr>
	<th>ID</th>
	<th>Customer</th>
	<th>Project</th>
	<th>Report Date</th>
	<th>Start Date</th>
	<th>End Date</th>
	<th>Due Date</th>
	<th>Total</th>
	<th>Paid</th>
	<th>Balance</th>
	<th colspan='2'></th>
</tr>";
while($invoice = mysql_fetch_assoc($invoices)) {
	$invoiceTotal = getInvoiceTotal($invoice['invoiceId'], $conexion);
	$paidTotal = getInvoicePaid($invoice['invoiceId'], $conexion);
	
	if($paidTotal == null || $paidTotal <= 0 ){ $paid = 'Unpaid';if($paidR)continue;}
	if($paidTotal != null && $paidTotal >= $invoiceTotal && $paidTotal != 0){ $paid = 'Paid'; if($unpaidR)continue;}
	if($paidTotal != null && $paidTotal > 0 && $paidTotal < $invoiceTotal){ $paid = 'Warning';if($paidR)continue;}
	if($paidTotal != null && $paidTotal > $invoiceTotal){ $paid = 'Overpaid'; if($unpaid)continue;}
	
	if($colorFlag){$tbody.= "<tr class='even".$paid." printable' id='invoice".$invoice['invoiceId']."' invoice='".$invoice['invoiceId']."'>";}
	else{$tbody.= "<tr class='odd".$paid." printable' id='invoice".$invoice['invoiceId']."' invoice='".$invoice['invoiceId']."'>";}
	$colorFlag=!$colorFlag;
	
	$tbody.= "<td>".$invoice['invoiceId']."</td>";
	$tbody.= "<td>".$invoice['customerName']."</td>";
	$tbody.= "<td>".$invoice['projectId']."</td>";
	$tbody.= "<td>".to_MDY($invoice['invoiceDate'])."</td>";
	$tbody.= "<td>".to_MDY($invoice['invoiceStartDate'])."</td>";
	$tbody.= "<td>".to_MDY($invoice['invoiceEndDate'])."</td>";
	$tbody.= "<td>".(date('m/d/Y', strtotime('+'.$invoice['termValue'].' days', strtotime($invoice['invoiceEndDate']))))."</td>";
	$tbody.= "<td>".decimalPad($invoiceTotal)."</td>";
	$tbody.= "<td>".decimalPad($paidTotal)."</td>";
	$tbody.= "<td>".decimalPad($invoiceTotal - $paidTotal)."</td>";
	
	if($paid == "Overpaid" || $paid == "Paid")$tbody.="<td></td>";
	else $tbody.="<td>".createActionIcon(IMG_PAY,'pay'.$invoice['invoiceId'],'Pay Invoice','../nyros/payCustomerInvoice.php','reportId='.$invoice['invoiceId'],'show'," width='22' height='22'")."</td>";
	
	if($paid == "Unpaid") {
		$tbody.= "<td>".createActionIcon(IMG_DELETE,'delete'.$invoice['invoiceId'],'Delete Invoice','../submit/deleteCustomerInvoice.php','reportId='.$invoice['invoiceId'],'delete'," width='22' height='22'")."</td>";
	} else {
		$tbody.="<td>".createActionIcon(IMG_MNG,'','Manage Invoice','../nyros/manageCustomerInvoice.php','reportId='.$invoice['invoiceId'],'show'," width='22' height='22'")."</td>";
	}
	$tbody.="</tr>";
}
$tbody.="</table>";

$jsondata['objectId'] = 'customerInvoicesTable';
$jsondata['table'] = $tbody;
echo json_encode($jsondata);


mysql_close();
?>
