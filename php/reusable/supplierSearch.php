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
		var url = '/mfi/php/nyros/viewSupplier.php?supplierId=' + $(this).attr('supplierId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
	});
});

function doAfterClose() {
	searchSupplier();
}

function printSupplier() {
	var data = getParams();
	var url = "../reports/genericReport.php?" + data + "&title=Supplier&sub=Search&type=supplier";
	var windowName = 'Supplier Search Report';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url,windowName,windowSize);
}

function searchSupplier() {
	var data = getParams();
	var url = "../retrieve/getSuppliersTable.php";
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
	
	dataArray['supplierId'] = getVal('searchSupplierId');
	dataArray['name'] = getVal('searchSupplierName');
	dataArray['vendor'] = getVal('vendorId');
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
				<th class="full"  colspan='5'>Search Suppliers</th>
			</tr>
			<tr>
				<td class='first'>Supplier ID:</td>
				<td class='first'>Vendor:</td>
				<td class='first'>Supplier Name:</td>
				<td class='first'>Supplier Address:</td>
				<td class='first' rowspan='6'>
					<?php echo createCheckbox('c_supplierId','Supplier Id','supplierId', 'checked="checked" headerName="Id" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_supplierName','Supplier Name','supplierName','checked="checked" headerName="Name" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_vendorName','Vendor Name','vendorName','checked="checked" headerName="Vendor" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressLine1','Address','addressLine1', 'headerName="Address" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressCity','City','addressCity', 'headerName="City" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressState','State','addressState', 'headerName="State" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressZip','Zip Code','addressZip', 'headerName="ZipCode" varType="string"'); ?><br/>
				</td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('searchSupplierId','',"size='10px'");?></td>
				<td> <?php echo arrayToSelect(vendorsArray($conexion), 0, 'vendorId', 'Vendor');?></td>
				<td> <?php echo createInputText('searchSupplierName','',"size='10px'");?></td>
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
	
	<div class='table' id='suppliersTable' ></div>
</div>
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
