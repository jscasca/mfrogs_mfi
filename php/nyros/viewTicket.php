<?php
include_once '../nyro_header.php';
$ticketId = $_GET['ticketId'];
$ticketInfo = objectQuery($conexion, '*',$ticketExtendedTables,'ticketId = '.$ticketId);
?>
<script type="text/javascript">
function deleteSuccess() {
	closeNM();
}
</script>
<div id='actions' class="top-bar">
	<?php /*echo createEditIcon('editTicket','../nyros/editTicket.php',"ticketId=$ticketId", "Ticket");*/?>
	<?php echo createDeleteIcon('deleteTicket','../submit/deleteTicket.php',"ticketId=$ticketId", "Ticket");?>
</div>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>Ticket Information</th>
		</tr>
		<?php
		$flag = true;
		$projectLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_PROJECT, createGenericNyroableAttributesSmall($ticketInfo['projectId'],'project'));
		$customerLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_CUSTOMER, createGenericNyroableAttributesSmall($ticketInfo['customerId'],'customer'));
		$itemLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_ITEM, createGenericNyroableAttributesSmall($ticketInfo['itemId'],'item'));
		$supplierLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_SUPPLIER, createGenericNyroableAttributesSmall($ticketInfo['supplierId'],'supplier'));
		$vendorLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_VENDOR, createGenericNyroableAttributesSmall($ticketInfo['vendorId'],'vendor'));
		$brokerLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_BROKER, createGenericNyroableAttributesSmall($ticketInfo['brokerId'],'broker'));
		$driverLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_DRIVER, createGenericNyroableAttributesSmall($ticketInfo['driverId'],'driver'));
		$truckLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_TRUCK, createGenericNyroableAttributesSmall($ticketInfo['truckId'],'truck'));
		echo printTuple(($flag?'':"class='bg'"),'Ticket Number',$ticketInfo['ticketMfi']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Dump',$ticketInfo['ticketNumber']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Project',$ticketInfo['projectId']." ".$ticketInfo['projectName'], $projectLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Customer',$ticketInfo['customerId']." ".$ticketInfo['customerName'], $customerLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Broker',$ticketInfo['brokerName'], $brokerLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Truck',$ticketInfo['brokerPid']." ".$ticketInfo['truckNumber'], $truckLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Driver',$ticketInfo['driverLastName'], $driverLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Supplier',$ticketInfo['supplierName'], $supplierLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Vendor',$ticketInfo['vendorName'], $vendorLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Ticket Date',to_MDY($ticketInfo['ticketDate']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Ticket Amount',decimalPad($ticketInfo['ticketAmount']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Ticket Broker Amount',decimalPad($ticketInfo['ticketBrokerAmount']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Item Number',$ticketInfo['itemNumber'], $itemLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Item Material Price',decimalPad($ticketInfo['itemMaterialPrice']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Item Broker Price',decimalPad($ticketInfo['itemBrokerCost']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Item Customer Price',decimalPad($ticketInfo['itemCustomerCost']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Customer Invoice',($ticketInfo['invoiceId']==null?'N/A':$ticketInfo['invoiceId']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Broker Invoice',($ticketInfo['reportId']==null?'N/A':$ticketInfo['reportId']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Supplier Invoice',($ticketInfo['supplierInvoiceId']==null?'N/A':$ticketInfo['supplierInvoiceId']));$flag = !$flag;
		?>
	</table>
</div>
