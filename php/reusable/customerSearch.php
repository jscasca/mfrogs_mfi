<?php
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
	
	$('#searchButton').click(function() {
		searchCustomers();
	});
	
	$('#printButton').click(function() {
		printCustomers();
	});
	
	$('#exportButton').click(function() {
		alert("This feature is not ready");
	});
	
	$(document).on('dblclick', '.doubleClickable', function() {
		var url = '/mfi/php/nyros/viewCustomer.php?customerId=' + $(this).attr('customerId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
	});
});

function doAfterClose() {
	searchCustomers();
}

function printCustomers() {
	var data = getParams();
	var url = "../reports/genericReport.php?" + data + "&title=Customer&sub=Search&type=customer";
	var windowName = 'Customer Report';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url,windowName,windowSize);
}

function searchCustomers() {
	var data = getParams();
	var url = "../retrieve/getCustomersTable.php";
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
	
	var dataArray = new Array();
	
	dataArray['values'] = names.join('~');
	dataArray['headers'] = escape(headers.join('~'));
	dataArray['variables'] = types.join('~');
	
	dataArray['customerId'] = getVal('searchCustomerId');
	dataArray['name'] = getVal('searchCustomerName');
	dataArray['term'] = getVal('searchCustomerTerm');
	
	dataArray['addressLine1'] = getVal('addressLine1');
	dataArray['addressCity'] = getVal('addressCity');
	dataArray['addressState'] = getVal('stateId');
	dataArray['addressZip'] = getVal('addressZip');
		
	return arrayToDataString(dataArray);
}
</script>
<div id="center-column">
	<div class='table' id='searchBar'>
		<img src="/trucking/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="/trucking/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing form" cellpadding="0" cellspacing="0" >
			<tr>
				<th class="full"  colspan='5'>Search Customers</th>
			</tr>
			<tr>
				<td class='first'>Customer ID:</td>
				<td class='first'>Customer PID:</td>
				<td class='first'>Customer Name:</td>
				<td class='first'>Address:</td>
				<td class='first' rowspan='6'>
					<?php echo createCheckbox('c_customerId','Customer Id','customerId', 'checked="checked" headerName="Id" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_customerPid','Customer Pid','customerPid', ' headerName="Customer PID" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_customerName','Customer Name','customerName','checked="checked" headerName="Customer Name" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressLine1','Address','addressLine1', 'headerName="Address" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressCity','City','addressCity', 'headerName="City" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressState','State','addressState', 'headerName="State" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressZip','Zip Code','addressZip', 'headerName="ZipCode" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_customerPayment','Payment','termName', 'headerName="Terms" varType="string"'); ?><br/>
				</td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('searchCustomerId','',"size='10px'");?></td>
				<td> <?php echo createInputText('searchCustomerPid','',"size='10px'");?></td>
				<td> <?php echo createInputText('searchCustomerName','',"size='10px'");?></td>
				<td> <?php echo createInputText('addressLine1','',"size='10px'");?></td>
			</tr>
			<tr>
				<td class='first'>City:</td>
				<td class='first'>State:</td>
				<td class='first'>Zip Code:</td>
				<td></td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('addressCity','',"size='10px'");?></td>
				<td> <?php echo createStateSelect($conexion);?></td>
				<td> <?php echo createInputText('addressZip','',"size='10px'");?></td>
				<td> <?php echo createSimpleButton('exportButton', 'Export');?></td>
			</tr>
			<tr>
				<td class='first'></td>
				<td class='first'></td>
				<td class='first'>Payment Method:</td>
				<td> <?php echo createSimpleButton('printButton', 'Print');?></td>
			</tr>
			<tr class='bg'>
				<td></td>
				<td></td>
				<td><?php echo arrayToSelect(termsArray($conexion),0,'searchCustomerTerm','Payment')?></td>
				<td> <?php echo createSimpleButton('searchButton', 'Search');?></td>
			</tr>
		</table>
	</div>
	
	<div class='table' id='customersTable' ></div>
</div>
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
