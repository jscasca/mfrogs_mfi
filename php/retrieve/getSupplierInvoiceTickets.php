<?
include_once '../function_header.php';
include_once '../datapack-functions/datapack.php';

$currentTickets = array();
$currentActive = $_REQUEST['currentActive'];
if($currentActive != '') {
	$currentActive = explode('~',$currentActive);
	foreach($currentActive as $current) {
		$currentTickets[$current] = "1";
	}
}

$queryTickets = "SELECT 
		* 
	FROM 
		ticket
		JOIN item using (itemId)
		JOIN project using (projectId)
		JOIN customer using (customerId)
		JOIN material using (materialId)
		JOIN supplier ON (item.supplierId = supplier.supplierId)
		LEFT JOIN supplierinvoiceticket using (ticketId)
		LEFT JOIN supplierinvoice using (supplierInvoiceId)
		WHERE ticketId <> '0'";

if($_GET['startDate']!=""){
	$queryTickets .= " AND ";
	$queryTickets .= "ticketDate >= '".to_YMD($_GET['startDate'])."'";
}

if($_GET['endDate']!=""){
	$queryTickets .= " AND ";
	$queryTickets .= "ticketDate <= '".to_YMD($_GET['endDate'])."'";
}

if($_GET['vendorId']!=0){
	$queryTickets .= " AND ";
	$queryTickets .= "vendorId = ".$_GET['vendorId'];
}

if($_GET['supplierId']!=0){
	$queryTickets .= " AND ";
	$queryTickets .= " item.supplierId = ".$_GET['supplierId'];
}

if($_GET['invoiced']=='yes'){
	$queryTickets .= " AND ";
	$queryTickets .= "supplierInvoiceId IS NOT NULL ";
}

if($_GET['invoiced']=='no'){
	$queryTickets .= " AND ";
	$queryTickets .= "supplierInvoiceId IS NULL ";
}

if($_GET['projectId']!=''){
	$queryTickets .= " AND ";
	$queryTickets .= "item.projectId = ".$_GET['projectId'];
}
	
$queryTickets .= "	
	ORDER BY
		ticketNumber ASC
";

$colorFlag=true;
$terms = mysql_query($queryTickets,$conexion);
$numTerms = mysql_num_rows($terms);
$tbody = "<tbody>";
while($term = mysql_fetch_assoc($terms)) {
	if(isset($currentTickets[$term['ticketId']])) continue;
	if($colorFlag)
	{
		$tbody.= "<tr id='ticket".$term['ticketId']."' >";
		!$colorFlag;
	}
	else
	{
		$tbody.= "<tr id='ticket".$term['ticketId']."' class='bg'>";
		!$colorFlag;
	}
	$tbody.= "
		<td class='first style2'>".$term['projectId']."<input type='hidden' value=\"".$term['projectName']."\" id='projectName".$term['ticketId']."'/></td>
		<td class='first style2'>".$term['customerName']."</td>
		<td class='first style2'>".$term['itemNumber']."</td>
		<td class='first style2'>".to_MDY($term['ticketDate'])."<input type='hidden' value='".to_MDY($term['ticketDate'])."' id='ticketDate".$term['ticketId']."'/></td>
		<td class='first style2'>".$term['materialName']."<input type='hidden' value='".$term['materialName']."' id='materialName".$term['ticketId']."'/></td>
		<td class='first style2'>".$term['itemDisplayFrom']."</td>
		<td class='first style2'>".$term['ticketMfi']."<input type='hidden' value='".$term['ticketMfi']."' id='ticketMfi".$term['ticketId']."'/></td>
		<td class='first style2'>".$term['ticketNumber']."<input type='hidden' value='".$term['ticketNumber']."' id='ticketNumber".$term['ticketId']."'/></td>
		<td class='number'>$".decimalPad($term['itemMaterialPrice'])."<input type='hidden' value='".decimalPad(decimalPad($term['itemMaterialPrice'])*decimalPad($term['ticketAmount']))."' id='price".$term['ticketId']."'/></td>
		<td class='number'>".decimalPad($term['ticketAmount'])."</td>
		<td class='number'>$".decimalPad($term['itemCustomerCost'])."</td>
		<td class='number'>".decimalPad($term['ticketBrokerAmount'])."</td>
		<td class='number'>$".decimalPad($term['itemBrokerCost'])."</td>
		<td>".($term['supplierInvoiceNumber']==null?"<img src='/trucking/img/23.png' class='add-ticket' width='20px'>":"<label class='rm-ticket'>".$term['supplierInvoiceNumber']."</label>")."</td>
			";
	$tbody.= "</tr>";
}
$tbody.= "</tbody>";

$jsondata['table'] = $tbody;
$jsondata['query'] = $queryTickets;
echo json_encode($jsondata);


mysql_close();
?>
