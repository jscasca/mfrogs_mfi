<?
include_once '../function_header.php';
include_once '../datapack-functions/datapack.php';

$query = "SELECT 
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
	WHERE ticketId <> '0'
		";
if($_GET['startDate']!="0"){$query.=" AND ticketDate >= '".$_GET['startDate']."'";}
if($_GET['endDate']!="0"){$query.=" AND ticketDate <= '".$_GET['endDate']."'";}
if($_GET['vendorId']!="0"){$query.=" AND vendorId = '".$_GET['vendorId']."'";}
if($_GET['supplierId']!="0"){$query.=" AND item.supplierId = '".$_GET['supplierId']."'";}
if($_GET['projectId']!="0" && $_GET['projectId']!=''){$query.=" AND item.projectId = '".$_GET['projectId']."'";}
if($_GET['invoiced']=='yes'){$query.=" AND supplierInvoiceId IS NOT NULL ";}
if($_GET['invoiced']=='no'){$query.=" AND supplierInvoiceId IS NULL ";}
$query.=" ORDER BY ticketNumber asc";
if(isset($_GET['limit']) &&$_GET['limit'] != 0){$query.=" limit ".$_GET['limit'];}
$currentActive = array();
if(isset($_GET['currentActive']) && $_GET['currentActive'] != '') {
	$currentTickets = explode('~', $_GET['currentActive']);
	foreach($currentTickets as $ticketId) {
		$currentActive[$ticketId] = true;
	}
}
$tickets = mysql_query($query, $conexion);
$tbody = "<tbody>";
$flag = true;
while($ticket = mysql_fetch_assoc($tickets)) {
	if(isset($currentActive[$ticket['ticketId']]))continue;
	
	$tbody.= "
	<tr ticketId='".$ticket['ticketId']."' id='ticket".$ticket['ticketId']."' ".($flag ? " class='bg' " : "" ).">
		<td class='first style2'>".$ticket['projectId']."<input type='hidden' value=\"".$ticket['projectName']."\" id='projectName".$ticket['ticketId']."'/></td>
		<td class='first style2'>".$ticket['customerName']."</td>
		<td class='first style2'>".$ticket['itemNumber']."</td>
		<td class='first style2'>".to_MDY($ticket['ticketDate'])."<input type='hidden' value='".to_MDY($ticket['ticketDate'])."' id='ticketDate".$ticket['ticketId']."'/></td>
		<td class='first style2'>".$ticket['materialName']."<input type='hidden' value='".$ticket['materialName']."' id='materialName".$ticket['ticketId']."'/></td>
		<td class='first style2'>".$ticket['itemDisplayFrom']."</td>
		<td class='first style2'>".$ticket['ticketMfi']."<input type='hidden' value='".$ticket['ticketMfi']."' id='ticketMfi".$ticket['ticketId']."'/></td>
		<td class='first style2'>".$ticket['ticketNumber']."<input type='hidden' value='".$ticket['ticketNumber']."' id='ticketNumber".$ticket['ticketId']."'/></td>
		<td class='number'>$".decimalPad($ticket['itemMaterialPrice'])."<input type='hidden' value='".decimalPad(decimalPad($ticket['itemMaterialPrice'])*decimalPad($ticket['ticketAmount']))."' id='price".$ticket['ticketId']."'/></td>
		<td class='number'>".decimalPad($ticket['ticketAmount'])."</td>
		<td class='number'>$".decimalPad($ticket['itemCustomerCost'])."</td>
		<td class='number'>".decimalPad($ticket['ticketBrokerAmount'])."</td>
		<td class='number'>$".decimalPad($ticket['itemBrokerCost'])."</td>
		<td>".($ticket['supplierInvoiceNumber']==null?"<img src='/mfi/img/23.png' class='add-ticket' width='20px'>":"<label class='rm-ticket'>".$ticket['supplierInvoiceNumber']."</label>")."</td>
	</tr>
	";
	
}

$jsondata['table'] = $tbody."</tbody>";
$jsondata['query'] = $query;
	echo json_encode($jsondata);


mysql_close();
?>
