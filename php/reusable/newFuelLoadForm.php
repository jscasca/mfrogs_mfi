<?php

?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#newFuelLoadCustomerId').change(function() {
		getProjectsOptions('newFuelLoadProjectId',$(this).val());
	});
	
	$('#submitNewFuelLoad').click(function() {
		submitNewFuelLoad();
	});
	
	$('#newFuelLoadBroker').change(function() {
		getTrucksOptions('newFuelLoadTruck',$(this).val());
	});
	
});

function submitNewFuelLoad() {
	var data = getNewFuelLoadParams();
	var url = '../submit/submitNewFuelLoad.php';
	
	submitNewObject(url, data);
}

function getNewFuelLoadParams() {
	var dataArray = new Array();
	dataArray['date'] = evalDate(getVal('newFuelLoadDate'));
	dataArray['broker'] = getVal('newFuelLoadBroker');
	dataArray['truck'] = getVal('newFuelLoadTruck');
	dataArray['driver'] = getVal('newFuelLoadDriver');
	dataArray['start'] = getVal('newFuelLoadStart');
	dataArray['finish'] = getVal('newFuelLoadFinish');
	dataArray['registered'] = getVal('newFuelLoadRegistered');
	dataArray['miles'] = getVal('newFuelLoadMiles');
	
	
	return arrayToDataString(dataArray);
}
</script>
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th colspan='7'>New Fuel Load</th>
			</tr>
			<tr>
				<td><strong>Date:</strong></td>
				<td><strong>Broker/Truck:</strong></td>
				<td><strong>Driver:</strong></td>
				<td><strong>Start:</strong></td>
				<td><strong>Finish:</strong></td>
				<td><strong>Registered:</strong></td>
				<td><strong>Miles:</strong></td>
			</tr>
			<tr class='bg'>
				<td><?php echo createInputText('newFuelLoadDate','',"size='6px' tabindex='1'");?></td>
				<td><?php echo arrayToSelect(brokersArray($conexion),'0','newFuelLoadBroker',"Broker", false, "tabindex='2'");?></td>
				<td><?php echo createInputText('newFuelLoadDriver','',"size='6px' tabindex='4'");?></td>
				<td><?php echo createInputText('newFuelLoadStart','',"size='6px' tabindex='5'");?></td>
				<td><?php echo createInputText('newFuelLoadFinish','',"size='6px' tabindex='6'");?></td>
				<td><?php echo createInputText('newFuelLoadRegistered','',"size='6px' tabindex='7'");?></td>
				<td><?php echo createInputText('newFuelLoadMiles','',"size='6px' tabindex='8'");?></td>
			</tr>
			<tr>
				<td></td>
				<td><?php echo emptySelect('newFuelLoadTruck', 'Truck', "tabindex='3'");?></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td><?php  echo createSimpleButton('submitNewFuelLoad', 'Submit','',"tabindex='9'");?></td>
			</tr>
		</table>
	</div>
