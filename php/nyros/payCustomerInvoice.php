<?php
include_once '../nyro_header.php';
$reportId = $_GET['reportId'];
?>
<script type="text/javascript">
var reportId = '<?php echo $reportId;?>';
$(document).ready(function() {
	
	//$('#submitButton').click(function() {
	//$(document).off('click#submitButton');
	$(document).off('click', '.customerPayable');
	//$(document).one('click', '#submitButton', function() {
	$(document).on('click', '.customerPayable', function() {
		var rowId = $(this).closest('tr').attr('rowId');
		console.log(rowId);
		submitNewCustomerPayment(rowId);
	});
});

function submitNewCustomerPayment(rowId) {
	var data = getNewCustomerPaymentParams(rowId);
	var url = '../submit/submitNewCustomerPayment.php';
	
	submitNewObject(url, data);
}

function disableButton() {
	$('#submitButton').attr('disabled','disabled');
}

function enableButton() {
	$('#submitButton').removeAttr('disabled');
}

function closeNM() {
	//console.log("closing");
	$.nmTop().close();
}

function doAfterSubmit(data) {
	console.log(data);
	try{
		var obj = jQuery.parseJSON(data);
		switch(obj.code){
			case 0:
				alert("Payment created successfully");
				if(obj.invoiceBalance <= 0) {
					closeNM();
				} else {
					$.nmManual('../nyros/payCustomerInvoice.php?reportId=<?php echo $reportId;?>');
				}
				break;
			case -1:
				alert(obj.msg);
				$('#' + obj.focus).focus();
				break;
			case -4:
				alert(obj.msg);
				$('#' + obj.focus).focus();
				break;
			default:
				alert(obj.msg);
				break;
		}
	} catch(e) {
		alert("Internal Error: Please contact the administrator.");
	}
	enableButton();
}

function getNewCustomerPaymentParams(rowId) {
	var dataArray = new Array();
	dataArray['reportId'] = '<?php echo $reportId;?>';
	dataArray['amount'] = getVal('payment' + rowId);
	dataArray['date'] = evalDate(getVal('date' + rowId));
	dataArray['check'] = getVal('check' + rowId);
	dataArray['number'] = getVal('number' + rowId);

	return arrayToDataString(dataArray);
}
</script>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='5'>Payment for Customer Invoice [<?php echo $reportId;?>]</th>
		</tr>
		<tr>
			<th>Customer Check</th>
			<th>Credit</th>
			<th>Amount to Pay</th>
			<th>Date</th>
			<th></th>
		</tr>
		<?php
		$reportTotal = getInvoiceTotal($reportId, $conexion);
		$paidTotal = getInvoicePaid($reportId, $conexion);
		
		$balance = decimalPad($reportTotal - $paidTotal);
		$invoiceInfo = getCustomerInvoiceInfo($conexion, $reportId);
		//Truck
		$flag = true; $credits = mysql_query("SELECT * FROM customer_credit JOIN customer_super_check USING (customerSuperCheckId) WHERE	customerId = '".$invoiceInfo['customerId']."'", $conexion);
		while($credit = mysql_fetch_assoc($credits)) {
			$creditValue = decimalPad($credit['customerCreditAmount']);
			echo printRow(
				($flag?"class='bg'":"")." rowId='F".$reportId."W".$credit['customerSuperCheckId']."' ",
				array(
					$credit['customerSuperCheckNumber'],
					$creditValue,
					createInputText("paymentF".$reportId."W".$credit['customerSuperCheckId'],'',"size='10px'",decimalPad( $creditValue > $balance? $balance : $creditValue)), 
					createInputText("dateF".$reportId."W".$credit['customerSuperCheckId'],"","size='10px'",to_MDY($credit['customerSuperCheckDate'])),
					createInputHidden("checkF".$reportId."W".$credit['customerSuperCheckId'],"","size='10px'",$credit['customerSuperCheckId'])." ".
					createInputHidden("numberF".$reportId."W".$credit['customerSuperCheckId'],"","size='10px'",$credit['customerSuperCheckNumber'])." ".
					printImgLink(IMG_PAY, "customerPayable", "Pay with check ".$credit['customerSuperCheckNumber'], ' width="22" height="22"')
				)
			);$flag=!$flag;
			//createInputText($id, $name = '', $attributes = '', $startingValue ='')
		}
		echo printRow(
				($flag?"class='bg'":"")." rowId='F".$reportId."W0' ",
				array(
					"Cash",
					"N/A",
					createInputText("paymentF".$reportId."W0",'',"size='10px'",decimalPad($balance)), 
					createInputText("dateF".$reportId."W0","","size='10px'",date('m/d/Y')),
					createInputHidden("checkF".$reportId."W0",'',"size='10px'","0")." ".
					createInputHidden("numberF".$reportId."W0",'',"size='10px'","Cash")." ".
					printImgLink(IMG_PAY, "customerPayable", "Pay with cash", ' width="22" height="22"')
				)
			);
		?>
	</table>
	<table>
		<tr>
			<td><?php echo createSimpleButton('submitButton', 'Submit'); ?></td>
		</tr>
	</table>
</div>
