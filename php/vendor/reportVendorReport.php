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
	
	$('#printButton').click(function() {
		printBalance();
	});
	
	$('#previewButton').click(function() {
		previewBalance();
	});
	
	$('#vendorId').change(function() {
		console.log($(this).val());
		getSuppliersOptions('supplierId',$(this).val());
	});
});

function printBalance() {
	$('#previewFrame').empty();
	var data = getParams();
	var brokerId = getVal('brokerId');
	var url = "";
	if(brokerId == 0) {
		url = '../reports/showGeneralBrokerBalance.php?' + data;
	} else {
		url = '../reports/showDetailedBrokerBalance.php?' + data;
	}
	var windowName = 'Detailed Broker Balance';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url, windowName, windowSize);
}

function previewBalance() {
	$('#previewFrame').empty();
	var data = getParams();
	var brokerId = getVal('brokerId');
	var url = '../reports/showVendorReport.php?' + data;
	
	$('<iframe />',{
		name: 'Vendor Balance',
		id: 'previewedFrame',
		src: url
	}).width('100%').height('2048px').appendTo('#previewFrame');
}

function getParams() {
	var dataArray = new Array();
	dataArray['vendorId'] = getVal('vendorId');
	dataArray['supplierId'] = getVal('supplierId');
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
				<th class="full" colspan="4">View Broker Balance</th>
			</tr>
			<tr>
				<td>Vendor / Supplier:</td>
				<td>Starting Date:</td>
				<td>Ending Date:</td>
				<td><?php  echo createSimpleButton('printButton', 'Print');?></td>
			</tr>
			<tr class='bg'>
				<td><?php echo arrayToSelect(vendorsArray($conexion), 0, 'vendorId', 'Vendor'); ?></td>
				<td rowspan='2'><?php echo createInputText('startDate','',"size='10px'");?></td>
				<td rowspan='2'><?php echo createInputText('endDate','',"size='10px'");?></td>
				<td rowspan='2'><?php  echo createSimpleButton('previewButton', 'Preview');?></td>
			</tr>
			<tr>
				<td><?php echo emptySelect("supplierId","Supplier");?></td>
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
