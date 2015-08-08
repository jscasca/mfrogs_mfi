<?
$title = "MFI";
$subtitle = "Search";

$tab = "SEARCH";

include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>
<script type="text/javascript">
$(document).ready(function() {
	
	//$.fn.nyroModal();
	$.fn.nyroModal({
		showBg: function (nm, clb) {
			nm.elts.bg.fadeTo(250, 0.7, clb);
		}
	});
	
	$('#brokerId').change(function() {
		console.log($(this).val());
		getDriversOptions('driverId',$(this).val());
		getTrucksOptions('truckId',$(this).val());
	});
	
	$('#customerId').change(function() {
		getProjectsOptions('projectId',$(this).val());
	});
	
	$('#projectId').change(function() {
		getItemsOptions('itemId',$(this).val());
	});
	
	$('#vendorId').change(function() {
		getSuppliersOptions('supplierId',$(this).val());
	});
	
	$('#searchButton').click(function() {
		searchTickets();
	});
	
	$('#printButton').click(function() {
		printTickets();
	});
	
	$('#exportButton').click(function() {
		alert("This feature is not ready");
	});
	
	$(document).on('dblclick', '.doubleClickable', function() {
		var url = '/mfi/php/nyros/viewTicket.php?ticketId=' + $(this).attr('ticketId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
	});
});

function printTickets() {
	var data = getParams();
	var url = "../reports/genericReport.php?" + data + "&title=Ticket&sub=Search&type=ticket";
	var windowName = 'Ticket Report';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url,windowName,windowSize);
}

function searchTickets() {
	var data = getParams();
	var url = "../retrieve/getTicketsTable.php";
	getExternalTable(url, data);
}

function getParams() {
	var names = [];
	var headers = [];
	var types = [];
	$('input:checked').each(function() {
		names.push($(this).attr("name"));
		headers.push($(this).attr("headerName"));
		types.push($(this).attr("varType"));
	});
	var attributes = names.join('~');
	var variables = types.join('~');
	var headers = escape(headers.join('~'));
	
	var projectId = $('#projectId').val();
	var customerId = $('#customerId').val();
	var itemId = $('#itemId').val();
	var brokerId = $('#brokerId').val();
	var truckId = $('#truckId').val();
	var driverId = $('#driverId').val();
	var vendorId = $('#vendorId').val();
	var supplierId = $('#supplierId').val();
	
	var inInvoice = $('#inInvoice').val();
	var inReport = $('#inReport').val();
	var inSupplierInvoice = $('#inSupplierInvoice').val();
	
	var ticketMfi = $('#ticketMfi').val();
	var ticketNumber = $('#ticketNumber').val();
	
	var invoiceId = $('#invoiceNumber').val();
	var reportId = $('#reportNumber').val();
	var supplierInvoiceId = $('#supplierInvoiceNumber').val();
	
	var startDate = evalDate($('#startDate').val());
	var endDate = evalDate($('#endDate').val());
	
	var data = "projectId="+projectId+"&customerId="+customerId+"&itemId="+itemId+"&brokerId="+brokerId+
		"&truckId="+truckId+"&driverId="+driverId+"&vendorId="+vendorId+"&supplierId="+supplierId+
		"&invoiceId="+invoiceId+"&reportId="+reportId+"&supplierInvoiceId="+supplierInvoiceId+"&ticketMfi="+ticketMfi+
		"&invoiced="+inInvoice+"&reported="+inReport+"&supplied="+inSupplierInvoice+
		"&ticketNumber="+ticketNumber+"&startDate="+startDate+"&endDate="+endDate+"&values="+attributes+"&headers="+headers+"&variables="+variables;
		
	return data;
}

