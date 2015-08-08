<?php
include_once '../nyro_header.php';
$brokerId = $_GET['brokerId'];
$brokerInfo = objectQuery($conexion, '*',$brokerExtendedTables,'brokerId = '.$brokerId);
?>
<script type="text/javascript">
function deleteSuccess() {
	closeNM();
}
</script>
<div id='actions' class="top-bar">
	<?php echo createEditIcon('editBroker','../nyros/editBroker.php',"brokerId=$brokerId", "Broker");?>
	<?php echo createDeleteIcon('deleteBroker','../submit/deleteBroker.php',"brokerId=$brokerId", "Broker");?>
</div>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>Broker Information</th>
		</tr>
		<?php
		$flag = true;
		echo printTuple(($flag?'':"class='bg'"),'Broker Name',$brokerInfo['brokerName']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Broker PID',$brokerInfo['brokerPid']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Broker Contact',$brokerInfo['brokerContactName']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Broker Telephone',showPhoneNumber($brokerInfo['brokerTel']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Broker Fax',showPhoneNumber($brokerInfo['brokerFax']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Broker Mobile',showPhoneNumber($brokerInfo['brokerMobile']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$brokerInfo['addressLine1']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$brokerInfo['addressCity'].", ".$brokerInfo['addressState']." ".$brokerInfo['addressZip']);$flag = !$flag;
		?>
	</table>
</div>
