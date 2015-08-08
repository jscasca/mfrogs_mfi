<?
$title = "MFI";
$subtitle = "Broker";

$tab = "BROKER";

include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>

<script type="text/javascript">
$(document).ready(function() {
	
	$('#submitButton').click(function() {
		submitBrokerInvoice();
	});
	
	$('#previewButton').click(function() {
		previewBalance();
	});
	
	$('#brokerId').change(function() {
		getDriversOptions('driverId',$(this).val());
		$('#previewButton').removeAttr('disabled');
	});
});

function disableButton() {
	$('#submitButton').attr('disabled','disabled');
}

function enableButton() {
	$('#submitButton').removeAttr('disabled');
}

function doAfterSubmit(data) {
	var obj = jQuery.parseJSON(data);
	if(obj.code == 0) {
		//went without problems
		$('#previewFrame').empty();
		console.log(data);
	} else {
		
	}
}

function submitBrokerInvoice() {
	disableButton();
	var url = '../submit/submitBrokerInvoice.php';
	var data = getParams();
	submitDataToUrl(url, data);
}

function previewBalance() {
	var url = '../reports/previewBrokerReport.php?' + getParams();
	$('#previewFrame').empty();
	$('<iframe />',{
		name: 'Broker Invoice',
		id: 'previewedFrame',
		src: url
	}).width('100%').height('2048px').appendTo('#previewFrame');
	$('#submitButton').removeAttr('disabled');
}

function getVal(objectId) {
	return escape($('#'+objectId).val());
}

function getParams() {
	var dataArray = new Array();
	dataArray['brokerId'] = getVal('brokerId');
	dataArray['driverId'] = getVal('driverId');
	dataArray['startDate'] = getVal('startDate');
	dataArray['endDate'] = getVal('endDate');
	var data = "";
	var glue = "";
	for(key in dataArray) {
		data += glue + key + "=" + dataArray[key];
		glue = "&";
	}
	return data;
}
</script>
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="4">View Broker Balance</th>
			</tr>
			<tr>
				<td>Broker/Driver:</td>
				<td>Starting Date:</td>
				<td>Ending Date:</td>
				<td><?php  echo createSimpleButton('submitButton', 'Submit','','disabled="disabled"');?></td>
			</tr>
			<tr class='bg'>
				<td><?php echo arrayToSelect(brokersArray($conexion), 0, 'brokerId', 'Broker'); ?></td>
				<td rowspan='2'><?php echo createInputText('startDate','',"size='10px'");?></td>
				<td rowspan='2'><?php echo createInputText('endDate','',"size='10px'");?></td>
				<td rowspan='2'><?php  echo createSimpleButton('previewButton', 'Preview','','disabled="disabled"');?></td>
			</tr>
			<tr>
				<td><?php echo emptySelect("driverId","Driver");?></td>
			</tr>
			<?php 
			?>
		</table>
	</div>
	
	<div class='iframes' id='previewFrame'></div>
</div>
	
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
