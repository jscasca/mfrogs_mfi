<?php
include_once '../nyro_header.php';
$customerId = $_GET['customerId'];
$customerInfo = objectQuery($conexion, '*',$customerExtendedTables,'customerId = '.$customerId);
?>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>Customer Information</th>
		</tr>
		<?php
		$flag = true;
		echo printTuple(($flag?'':"class='bg'"),'Customer Id',$customerInfo['customerId']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Customer Name',$customerInfo['customerName']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Customer Telephone',showPhoneNumber($customerInfo['customerTel']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Customer Fax',showPhoneNumber($customerInfo['customerFax']));$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'WebSite',$customerInfo['customerWebsite']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$customerInfo['addressLine1']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Address',$customerInfo['addressCity'].", ".$customerInfo['addressState']." ".$customerInfo['addressZip']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'P.O. Box',$customerInfo['addressPOBox']);$flag = !$flag;
		echo printTuple(($flag?'':"class='bg'"),'Terms',$customerInfo['termName']);$flag = !$flag;
		?>
	</table>
</div>
