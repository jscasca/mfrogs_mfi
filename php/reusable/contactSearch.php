<?php
include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#searchButton').click(function() {
		searchContact();
	});
	
	$('#printButton').click(function() {
		printContact();
	});
	
	$('#exportButton').click(function() {
		alert("This feature is not ready");
	});
	
	$(document).on('dblclick', '.doubleClickable', function() {
		var url = '/mfi/php/nyros/viewContact.php?contactId=' + $(this).attr('contactId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
	});
});

function doAfterClose() {
	searchContact();
}

function printContact() {
	var data = getParams();
	var url = "../reports/genericReport.php?" + data + "&title=Contact&sub=Search&type=contact";
	var windowName = 'Contact Search Report';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url,windowName,windowSize);
}

function searchContact() {
	var data = getParams();
	var url = "../retrieve/getContactsTable.php";
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
	
	dataArray['contactId'] = getVal('searchContactId');
	dataArray['name'] = getVal('searchContactName');
	dataArray['customer'] = getVal('searchCustomerId');
	dataArray['material'] = getVal('materialId');
	
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
				<th class="full"  colspan='5'>Search Contacts</th>
			</tr>
			<tr>
				<td class='first'>Contact ID:</td>
				<td class='first'>Customer:</td>
				<td class='first'>Contact Name:</td>
				<td class='first'>Contact Address:</td>
				<td class='first' rowspan='6'>
					<?php echo createCheckbox('c_contactId','Contact Id','contactId', 'checked="checked" headerName="Id" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_contactName','Contact Name','contactName','checked="checked" headerName="Name" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_customerName','Customer Name','customerName','checked="checked" headerName="Customer" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressLine1','Address','addressLine1', 'headerName="Address" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressCity','City','addressCity', 'headerName="City" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressState','State','addressState', 'headerName="State" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressZip','Zip Code','addressZip', 'headerName="ZipCode" varType="string"'); ?><br/>
				</td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('searchContactId','',"size='10px'");?></td>
				<td> <?php echo arrayToSelect(customersArray($conexion), 0, 'searchCustomerId', 'Customer');?></td>
				<td> <?php echo createInputText('searchContactName','',"size='10px'");?></td>
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
	
	<div class='table' id='contactsTable' ></div>
</div>
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
