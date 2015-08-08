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
	
	$('#searchProjectCustomer').change(function() {
		getContactsOptions('searchProjectContact',$(this).val());
	});
	
	$('#searchButton').click(function() {
		searchProjects();
	});
	
	$('#printButton').click(function() {
		printProjects();
	});
	
	$('#exportButton').click(function() {
		alert("This feature is not ready");
	});
	
	$(document).on('dblclick', '.doubleClickable', function() {
		var url = '/mfi/php/nyros/viewProject.php?projectId=' + $(this).attr('projectId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
	});
});

function doAfterClose() {
	searchProjects();
}

function printProjects() {
	var data = getParams();
	var url = "../reports/genericReport.php?" + data + "&title=Project&sub=Search&type=project";
	var windowName = 'Project Report';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url,windowName,windowSize);
}

function searchProjects() {
	var data = getParams();
	var url = "../retrieve/getProjectsTable.php";
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
	
	dataArray['projectId'] = getVal('searchProjectId');
	dataArray['name'] = getVal('searchProjectName');
	dataArray['customer'] = getVal('searchProjectCustomer');
	dataArray['contact'] = getVal('searchProjectContact');
	
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
				<th class="full"  colspan='5'>Search Projects</th>
			</tr>
			<tr>
				<td class='first'>Project ID:</td>
				<td class='first'>Project PID:</td>
				<td class='first'>Project Name:</td>
				<td class='first'>Address:</td>
				<td class='first' rowspan='6'>
					<?php echo createCheckbox('c_projectId','Project Id','projectId', 'checked="checked" headerName="Id" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_projectName','Project Name','projectName','checked="checked" headerName="Project Name" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_customerId','Customer Id','customerId', ' headerName="Customer ID" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_customerName','Customer Name','customerName', 'checked="checked" headerName="Customer" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_contactId','Contact Id','contactId', ' headerName="Contact ID" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_contactName','Contact Name','contactName', 'checked="checked" headerName="Contact" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressLine1','Address','addressLine1', 'headerName="Address" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressCity','City','addressCity', 'headerName="City" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressState','State','addressState', 'headerName="State" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressZip','Zip Code','addressZip', 'headerName="ZipCode" varType="string"'); ?><br/>
				</td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('searchProjectId','',"size='10px'");?></td>
				<td> <?php echo createInputText('searchProjectPid','',"size='10px'");?></td>
				<td> <?php echo createInputText('searchProjectName','',"size='10px'");?></td>
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
				<td class='first'>Customer:</td>
				<td class='first'>Contact:</td>
				<td class='first'></td>
				<td> <?php echo createSimpleButton('printButton', 'Print');?></td>
			</tr>
			<tr class='bg'>
				<td><?php echo arrayToSelect(customersArray($conexion),0,'searchProjectCustomer','Customer');?></td>
				<td><?php echo emptySelect('searchProjectContact','Contact');?></td>
				<td></td>
				<td> <?php echo createSimpleButton('searchButton', 'Search');?></td>
			</tr>
		</table>
	</div>
	
	<div class='table' id='projectsTable' ></div>
</div>
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
