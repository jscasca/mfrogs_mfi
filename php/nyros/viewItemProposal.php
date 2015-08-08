<?php
include_once '../nyro_header.php';
$itemId = $_GET['itemProposalId'];
$itemInfo = objectQuery($conexion, $itemProposalExtendedSelect,$itemProposalExtendedTables,'itemProposalId = '.$itemId);
?>
<script type="text/javascript">
function deleteSuccess() {
	closeNM();
}
</script>
<div id='actions' class="top-bar">
	<?php /*echo createEditIcon('editTruck','../nyros/editTruck.php',"truckId=$truckId", "Truck");*/?>
	<?php echo createDeleteIcon('deleteItemProposal','../submit/deleteItemProposal.php',"itemProposalId=$itemId", "ItemProposal");?>
</div>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>Item Information</th>
		</tr>
		<?php
		$flag = true;
		$projectLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_PROJECT, createGenericNyroableAttributesSmall($itemInfo['projectId'],'project'));
		$customerLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_CUSTOMER, createGenericNyroableAttributesSmall($itemInfo['customerId'],'customer'));
		$supplierLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_SUPPLIER, createGenericNyroableAttributesSmall($itemInfo['supplierId'],'supplier'));
		$vendorLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_VENDOR, createGenericNyroableAttributesSmall($itemInfo['vendorId'],'vendor'));
		echo printTuple(($flag?'':"class='bg'"),'Item Id',$itemInfo['itemProposalId']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Customer',$itemInfo['customerId']." ".$itemInfo['customerName'], $customerLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Project',$itemInfo['projectId']." ".$itemInfo['projectName'], $projectLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Item Number',$itemInfo['itemNumber']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Vendor',$itemInfo['vendorName'], $vendorLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Supplier',$itemInfo['supplierName'], $supplierLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Material',$itemInfo['materialName']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'From',$itemInfo['itemProposalDisplayFrom']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'To',$itemInfo['itemProposalDisplayTo']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Material',decimalPad($itemInfo['itemProposalMaterialPrice']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Broker',decimalPad($itemInfo['itemProposalBrokerCost']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Customer',decimalPad($itemInfo['itemProposalCustomerCost']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Type',$itemInfo['itemProposalTypeString']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Item Description',$itemInfo['itemProposalDescription']);$flag = !$flag;
		?>
	</table>
</div>
