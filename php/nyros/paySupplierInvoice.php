<?php
include_once '../nyro_header.php';
$reportId = $_GET['reportId'];
$supplierInvoice = mysql_fetch_assoc(mysql_query("SELECT * FROM supplierinvoice WHERE supplierInvoiceId = '$reportId'",$conexion));
?>
<script type="text/javascript">
$(document).ready(function() {
	
	//$('#submitButton').click(function() {
	$(document).off('click','#submitButton');
	//$(document).one('click', '#submitButton', function() {
	$(document).on('click', '#submitButton', function() {
		submitNewBrokerPayCheck();
	});
});

function submitNewBrokerPayCheck() {
	disableButton()
	var data = getNewCheckParams();
	var url = '../submit/submitNewSupplierCheck.php';
	
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
				closeNM();
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
	//$('#submitArea').empty();
	//$('#submitArea').append("<button type='button' id='submitButton' name='submitButton' >Submit</button>");
	enableButton();
}

function getNewCheckParams() {
	var dataArray = new Array();
	dataArray['reportId'] = '<?php echo $reportId;?>';
	dataArray['number'] = getVal('newSupplierCheckNumber');
	dataArray['date'] = evalDate(getVal('newSupplierCheckDate'));
	dataArray['amount'] = getVal('newSupplierCheckAmount');
	
	var data = "";
	var glue = "";
	for(key in dataArray) {
		data += glue + key + "=" + dataArray[key];
		glue = "&";
	}
	//console.log(data);
	return data;
}
</script>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>New Pay Cheque for Broker Invoice [<?php echo $supplierInvoice['supplierInvoiceNumber'];?>]</th>
		</tr>
		<?php
		$reportTotal = decimalPad($supplierInvoice['supplierInvoiceAmount']);
		$paidTotal = getSuppliedPaid($reportId, $conexion);
		$balance = decimalPad($reportTotal - $paidTotal);
		
		$nextCheck = getNextAutoIncrement($conexion, 'suppliercheque');
		//Truck
		$flag = true; 
		echo createFormRowTextField('Check Id', 'newSupplierChequeId', $flag ? 'class="bg"' : '', true, " disabled='disabled' size='10px' value='".$nextCheck."'"); $flag = !$flag; 
		echo createFormRowTextField('Check Number', 'newSupplierCheckNumber', $flag ? 'class="bg"' : '', false, "size='10px' "); $flag = !$flag; 
		echo createFormRowTextField('Check Date', 'newSupplierCheckDate', $flag ? 'class="bg"' : '', false, "size='10px' value='".to_MDY($supplierInvoice['supplierInvoiceDate'])."'"); $flag = !$flag; 
		echo createFormRowTextField('Amount', 'newSupplierCheckAmount', $flag ? 'class="bg"' : '', false, "size='10px' value='".$balance."'"); $flag = !$flag; 
				
		?>
	</table>
	<table>
		<tr>
			<td><div id='submitArea'><?php echo createSimpleButton('submitButton', 'Submit'); ?></div></td>
		</tr>
	</table>
</div>
