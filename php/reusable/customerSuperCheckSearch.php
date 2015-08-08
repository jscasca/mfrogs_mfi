<?php
include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#searchButton').click(function() {
		searchSupplier();
	});
	
	$('#printButton').click(function() {
		printSupplier();
	});
	
	$('#exportButton').click(function() {
		alert("This feature is not ready");
	});
	
	$(document).on('dblclick', '.doubleClickable', function() {
		var url = '/mfi/php/nyros/viewCustomerSuperCheck.php?customerSuperCheckId=' + $(this).attr('customerSuperCheckId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
	});
});

function doAfterClose() {
	searchSupplier();
}

function printSupplier() {
	var data = getParams();
	var url = "../reports/genericReport.php?" + data + "&title=Payments&sub=Search&type=customerSuperCheck";
	var windowName = 'Customer Payments Search Report';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url,windowName,windowSize);
}

function searchSupplier() {
	var data = getParams();
	var url = "../retrieve/getCustomerSuperChecksTable.php";
	getExternalTable(url, data);
}

function getParams() {
	var dataArray = new Array();
	var names = [];
	var headers = [];
	var types = [];
	$('.checkableHeaders:checked').each(function() {
		names.push($(this).attr("name"));
		headers.push($(this).attr("headerName"));
		types.push($(this).attr("varType"));
	});
	
	dataArray['values'] = names.join('~');
	dataArray['headers'] = escape(headers.join('~'));
	dataArray['variables'] = types.join('~');
	
	dataArray['id'] = getVal('searchSuperCheckId');
	dataArray['customer'] = getVal('searchSuperCheckCustomer');
	dataArray['number'] = getVal('searchSuperCheckNumber');
	dataArray['afterDate'] = getVal('searchSuperCheckAfterDate');
	dataArray['beforeDate'] = getVal('searchSuperCheckBeforeDate');
		
	return arrayToDataString(dataArray);
}
</script>
<div id="center-column">
	<div class='table' id='searchBar'>
		<img src="/trucking/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="/trucking/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing form" cellpadding="0" cellspacing="0" >
			<tr>
				<th class="full"  colspan='5'>Search Customer Checks</th>
			</tr>
			<tr>
				<td class='first'>Check ID:</td>
				<td class='first'>Customer:</td>
				<td class='first'>Number:</td>
				<td class='first'></td>
				<td class='first' rowspan='6'>
					<?php echo createCheckbox('c_checkId','Check Id','customerSuperCheckId', 'checked="checked" headerName="Id" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_checkCustomer','Customer','customerName','checked="checked" headerName="Customer" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_checkNumber','Number','customerSuperCheckNumber','checked="checked" headerName="Number" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_checkAmount','Amount','customerSuperCheckAmount', 'headerName="Amount" varType="double"'); ?><br/>
					<?php echo createCheckbox('c_checkDate','Date','customerSuperCheckDate', 'headerName="Date" varType="date"'); ?><br/>
					<?php echo createCheckbox('c_checkCredit','Credit','checkCredit', 'headerName="Credit" varType="double"'); ?><br/>
				</td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('searchSuperCheckId','',"size='10px'");?></td>
				<td> <?php echo arrayToSelect(customersArray($conexion), 0, 'searchSuperCheckCustomer', 'Customer');?></td>
				<td> <?php echo createInputText('searchSuperCheckNumber','',"size='10px'");?></td>
				<td></td>
			</tr>
			<tr>
				<td>After Date:</td>
				<td>Before Date:</td>
				<td></td>
				<td></td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('searchSuperCheckAfterDate','',"size='10px'");?></td>
				<td> <?php echo createInputText('searchSuperCheckBeforeDate','',"size='10px'");?></td>
				<td></td>
				<td> <?php echo createSimpleButton('exportButton', 'Export');?></td>
			</tr>
			<tr>
				<td class='first'></td>
				<td class='first'></td>
				<td class='first'></td>
				<td> <?php echo createSimpleButton('printButton', 'Print');?></td>
			</tr>
			<tr class='bg'>
				<td></td>
				<td></td>
				<td></td>
				<td> <?php echo createSimpleButton('searchButton', 'Search');?></td>
			</tr>
		</table>
	</div>
	
	<div class='table' id='customerSuperChecksTable' ></div>
</div>
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
