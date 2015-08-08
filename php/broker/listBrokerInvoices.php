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
	
	searchBrokerInvoices();
	
	$('#searchButton').click(function() {
		searchBrokerInvoices();
	});
	
	$('#brokerId').change(function() {
		getDriversOptions('driverId',$(this).val());
		$('#previewButton').removeAttr('disabled');
	});
	
	$(document).on('dblclick','.printable',function() {
		printBrokerInvoice($(this).attr("invoice"));
	});
});

function doAfterClose() { searchBrokerInvoices(); }

function searchBrokerInvoices() {
	var data = getParams();
	var url = '../retrieve/getBrokerInvoicesInteractive.php';
	getExternalTable(url, data);
}

function disableButton() {
	$('#submitButton').attr('disabled','disabled');
}

function enableButton() {
	$('#submitButton').removeAttr('disabled');
}

function getVal(objectId) {
	return escape($('#'+objectId).val());
}

function getParams() {
	var dataArray = new Array();
	dataArray['brokerId'] = getVal('brokerId');
	dataArray['driverId'] = getVal('driverId');
	dataArray['afterDate'] = evalDate(getVal('startDate'));
	dataArray['beforeDate'] = evalDate(getVal('endDate'));
	dataArray['paid'] = getVal('paid');
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
				<th class="full" colspan="5">View Broker Balance</th>
			</tr>
			<tr>
				<td>Broker/Driver:</td>
				<td>End date After Date:</td>
				<td>End date Before Date:</td>
				<td>Paid/Unpaid:</td>
				<td></td>
			</tr>
			<tr class='bg'>
				<td><?php echo arrayToSelect(brokersArray($conexion), 0, 'brokerId', 'Broker'); ?></td>
				<td rowspan='2'><?php echo createInputText('startDate','',"size='10px'");?></td>
				<td rowspan='2'><?php echo createInputText('endDate','',"size='10px'");?></td>
				<td rowspan='2'><?php echo arrayToSelect(array("0"=>"All","1" => "Paid","2"=>"Unpaid"),"0",'paid','Payment', true);?></td>
				<td rowspan='2'><?php  echo createSimpleButton('searchButton', 'Search','');?></td>
			</tr>
			<tr>
				<td><?php echo emptySelect("driverId","Driver");?></td>
			</tr>
			<?php 
			?>
		</table>
	</div>
	
	<div class='table' id='brokerInvoicesTable' ></div>
</div>
	
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