function getTickets() {
	var names = [];
	var headers = [];
	var types = [];
	$('input:checked').each(function() {
		names.push($(this).attr("name"));
		headers.push($(this).attr("headerName"));
		types.push($(this).attr("varType"));
	});
	var attributes = names.join('~');
	var variables = types.join('~');
	var headers = escape(headers.join('~'));
	
	var projectId = $('#projectId').val();
	var customerId = $('#customerId').val();
	var itemId = $('#itemId').val();
	var brokerId = $('#brokerId').val();
	var truckId = $('#truckId').val();
	var driverId = $('#driverId').val();
	var vendorId = $('#vendorId').val();
	var supplierId = $('#supplierId').val();
	
	var inInvoice = $('#inInvoice').val();
	var inReport = $('#inReport').val();
	var inSupplierInvoice = $('#inSupplierInvoice').val();
	
	var ticketMfi = $('#ticketMfi').val();
	var ticketNumber = $('#ticketNumber').val();
	
	var invoiceId = $('#invoiceNumber').val();
	var reportId = $('#reportNumber').val();
	var supplierInvoiceId = $('#supplierInvoiceNumber').val();
	
	var startDate = evalDate($('#startDate').val());
	var endDate = evalDate($('#endDate').val());
	
	var data = "projectId="+projectId+"&customerId="+customerId+"&itemId="+itemId+"&brokerId="+brokerId+
		"&truckId="+truckId+"&driverId="+driverId+"&vendorId="+vendorId+"&supplierId="+supplierId+
		"&invoiceId="+invoiceId+"&reportId="+reportId+"&supplierInvoiceId="+supplierInvoiceId+"&ticketMfi="+ticketMfi+
		"&invoiced="+inInvoice+"&reported="+inReport+"&supplied="+inSupplierInvoice+
		"&ticketNumber="+ticketNumber+"&startDate="+startDate+"&endDate="+endDate+"&values="+attributes+"&headers="+headers+"&variables="+variables;
	var url = "../retrieve/getTicketsTable.php";
	
	getExternalTable(url, data);
	
}
</script>
<!-- Search -->
<div id="center-column">
	<div class='table' id='searchBar'>
		<img src="/trucking/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="/trucking/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing form" cellpadding="0" cellspacing="0" >
			<tr>
				<th class="full"  colspan='5'>Search Tickets</th>
			</tr>
			<tr>
				<td class='first'>Customer:</td>
				<td class='first'>Broker:</td>
				<td class='first'>Vendor:</td>
				<td class='first'>MFI #:</td>
				<td class='first' rowspan='9'>
					<?php echo createCheckbox('c_customerName','Customer Name','customerName', 'headerName="Customer Name" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_projectName','Project Name','projectName','checked="checked" headerName="Project Name" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_brokerPid','Broker Pid','brokerPid', 'headerName="Broker PID" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_truckNumber','Truck Number','truckNumber', 'headerName="Truck Number" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_ticketDate','Ticket Date','ticketDate','checked="checked" headerName="Date" varType="date"'); ?><br/>
					<?php echo createCheckbox('c_ticketMfi','Ticket MFI','ticketMfi','checked="checked" headerName="MFI" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_ticketNumber','Ticket Dump','ticketNumber', 'headerName="Dump" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_supplierName','Supplier','supplierName', 'headerName="Supplier" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_itemDisplayFrom','From','itemDisplayFrom', 'headerName="From" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_itemDisplayTo','To','itemDisplayTo', 'headerName="To" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_itemMaterialName','Material','materialName', 'headerName="Material" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_itemMaterialPrice','Material Price','itemMaterialPrice', 'headerName="Material Cost" varType="double"'); ?><br/>
					<?php echo createCheckbox('c_ticketAmount','Amount','ticketAmount', 'headerName="Amount" varType="double"'); ?><br/>
					<?php echo createCheckbox('c_ticketBrokerAmount','Broker Amount','ticketBrokerAmount', 'headerName="Broker Amount" varType="double"'); ?><br/>
					<?php echo createCheckbox('c_itemBrokerCost','Broker Cost','itemBrokerCost', 'headerName="Broker Cost" varType="double"'); ?><br/>
					<?php echo createCheckbox('c_itemCustomerCost','Customer Price','itemCustomerCost', 'headerName="Customer Price" varType="double"'); ?><br/>
					<?php echo createCheckbox('c_invoiceId','Customer Invoice','invoiceId', 'headerName="Customer Invoice" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_reportId','Broker Invoice','reportId', 'headerName="Broker Invoice" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_supplierInvoceId','Supplier Invoice','supplierInvoiceId', 'headerName="Supplier Invoice" varType="string"'); ?><br/>
				</td>
			</tr>
			<tr class='bg'>
				<td>
				<?php
				$customerArray = customersArray($conexion);
				echo arrayToSelect($customerArray, 0, 'customerId', 'Customer');
				?>
				</td>
				<td>
				<?php
				$brokerArray = brokersArray($conexion);
				echo arrayToSelect($brokerArray, 0, 'brokerId', 'Broker');
				?>
				</td>
				<td>
				<?php
				$vendorArray = vendorsArray($conexion);
				echo arrayToSelect($vendorArray, 0, 'vendorId', 'Vendor');
				?>
				</td>
				<td><?php echo createInputText('mfiNumber','',"size='6px'");?></td>
			</tr>
			<tr>
				<td class='first'>Project:</td>
				<td class='first'>Truck:</td>
				<td class='first'>Supplier:</td>
				<td class='first'>Dump:</td>
			</tr>
			<tr class='bg'>
				<td> <?php echo emptySelect("projectId","Project");?></td>
				<td> <?php echo emptySelect("truckId","Truck");?></td>
				<td> <?php echo emptySelect("supplierId","Supplier");?></td>
				<td> <?php echo createInputText('dumpNumber','',"size='6px'");?></td>
			</tr>
			<tr>
				<td class='first'>Item:</td>
				<td class='first'>Driver:</td>
				<td class='first'>Start Date:</td>
				<td class='first'>End Date:</td>
			</tr>
			<tr class='bg'>
				<td> <?php echo emptySelect("itemId","Item");?></td>
				<td> <?php echo emptySelect("driverId","Driver");?></td>
				<td> <?php echo createInputText('startDate','',"size='6px'");?></td>
				<td> <?php echo createInputText('endDate','',"size='6px'");?></td>
			</tr>
			<tr>
				<td class='first'>Customer Invoice:</td>
				<td class='first'>Broker Invoice:</td>
				<td class='first'>Supplier Invoice:</td>
				<td> <?php echo createSimpleButton('exportButton', 'Export');?></td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('invoiceNumber','',"size='6px'");?></td>
				<td> <?php echo createInputText('reportNumber','',"size='6px'");?></td>
				<td> <?php echo createInputText('supplierInvoiceNumber','',"size='6px'");?></td>
				<td> <?php echo createSimpleButton('printButton', 'Print');?></td>
			</tr>
			<tr>
				<td><?php echo arrayToSelect(array("Any","Invoiced","Not Invoiced"),0,'inInvoice','Invoice',true); ?></td>
				<td><?php echo arrayToSelect(array("Any","Invoiced","Not Invoiced"),0,'inReport','Report',true); ?></td>
				<td><?php echo arrayToSelect(array("Any","Invoiced","Not Invoiced"),0,'inSupplierInvoice','Supplier Invoice',true); ?></td>
				<td> <?php echo createSimpleButton('searchButton', 'Search');?></td>
			</tr>
		</table>
	</div>
	
	<div class='table' id='ticketsTable' ></div>
</div>	
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
