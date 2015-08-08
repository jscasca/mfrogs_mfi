<?php
include_once '../nyro_header.php';
$itemId = $_GET['itemId'];
$itemInfo = objectQuery($conexion, $itemExtendedSelect,$itemExtendedTables,'itemId = '.$itemId);
?>
<script type="text/javascript">
function deleteSuccess() {
	closeNM();
}
</script>
<div id='actions' class="top-bar">
	<?php /*echo createEditIcon('editTruck','../nyros/editTruck.php',"truckId=$truckId", "Truck");*/?>
	<?php echo createDeleteIcon('deleteItem','../submit/deleteItem.php',"itemId=$itemId", "Item");?>
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
		echo printTuple(($flag?'':"class='bg'"),'Item Id',$itemInfo['itemId']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Customer',$itemInfo['customerId']." ".$itemInfo['customerName'], $customerLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Project',$itemInfo['projectId']." ".$itemInfo['projectName'], $projectLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Item Number',$itemInfo['itemNumber']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Vendor',$itemInfo['vendorName'], $vendorLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Supplier',$itemInfo['supplierName'], $supplierLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Material',$itemInfo['materialName']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'From',$itemInfo['itemDisplayFrom']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'To',$itemInfo['itemDisplayTo']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Material',decimalPad($itemInfo['itemMaterialPrice']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Broker',decimalPad($itemInfo['itemBrokerCost']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Customer',decimalPad($itemInfo['itemCustomerCost']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Type',$itemInfo['itemTypeString']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Item Description',$itemInfo['itemDescription']);$flag = !$flag;
		?>
	</table>
</div>
