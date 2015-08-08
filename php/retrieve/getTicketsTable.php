<?
include_once '../function_header.php';
include_once '../datapack-functions/datapack.php';
/*
$customerId = (isset($_GET['customerId'])?$_GET['customerId']:0);
$projectId = (isset($_GET['projectId'])?$_GET['projectId']:0);
$itemId = (isset($_GET['itemId'])?$_GET['itemId']:0);
$brokerId = (isset($_GET['brokerId'])?$_GET['brokerId']:0);
$truckId = (isset($_GET['truckId'])?$_GET['truckId']:0);
$driverId = (isset($_GET['driverId'])?$_GET['driverId']:0);
$vendorId = (isset($_GET['vendorId'])?$_GET['vendorId']:0);
$supplierId = (isset($_GET['supplierId'])?$_GET['supplierId']:0);

$invoiceId = (isset($_GET['invoiceId'])?$_GET['invoiceId']:0);
$reportId = (isset($_GET['reportId'])?$_GET['reportId']:0);
$supplierInvoiceId = (isset($_GET['supplierInvoiceId'])?$_GET['supplierInvoiceId']:0);

$ticketMfi = (isset($_GET['ticketMfi'])?$_GET['ticketMfi']:0);
$ticketNumber = (isset($_GET['ticketNumber'])?$_GET['ticketNumber']:0);
$startDate = (isset($_GET['startDate'])?$_GET['startDate']:0);
$endDate = (isset($_GET['endDate'])?$_GET['endDate']:0);

$invoiced = (isset($_GET['invoiced'])?$_GET['invoiced']:0);
$reported = (isset($_GET['reported'])?$_GET['reported']:0);
$supplied = (isset($_GET['supplied'])?$_GET['supplied']:0);

$values = $_GET['values'];
$headers = $_GET['headers'];
$types = $_GET['variables'];

$ticketsQuery = "
	SELECT
		*
	FROM
		ticket
		JOIN item USING (itemId)
		JOIN material USING (materialId)
		JOIN supplier USING (supplierId)
		JOIN vendor USING (vendorId)
		JOIN project USING (projectId)
		JOIN customer USING (customerId)
		JOIN truck USING (truckId)
		JOIN broker USING (brokerId)
		JOIN driver USING (driverId)
		LEFT JOIN reportticket USING (ticketId)
		LEFT JOIN invoiceticket USING (ticketId)
		LEFT JOIN supplierinvoiceticket USING (ticketId)
	WHERE ticketId <> 0
";
if($customerId!=0)$ticketsQuery.=" AND customerId = $customerId";
if($projectId!=0)$ticketsQuery.=" AND projectId = $projectId";
if($itemId!=0)$ticketsQuery.=" AND itemId = $itemId";
if($brokerId!=0)$ticketsQuery.=" AND brokerId = $brokerId";
if($truckId!=0)$ticketsQuery.=" AND truckId = $truckId";
if($driverId!=0)$ticketsQuery.=" AND driverId = $driverId";
if($vendorId!=0)$ticketsQuery.=" AND vendorId = $vendorId";
if($supplierId!=0)$ticketsQuery.=" AND supplierId = $supplierId";

if($invoiceId!=0)$ticketsQuery.=" AND invoiceId = $invoiceId";
if($reportId!=0)$ticketsQuery.=" AND reportId = $reportId";
if($supplierInvoiceId!=0)$ticketsQuery.=" AND supplierInvoiceId = $supplierInvoiceId";

if($ticketMfi!=0)$ticketsQuery.=" AND ticketMfi = '$ticketMfi'";
if($ticketNumber!=0)$ticketsQuery.=" AND ticketNumber = '$ticketNumber'";
if($startDate!=0)$ticketsQuery.=" AND ticketDate > '$startDate'";
if($endDate!=0)$ticketsQuery.=" AND ticketDate < '$endDate'";

if($invoiced == '1')$ticketsQuery.=" AND invoiceId IS NOT NULL";
if($invoiced == '2')$ticketsQuery.=" AND invoiceId IS NULL";
if($reported == '1')$ticketsQuery.=" AND reportId IS NOT NULL";
if($reported == '2')$ticketsQuery.=" AND reportId IS NULL";
if($supplied == '1')$ticketsQuery.=" AND supplierInvoiceId IS NOT NULL";
if($supplied == '2')$ticketsQuery.=" AND supplierInvoiceId IS NULL";

$ticketsQuery.=" ORDER BY ticketId desc limit 200";

$tableString = "<table class='listing form' cellpadding='0' cellspacing='0' ><tr>";
$headerArray = explode("~",$headers);
foreach($headerArray as $header) {
	$tableString.="<th>$header</th>";
}
$tableString.="</tr>";

$typeMap = createTypeMap($values, $types, '~');
$tickets = mysql_query($ticketsQuery, $conexion);
while($ticket = mysql_fetch_assoc($tickets)) {
	$tableString.="<tr ticketId='".$ticket['ticketId']."' id='ticket".$ticket['ticketId']."' class='doubleClickable'>";
	$tableString.=mapValuesWithTypes($ticket, $typeMap, '<td align="right">', '</td>');
	$tableString.="</tr>";
}

$jsondata['objectId'] = 'ticketsTable';
$jsondata['table'] = $tableString;
*/
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
}
$jsondata['objectId'] = 'ticketsTable';
$jsondata['table'] = $tableString;
echo json_encode($jsondata);


mysql_close();
?>
