<?php
include_once '../nyro_header.php';
$fuelLoadId = $_GET['fuelLoadId'];
$fuelLoadInfo = objectQuery($conexion, '*','fuel_load JOIN broker ON (broker.brokerId = fuel_load.brokerId) LEFT JOIN truck USING (truckId)','fuelLoadId = '.$fuelLoadId);
?>
<script type="text/javascript">
function deleteSuccess() {
	closeNM();
}
</script>
<div id='actions' class="top-bar">
	<?php echo createEditIcon('editFuelLoad','../nyros/editFuelLoad.php',"fuelLoadId=$fuelLoadId", "FuelLoad");?>
	<?php echo createDeleteIcon('deleteFuelLoad','../submit/deleteFuelLoad.php',"fuelLoadId=$fuelLoadId", "FuelLoad");?>
</div>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>FuelLoad Information</th>
		</tr>
		<?php
		$flag = true;
		$brokerLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_BROKER, createGenericNyroableAttributesSmall($fuelLoadInfo['brokerId'],'broker'));
		echo printTuple(($flag?'':"class='bg'"),'Fuel Load Id',$fuelLoadInfo['fuelLoadId']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Broker',$fuelLoadInfo['brokerName'], $brokerLink);$flag = !$flag;
		if($fuelLoadInfo['truckNumber'] != null) {
			$truckLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_TRUCK, createGenericNyroableAttributesSmall($fuelLoadInfo['truckId'],'truck'));
			echo printTuple(($flag?'':"class='bg'"),'Truck',$fuelLoadInfo['brokerPid']."-".$fuelLoadInfo['truckNumber'], $brokerLink);$flag = !$flag;
		} else {
			echo printTuple(($flag?'':"class='bg'"),'Truck',$fuelLoadInfo['truckNumber']);$flag = !$flag;
		}
		echo printTuple(($flag?'':"class='bg'"),'Date',to_MDY($fuelLoadInfo['fuelLoadDate']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Start',$fuelLoadInfo['fuelLoadStart']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Finish',$fuelLoadInfo['fuelLoadFinish']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Reistered',$fuelLoadInfo['fuelLoadRegistered']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Mileage',$fuelLoadInfo['fuelLoadMileage']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Comment',$fuelLoadInfo['fuelLoadCommet']);$flag = !$flag;
		?>
	</table>
</div>
