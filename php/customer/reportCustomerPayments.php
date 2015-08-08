<?
$title = "MFI";
$subtitle = "Customer";

$tab = "CUSTOMER";

include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>

<script type="text/javascript">
$(document).ready(function() {
	
	$('#printButton').click(function() {
		printBalance();
	});
	
	$('#previewButton').click(function() {
		previewBalance();
	});
});

function printBalance() {
	$('#previewFrame').empty();
	var url = '../reports/showCustomerPayments.php?' + getParams();
	var windowName = 'Customer Payments';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url, windowName, windowSize);
}

function previewBalance() {
	var url = '../reports/showCustomerPayments.php?' + getParams();
	$('#previewFrame').empty();
	$('<iframe />',{
		name: 'Customer Payments',
		id: 'previewedFrame',
		src: url
	}).width('100%').height('2048px').appendTo('#previewFrame');
}

function getVal(objectId) {
	return escape($('#'+objectId).val());
}

function getParams() {
	var dataArray = new Array();
	dataArray['customerId'] = getVal('customerId');
	dataArray['startDate'] = getVal('startDate');
	dataArray['endDate'] = getVal('endDate');
	var data = "";
	var glue = "";
	for(key in dataArray) {
		data += glue + key + "=" + dataArray[key];
		glue = "&";
	}
	console.log(data);
	return data;
}
</script>
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="4">Customer Payments</th>
			</tr>
			<tr>
				<td>Customer:</td>
				<td>Starting Date:</td>
				<td>Ending Date:</td>
				<td><?php  echo createSimpleButton('printButton', 'Print');?></td>
			</tr>
			<tr class='bg'>
				<td><?php echo arrayToSelect(brokersArray($conexion), 0, 'customerId', 'Customer');;?></td>
				<td><?php echo createInputText('startDate','',"size='10px'");?></td>
				<td><?php echo createInputText('endDate','',"size='10px'");?></td>
				<td><?php  echo createSimpleButton('previewButton', 'Preview');?></td>
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
