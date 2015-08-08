<?php
include_once '../nyro_header.php';
$supplierId = $_GET['supplierId'];
$supplierInfo = objectQuery($conexion, '*',$supplierExtendedTables,'supplierId = '.$supplierId);
?>
<script type="text/javascript">
function deleteSuccess() {
	closeNM();
}
</script>
<div id='actions' class="top-bar">
	<?php echo createActionIcon(IMG_MNG, 'managePriceList', 'Manage Price List', '../nyros/managePriceList.php', "supplierId=$supplierId", 'show');?>
	<?php echo createEditIcon('editSupplier','../nyros/editSupplier.php',"supplierId=$supplierId", "Supplier");?>
	<?php echo createDeleteIcon('deleteSupplier','../submit/deleteSupplier.php',"supplierId=$supplierId", "Supplier");?>
</div>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>Supplier Information</th>
		</tr>
		<?php
		$flag = true;
		$vendorLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_VENDOR, createGenericNyroableAttributesSmall($supplierInfo['vendorId'],'vendor'));
		echo printTuple(($flag?'':"class='bg'"),'Supplier Name',$supplierInfo['supplierName']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Supplier Telephone',showPhoneNumber($supplierInfo['supplierTel']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Supplier Fax',showPhoneNumber($supplierInfo['supplierFax']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Supplier Info',$supplierInfo['supplierInfo']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$supplierInfo['addressLine1']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$supplierInfo['addressCity'].", ".$supplierInfo['addressState']." ".$supplierInfo['addressZip']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Supplier Dumptime',$supplierInfo['supplierDumptime']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Vendor',$supplierInfo['vendorName'], $vendorLink);$flag = !$flag;
		?>
	</table>
</div>
