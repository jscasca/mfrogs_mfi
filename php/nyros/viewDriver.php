<?php
include_once '../nyro_header.php';
$driverId = $_GET['driverId'];
$driverInfo = objectQuery($conexion, '*',$driverExtendedTables,'driverId = '.$driverId);
$brokerInfo = objectQuery($conexion, '*', 'broker','brokerId = '.$driverInfo['brokerId']);
?>
<script type="text/javascript">
function deleteSuccess() {
	closeNM();
}
</script>
<div id='actions' class="top-bar">
	<?php echo createEditIcon('editDriver','../nyros/editDriver.php',"driverId=$driverId", "Driver");?>
	<?php echo createDeleteIcon('deleteDriver','../submit/deleteDriver.php',"driverId=$driverId", "Driver");?>
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
		$brokerLink = printImgLink(IMG_VIEW, NYRO_CLASS, TITLE_VIEW_BROKER, createGenericNyroableAttributesSmall($driverInfo['brokerId'],'broker'));
		echo printTuple(($flag?'':"class='bg'"),'Driver Name',$driverInfo['driverFirstName']." ".$driverInfo['driverLastName']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Broker',$brokerInfo['brokerName'], $brokerLink);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Driver SSN',$driverInfo['driverSSN']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Driver Telephone',showPhoneNumber($driverInfo['driverTel']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Driver Info',$driverInfo['driverInfo']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$driverInfo['addressLine1']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$driverInfo['addressCity'].", ".$driverInfo['addressState']." ".$driverInfo['addressZip']);$flag = !$flag;
		?>
	</table>
</div>
