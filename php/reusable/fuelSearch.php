<?php
include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#searchButton').click(function() {
		searchFuelLoad();
	});
	
	$('#printButton').click(function() {
		printFuelLoad();
	});
	
	$('#exportButton').click(function() {
		alert("This feature is not ready");
	});
	
	$(document).on('dblclick', '.doubleClickable', function() {
		var url = '/mfi/php/nyros/viewFuelLoad.php?fuelLoadId=' + $(this).attr('fuelLoadId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
	});
});

function doAfterClose() {
	searchFuelLoad();
}

function printFuelLoad() {
	var data = getParams();
	var url = "../reports/genericReport.php?" + data + "&title=FuelLoad&sub=Search&type=fuel";
	var windowName = 'FuelLoad Search Report';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url,windowName,windowSize);
}

function searchFuelLoad() {
	var data = getParams();
	var url = "../retrieve/getFuelLoadsTable.php";
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
	
	dataArray['fuelLoadId'] = getVal('searchFuelLoadId');
	dataArray['broker'] = getVal('searchFuelLoadBroker');
	dataArray['truck'] = getVal('searchFuelLoadTruck');
	dataArray['start'] = evalDate(getVal('searchFuelLoadStart'));
	dataArray['end'] = evalDate(getVal('searchFuelLoadEnd'));
		
	return arrayToDataString(dataArray);
}
</script>
<div id="center-column">
	<div class='table' id='searchBar'>
		<img src="/trucking/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="/trucking/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing form" cellpadding="0" cellspacing="0" >
			<tr>
				<th class="full"  colspan='5'>Search Fuel Loads</th>
			</tr>
			<tr>
				<td class='first'>FuelLoad ID:</td>
				<td class='first'>Broker:</td>
				<td class='first'>Truck:</td>
				<td class='first'></td>
				<td class='first' rowspan='6'>
					<?php echo createCheckbox('c_fuelLoadId','FuelLoad Id','fuelLoadId', 'checked="checked" headerName="Id" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_brokerName','Broker Name','brokerName','checked="checked" headerName="Broker" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_brokerName','Broker Name','brokerPid','checked="checked" headerName="PID" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_truckName','Truck Name','truckNumber','checked="checked" headerName="Truck" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_fuelLoadDate','Date','fuelLoadDate', 'headerName="Date" varType="date"'); ?><br/>
					<?php echo createCheckbox('c_fuelLoadStart','Start','fuelLoadStart', 'headerName="Start" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_fuelLoadFinish','Finish','fuelLoadFinish', 'headerName="Finish" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_fuelLoadRegistered','Registered','fuelLoadRegistered', 'headerName="Registered" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_fuelLoadMileage','Mileage','fuelLoadMileage', 'headerName="Mileage" varType="string"'); ?><br/>
				</td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('searchFuelLoadId','',"size='10px'");?></td>
				<td> <?php echo arrayToSelect(brokersArray($conexion), 0, 'searchFuelLoadBroker', 'Broker');?></td>
				<td> <?php echo emptySelect('searchFuelLoadTruck','Truck');?></td>
				<td></td>
			</tr>
			<tr>
				<td class='first'>After Date:</td>
				<td class='first'>Before Date:</td>
				<td class='first'></td>
				<td></td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('searchFuelLoadStart','',"size='10px'");?></td>
				<td> <?php echo createInputText('searchFuelLoadEnd','',"size='10px'");?></td>
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
	
	<div class='table' id='fuelsTable' ></div>
</div>
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
