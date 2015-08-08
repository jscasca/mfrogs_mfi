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
		supplierinvoice
		JOIN supplier using (supplierId)
		JOIN vendor using (vendorId)
	WHERE
		supplierInvoiceId <> 0
";
if($_GET['vendorId'] != 0) { $queryReports.=" AND vendorId = ".$_GET['vendorId'];}
if($_GET['supplierId'] != 0) { $queryReports.=" AND supplierId = ".$_GET['supplierId'];}
if($_GET['afterDate'] != 0) { $queryReports.=" AND supplierInvoiceDate >= '".$_GET['afterDate']."'";}
if($_GET['beforeDate'] != 0) { $queryReports.=" AND supplierInvoiceDate <= '".$_GET['beforeDate']."'";}
$queryReports.=" ORDER BY supplierInvoiceDate desc limit 200";
$reports = mysql_query($queryReports, $conexion);
$tbody = "<table class='listing form' cellpadding='0' cellspacing='0' ><tr>
	<th>ID</th>
	<th>Supplier</th>
	<th>Invoice #</th>
	<th>Date</th>
	<th>Total</th>
	<th>Paid</th>
	<th>Balance</th>
	<th colspan='2'></th>
</tr>";
while($invoice = mysql_fetch_assoc($reports)) {
	$paidTotal = getSuppliedPaid($invoice['supplierInvoiceId'], $conexion);
	$reportTotal = decimalPad($invoice['supplierInvoiceAmount']);
	
	if($paidTotal == null || $paidTotal <= 0 ){ $paid = 'Unpaid';if($paidR)continue;}
	if($paidTotal != null && $paidTotal >= $reportTotal && $paidTotal != 0){ $paid = 'Paid'; if($unpaidR)continue;}
	if($paidTotal != null && $paidTotal > 0 && $paidTotal < $reportTotal){ $paid = 'Warning';if($paidR)continue;}
	if($paidTotal != null && $paidTotal > $reportTotal){ $paid = 'Overpaid'; if($unpaid)continue;}
	
	if($colorFlag){$tbody.= "<tr class='even".$paid." printable' id='report".$invoice['supplierInvoiceId']."' invoice='".$invoice['supplierInvoiceId']."'>";}
	else{$tbody.= "<tr class='odd".$paid." printable' id='report".$invoice['supplierInvoiceId']."' invoice='".$invoice['supplierInvoiceId']."'>";}
	$colorFlag=!$colorFlag;
	
	$tbody.= "<td>".$invoice['supplierInvoiceId']."</td>";
	$tbody.= "<td>".$invoice['supplierName']."</td>";
	$tbody.= "<td>".$invoice['supplierInvoiceNumber']."</td>";
	$tbody.= "<td>".to_MDY($invoice['supplierInvoiceDate'])."</td>";
	$tbody.= "<td>".decimalPad($reportTotal)."</td>";
	$tbody.= "<td>".decimalPad($paidTotal)."</td>";
	$tbody.= "<td>".decimalPad($reportTotal - $paidTotal)."</td>";
	
	if($paid == "Overpaid" || $paid == "Paid")$tbody.="<td></td>";
	else $tbody.="<td>".createActionIcon(IMG_PAY,'pay'.$invoice['supplierInvoiceId'],'Pay Report','../nyros/paySupplierInvoice.php','reportId='.$invoice['supplierInvoiceId'],'show'," width='22' height='22'")."</td>";
	
	if($paid == "Unpaid") {
		$tbody.= "<td>".createActionIcon(IMG_DELETE,'delete'.$invoice['supplierInvoiceId'],'Delete Invoice','../submit/deleteSupplierInvoice.php','reportId='.$invoice['supplierInvoiceId'],'delete'," width='22' height='22'")."</td>";
	} else {
		$tbody.="<td>".createActionIcon(IMG_MNG,'','Manage Invoice','../nyros/manageSupplierInvoice.php','reportId='.$invoice['supplierInvoiceId'],'show'," width='22' height='22'")."</td>";
	}
	$tbody.="</tr>";
}
$tbody.="</table>";

$jsondata['objectId'] = 'supplierInvoicesTable';
$jsondata['table'] = $tbody;
echo json_encode($jsondata);


mysql_close();
?>
