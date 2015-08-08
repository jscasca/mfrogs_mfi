<?php
include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#searchButton').click(function() {
		search();
	});
	
	$('#printButton').click(function() {
		print();
	});
	
	$('#exportButton').click(function() {
		alert("This feature is not ready");
	});
	
	$(document).on('dblclick', '.doubleClickable', function() {
		var url = '/mfi/php/nyros/viewTruck.php?truckId=' + $(this).attr('truckId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
	});
});

function doAfterClose() {
	search();
}

function print() {
	var data = getParams();
	var url = "../reports/genericReport.php?" + data + "&title=Truck&sub=Search&type=truck";
	var windowName = 'Truck Report';
	var windowSize = 'width=814,heigh=514,scrollbars=yes';
	window.open(url,windowName,windowSize);
}

function search() {
	var data = getParams();
	var url = "../retrieve/getTrucksTable.php";
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
	//var attributes = names.join('~');
	//var variables = types.join('~');
	//var headers = escape(headers.join('~'));
	
	dataArray['values'] = names.join('~');
	dataArray['headers'] = escape(headers.join('~'));
	dataArray['variables'] = types.join('~');
	
	dataArray['truckId'] = getVal('truckId');
	dataArray['brokerId'] = getVal('brokerId');
	dataArray['truckNumber'] = getVal('truckNumber');
	dataArray['truckBrand'] = getVal('truckBrand');
	dataArray['truckYear'] = getVal('truckYear');
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
				<th class="full"  colspan='5'>Search Brokers</th>
			</tr>
			<tr>
				<td class='first'>Truck ID:</td>
				<td class='first'>Broker:</td>
				<td class='first'>Truck Number:</td>
				<td class='first'>Truck Brand:</td>
				<td class='first' rowspan='6'>
					<?php echo createCheckbox('c_truckId','Truck Id','truckId', 'headerName="Id" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_brokerPid','Broker Pid','brokerPid', 'checked="checked" headerName="Broker PID" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_truckNumber','Truck Number','truckNumber','checked="checked" headerName="Truck Number" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_truckPlates','Plates','truckPlates','checked="checked" headerName="Truck Plates" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_truckBrand','Brand','truckBrand','headerName="Brand" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_truckYear','Year Model','truckYear','headerName="Year" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_truckTireSize','Tire Size','truckTireSize','headerName="Tire Size" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_truckSerial','Serial','truckSerial','headerName="Serial" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressLine1','Address','addressLine1', 'headerName="Address" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressCity','City','addressCity', 'headerName="City" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressState','State','addressState', 'headerName="State" varType="string"'); ?><br/>
					<?php echo createCheckbox('c_addressZip','Zip Code','addressZip', 'headerName="ZipCode" varType="string"'); ?><br/>
				</td>
			</tr>
			<tr class='bg'>
				<td> <?php echo createInputText('truckId','',"size='10px'");?></td>
				<td> <?php echo arrayToSelect(brokersArray($conexion), 0, 'brokerId', 'Broker');?></td>
				<td> <?php echo createInputText('truckNumber','',"size='10px'");?></td>
				<td> <?php echo createInputText('truckBrand','',"size='10px'");?></td>
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
				<td class='first'>Truck Year:</td>
				<td class='first'></td>
				<td class='first'></td>
				<td> <?php echo createSimpleButton('printButton', 'Print');?></td>
			</tr>
			<tr class='bg'>
				<td><?php echo createInputText('truckYear','',"size='10px'");?></td>
				<td></td>
				<td></td>
				<td> <?php echo createSimpleButton('searchButton', 'Search');?></td>
			</tr>
		</table>
	</div>
	
	<div class='table' id='trucksTable' ></div>
</div>
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
