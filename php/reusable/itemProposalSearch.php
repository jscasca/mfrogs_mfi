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
	
	$('#customerId').change(function() {
		getProjectsOptions('projectId',$(this).val());
	});
	
	$('#vendorId').change(function() {
		getSuppliersOptions('supplierId',$(this).val());
	});
	
	$('#searchButton').click(function() {
		searchItems();
	});
	
	$('#printButton').click(function() {
		printItems();
	});
	
	$('#exportButton').click(function() {
		alert("This feature is not ready");
	});
	
	$(document).on('dblclick', '.doubleClickable', function() {
		var url = '/mfi/php/nyros/viewItemProposal.php?itemProposalId=' + $(this).attr('itemProposalId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
	});
});

function doAfterClose() {
	searchItems();
}

function printItems() {
	var data = getParams();
	var url = "../reports/genericReport.php?" + data + "&title=" + escape("Item Proposal") + "&sub=Search&type=itemProposal";
	var windowName = 'Item Proposal Report';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url,windowName,windowSize);
}

function searchItems() {
	var data = getParams();
	var url = "../retrieve/getItemProposalsTable.php";
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
	
	dataArray['customerId'] = getVal('customerId');
	dataArray['projectId'] = getVal('projectId');
	dataArray['vendorId'] = getVal('vendorId');
	dataArray['supplierId'] = getVal('supplierId');
	dataArray['materialId'] = getVal('materialId');
	dataArray['itemProposalId'] = getVal('searchItemProposalId');
	dataArray['type'] = getVal('searchItemProposalType');
		
	return arrayToDataString(dataArray);
}
</script>
<div id="center-column">
	<div class='table' id='searchBar'>
		<img src="/trucking/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="/trucking/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing form" cellpadding="0" cellspacing="0" >
			<tr>
				<th class="full"  colspan='5'>Search Item Proposal</th>
			</tr>
			<tr>
				<td class='first'>Customer:</td>
				<td class='first'>Vendor:</td>
				<td class='first'>Material:</td>
				<td class='first'>Item Id:</td>
				<td class='first' rowspan='6'>
					<?php /*createCheckbox($id, $label, $name = '', $attributes = '')*/?>
					<?php echo createCheckbox('c_itemId','Item','itemProposalId', 'checked="checked" headerName="Item Proposal" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_customerName','Customer','customerName', 'headerName="Customer" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_projectId','Project (id)','projectId', 'headerName="Project" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_projectName','Project','projectName', 'headerName="Project" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_vendorName','Vendor','vendorName', 'headerName="Vendor" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_supplierName','Supplier','supplierName', 'headerName="Supplier" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_materialName','Material','materialName', 'checked="checked" headerName="Material" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_materialPrice','Material Price','itemProposalMaterialPrice', 'headerName="Material Price" varType="double"'); ?><br/>
					<?php echo createCheckbox('c_brokerCost','Broker Cost','itemProposalBrokerCost', 'headerName="Broker Cost" varType="double"'); ?><br/>
					<?php echo createCheckbox('c_customerCost','Customer Cost','itemProposalCustomerCost', 'headerName="Customer Cost" varType="double"'); ?><br/>
					<?php echo createCheckbox('c_itemDescription','Description','itemProposalDescription', 'headerName="Number" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_itemType','Item Type','itemProposalTypeString', 'headerName="Type" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_itemDate','Creation Date','itemProposalCreationDate', 'headerName="Date" varType="date"'); ?><br/>
				</td>
			</tr>
			<tr class='bg'>
				<td><?php echo arrayToSelect(customersArray($conexion), 0, 'customerId', 'Customer');?></td>
				<td><?php echo arrayToSelect(vendorsArray($conexion), 0, 'vendorId', 'Vendor');?></td>
				<td><?php echo arrayToSelect(materialsArray($conexion), 0, 'materialId', 'Material');?></td>
				<td><?php echo createInputText('searchItemId','',"size='6px'");?></td>
			</tr>
			<tr>
				<td class='first'>Project</td>
				<td class='first'>Supplier</td>
				<td class='first'>Type:</td>
				<td><?php echo createSimpleButton('exportButton', 'Export');?></td>
			</tr>
			<tr class='bg'>
				<td> <?php echo emptySelect("projectId","Project");?></td>
				<td> <?php echo emptySelect("supplierId","Supplier");?></td>
				<td><?php echo arrayToSelect(array("Loads","Tons","Hours","Any"),3,"searchItemProposalType","",true);?></td>
				<td><?php echo createSimpleButton('printButton', 'Print');?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td><?php echo createSimpleButton('searchButton', 'Search');?></td>
			</tr>
		</table>
	</div>
	
	<div class='table' id='itemProposalsTable' ></div>
</div>
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
