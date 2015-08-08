<?php
include_once '../nyro_header.php';
$reportId = $_GET['reportId'];
?>
<script type="text/javascript">
$(document).ready(function() {
	
	//$('#submitButton').click(function() {
	//$(document).off('click#submitButton');
	$(document).off('click', '#submitButton');
	//$(document).one('click', '#submitButton', function() {
	$(document).on('click', '#submitButton', function() {
		//closeNM();
		submitNewBrokerPayCheck();
	});
});

function submitNewBrokerPayCheck() {
	disableButton()
	var data = getNewCheckParams();
	var url = '../submit/submitNewBrokerCheck.php';
	
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
	enableButton();
}

function getNewCheckParams() {
	var dataArray = new Array();
	dataArray['reportId'] = '<?php echo $reportId;?>';
	dataArray['number'] = getVal('newBrokerCheckNumber');
	dataArray['date'] = evalDate(getVal('newBrokerCheckDate'));
	dataArray['amount'] = getVal('newBrokerCheckAmount');
	
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
			<th colspan='2'>New Pay Cheque for Broker Invoice [<?php echo $reportId;?>]</th>
		</tr>
		<?php
		$reportTotal = getReportTotal($reportId, $conexion);
		$paidTotal = getReportPaid($reportId, $conexion);
		
		$reportInfo = getBasicReportInfo($conexion, $reportId);
		$balance = decimalPad($reportTotal - $paidTotal);
		
		$nextCheck = getNextAutoIncrement($conexion, 'paidcheques');
		//Truck
		$flag = true; 
		echo createFormRowTextField('Check Id', 'newBrokerChequeId', $flag ? 'class="bg"' : '', true, " disabled='disabled' size='10px' value='".$nextCheck."'"); $flag = !$flag; 
		echo createFormRowTextField('Check Number', 'newBrokerCheckNumber', $flag ? 'class="bg"' : '', false, "size='10px' "); $flag = !$flag; 
		echo createFormRowTextField('Check Date', 'newBrokerCheckDate', $flag ? 'class="bg"' : '', false, "size='10px' value='".to_MDY($reportInfo['reportDate'])."'"); $flag = !$flag; 
		echo createFormRowTextField('Amount', 'newBrokerCheckAmount', $flag ? 'class="bg"' : '', false, "size='10px' value='".$balance."'"); $flag = !$flag; 
				
		?>
	</table>
	<table>
		<tr>
			<td><?php echo createSimpleButton('submitButton', 'Submit'); ?></td>
		</tr>
	</table>
</div>
