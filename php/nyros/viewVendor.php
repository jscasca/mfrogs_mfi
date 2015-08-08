<?php
include_once '../nyro_header.php';
$vendorId = $_GET['vendorId'];
$vendorInfo = objectQuery($conexion, '*',$vendorExtendedTables,'vendorId = '.$vendorId);
?>
<script type="text/javascript">
function deleteSuccess() {
	closeNM();
}
</script>
<div id='actions' class="top-bar">
	<?php echo createEditIcon('editVendor','../nyros/editVendor.php',"vendorId=$vendorId", "Vendor");?>
	<?php echo createDeleteIcon('deleteVendor','../submit/deleteVendor.php',"vendorId=$vendorId", "Vendor");?>
</div>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>Vendor Information</th>
		</tr>
		<?php
		$flag = true;
		echo printTuple(($flag?'':"class='bg'"),'Vendor Name',$vendorInfo['vendorName']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Vendor Information',$vendorInfo['vendorInfo']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Vendor Telephone',showPhoneNumber($vendorInfo['vendorTel']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Vendor Fax',showPhoneNumber($vendorInfo['vendorFax']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$vendorInfo['addressLine1']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$vendorInfo['addressCity'].", ".$vendorInfo['addressState']." ".$vendorInfo['addressZip']);$flag = !$flag;
		
		echo printTuple(($flag?'':"class='bg'"),'Comment',$vendorInfo['vendorInfo']);$flag = !$flag;
		
		?>
	</table>
</div>
