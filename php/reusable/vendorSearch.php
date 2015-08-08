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
		searchVendors();
	});
	
	$('#printButton').click(function() {
		printVendors();
	});
	
	$('#exportButton').click(function() {
		alert("This feature is not ready");
	});
	
	$(document).on('dblclick', '.doubleClickable', function() {
		var url = '/mfi/php/nyros/viewVendor.php?vendorId=' + $(this).attr('vendorId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
	});
});

function doAfterClose() {
	searchVendors();
}

function printVendors() {
	var data = getParams();
	var url = "../reports/genericReport.php?" + data + "&title=Vendor&sub=Search&type=vendor";
	var windowName = 'Vendor Report';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url,windowName,windowSize);
}

function searchVendors() {
	var data = getParams();
	var url = "../retrieve/getVendorsTable.php";
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
	
	dataArray['vendorId'] = getVal('searchVendorId');
	dataArray['name'] = getVal('searchVendorName');
	
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
				<th class="full"  colspan='5'>Search Vendors</th>
			</tr>
			<tr>
				<td class='first'>Vendor ID:</td>
				<td class='first'>Vendor PID:</td>
				<td class='first'>Vendor Name:</td>
				<td class='first'>Address:</td>
				<td class='first' rowspan='6'>
					<?php echo createCheckbox('c_vendorId','Vendor Id','vendorId', 'checked="checked" headerName="Id" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_vendorPid','Vendor Pid','vendorPid', ' headerName="Vendor PID" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_vendorName','Vendor Name','vendorName','checked="checked" headerName="Vendor Name" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressLine1','Address','addressLine1', 'headerName="Address" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressCity','City','addressCity', 'headerName="City" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressState','State','addressState', 'headerName="State" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressZip','Zip Code','addressZip', 'headerName="ZipCode" varType="string"'); ?><br/>
				</td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('searchVendorId','',"size='10px'");?></td>
				<td> <?php echo createInputText('searchVendorPid','',"size='10px'");?></td>
				<td> <?php echo createInputText('searchVendorName','',"size='10px'");?></td>
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
	
	<div class='table' id='vendorsTable' ></div>
</div>
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
