<?
$title = "MFI";
$subtitle = "Vendor";

$tab = "VENDOR";

include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>

<script type="text/javascript">
$(document).ready(function() {
	
	searchVendorInvoices();
	
	$('#searchButton').click(function() {
		searchVendorInvoices();
	});
	
	$('#vendorId').change(function() {
		getSuppliersOptions('supplierId',$(this).val());
		$('#previewButton').removeAttr('disabled');
	});
	
	$(document).on('dblclick','.printable',function() {
		printSupplierInvoice($(this).attr("invoice"));
	});
});

function doAfterClose() { searchVendorInvoices(); }

function searchVendorInvoices() {
	var data = getParams();
	var url = '../retrieve/getVendorInvoicesInteractive.php';
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
	dataArray['vendorId'] = getVal('vendorId');
	dataArray['supplierId'] = getVal('supplierId');
	dataArray['afterDate'] = evalDate(getVal('startDate'));
	dataArray['beforeDate'] = evalDate(getVal('endDate'));
	dataArray['paid'] = getVal('paid');
	return arrayToDataString(dataArray);
}
</script>
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="5">View Vendor Balance</th>
			</tr>
			<tr>
				<td>Vendor/Driver:</td>
				<td>End date After Date:</td>
				<td>End date Before Date:</td>
				<td>Paid/Unpaid:</td>
				<td></td>
			</tr>
			<tr class='bg'>
				<td><?php echo arrayToSelect(vendorsArray($conexion), 0, 'vendorId', 'Vendor'); ?></td>
				<td rowspan='2'><?php echo createInputText('startDate','',"size='10px'");?></td>
				<td rowspan='2'><?php echo createInputText('endDate','',"size='10px'");?></td>
				<td rowspan='2'><?php echo arrayToSelect(array("0"=>"All","1" => "Paid","2"=>"Unpaid"),"0",'paid','Payment', true);?></td>
				<td rowspan='2'><?php  echo createSimpleButton('searchButton', 'Search','');?></td>
			</tr>
			<tr>
				<td><?php echo emptySelect("supplierId","Supplier");?></td>
			</tr>
			<?php 
			?>
		</table>
	</div>
	
	<div class='table' id='supplierInvoicesTable' ></div>
</div>
	
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
