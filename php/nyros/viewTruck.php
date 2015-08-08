<?php
include_once '../nyro_header.php';
$truckId = $_GET['truckId'];
$truckInfo = objectQuery(
	$conexion, 
	'*',
	'truck JOIN address ON (truck.addressId = address.addressId) 
		JOIN broker USING (brokerId)',
	'truckId = '.$truckId);
?>
<script type="text/javascript">
function deleteSuccess() {
	closeNM();
}
</script>
<div id='actions' class="top-bar">
	<?php echo createEditIcon('editTruck','../nyros/editTruck.php',"truckId=$truckId", "Truck");?>
	<?php echo createDeleteIcon('deleteTruck','../submit/deleteTruck.php',"truckId=$truckId", "Truck");?>
</div>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>Driver Information</th>
		</tr>
		<?php
		$flag = true;
		$brokerLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_BROKER, createGenericNyroableAttributesSmall($truckInfo['brokerId'],'broker'));
		echo printTuple(($flag?'':"class='bg'"),'Truck Number',$truckInfo['brokerPid']." ".$truckInfo['truckNumber']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Broker',$truckInfo['brokerName'], $brokerLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Driver',$truckInfo['truckDriver']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Plates',$truckInfo['truckPlates']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Brand',$truckInfo['truckBrand']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Year/Model',$truckInfo['truckYear']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Tire Size',$truckInfo['truckTireSize']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Additional Info',$truckInfo['truckInfo']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$truckInfo['addressLine1']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$truckInfo['addressCity'].", ".$truckInfo['addressState']." ".$truckInfo['addressZip']);$flag = !$flag;
		?>
	</table>
</div>
