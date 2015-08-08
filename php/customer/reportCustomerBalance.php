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
	var url = '../reports/showCustomerBalance.php?' + getParams();
	var windowName = 'Customer Balance';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url, windowName, windowSize);
}

function previewBalance() {
	var url = '../reports/showCustomerBalance.php?' + getParams();
	$('#previewFrame').empty();
	$('<iframe />',{
		name: 'Customer Balance',
		id: 'previewedFrame',
		src: url
	}).width('100%').height('2048px').appendTo('#previewFrame');
}

function getVal(objectId) {
	return escape($('#'+objectId).val());
}

function getParams() {
	var dataArray = new Array();
	dataArray['customer'] = getVal('customerId');
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
				<th class="full" colspan="3">Customer Balance</th>
			</tr>
			<tr>
				<td>Customer:</td>
				<td></td>
				<td><?php  echo createSimpleButton('printButton', 'Print');?></td>
			</tr>
			<tr class='bg'>
				<td><?php echo arrayToSelect(customersArray($conexion), 0, 'customerId', 'Customer');;?></td>
				<td></td>
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
