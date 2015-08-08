<?php
include_once '../nyro_header.php';
$fuelLoadId = $_GET['fuelLoadId'];
$fuelLoadInfo = objectQuery(
	$conexion, 
	'*',
	'fuel_load',
	'fuelLoadId = '.$fuelLoadId);
?>
<script type="text/javascript">
$(document).ready(function() {
	
	//$('#submitButton').click(function() {
	$(document).one('click', '#submitEditFuelLoadButton', function() {
		console.log("algo");
		//closeNM();
		submitEditFuelLoad();
	});
});

function submitEditFuelLoad() {
	disableButton()
	var data = getEditFuelLoadParams();
	var url = '../submit/submitEditFuelLoad.php';
	
	submitNewObject(url, data);
}

function disableButton() {
	$('#submitButton').attr('disabled','disabled');
}

function enableButton() {
	$('#submitButton').removeAttr('disabled');
}

function closeNM() {
	//console.log("closing");
	$.nmTop().close();
}

function doAfterSubmit(data) {
	console.log(data);
	try{
		var obj = jQuery.parseJSON(data);
		switch(obj.code){
			case 0:
				alert("FuelLoad edited successfully");
				closeNM();
				break;
			case -1:
				alert(obj.msg);
				$('#' + obj.focus).focus();
				break;
			case -4:
				alert(obj.msg);
				$('#' + obj.focus).focus();
				break;
			default:
				alert(obj.msg);
				break;
		}
	} catch(e) {
		alert("Internal Error: Please contact the administrator.");
	}
	enableButton();
}

function getFeatures() {
	var features = "";
	var glue = "";
	var checkedB = $('.featureCheckbox:checked');
	$.each(checkedB, function(index, obj) {
		features = features + glue + obj.value;
		glue = "~";
	});
	return escape(features);
}

function getEditFuelLoadParams() {
	var dataArray = new Array();
	dataArray['fuelLoadId'] = <?php echo $fuelLoadId;?>;
	
	dataArray['date'] = evalDate(getVal('editFuelLoadDate'));
	dataArray['broker'] = getVal('editFuelLoadBroker');
	dataArray['truck'] = getVal('editFuelLoadTruck');
	dataArray['driver'] = getVal('editFuelLoadDriver');
	dataArray['start'] = getVal('editFuelLoadStart');
	dataArray['finish'] = getVal('editFuelLoadFinish');
	dataArray['registered'] = getVal('editFuelLoadRegistered');
	dataArray['miles'] = getVal('editFuelLoadMiles');
	var data = "";
	var glue = "";
	for(key in dataArray) {
		data += glue + key + "=" + dataArray[key];
		glue = "&";
	}
	//console.log(data);
	return data;
}
</script>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th colspan='7'>Edit Fuel Load</th>
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
				<td><?php echo createInputText('editFuelLoadDate','',"size='6px' tabindex='1' value='".to_MDY($fuelLoadInfo['fuelLoadDate'])."'");?></td>
				<td><?php echo arrayToSelect(brokersArray($conexion),$fuelLoadInfo['brokerId'],'editFuelLoadBroker',"Broker", false, "tabindex='2'");?></td>
				<td><?php echo createInputText('editFuelLoadDriver','',"size='6px' tabindex='4' value='".$fuelLoadInfo['fuelLoadCommet']."'");?></td>
				<td><?php echo createInputText('editFuelLoadStart','',"size='6px' tabindex='5' value='".$fuelLoadInfo['fuelLoadStart']."'");?></td>
				<td><?php echo createInputText('editFuelLoadFinish','',"size='6px' tabindex='6' value='".$fuelLoadInfo['fuelLoadFinish']."'");?></td>
				<td><?php echo createInputText('editFuelLoadRegistered','',"size='6px' tabindex='7' value='".$fuelLoadInfo['fuelLoadRegistered']."'");?></td>
				<td><?php echo createInputText('editFuelLoadMiles','',"size='6px' tabindex='8' value='".$fuelLoadInfo['fuelLoadMileage']."'");?></td>
			</tr>
			<tr>
				<td></td>
				<td><?php echo arrayToSelect(trucksArray($conexion,$fuelLoadInfo['brokerId']),$fuelLoadInfo['truckId'],'editFuelLoadTruck',"Truck", false, "tabindex='3'");?></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td><?php  echo createSimpleButton('submitEditFuelLoadButton', 'Submit','',"tabindex='9'");?></td>
			</tr>
		</table>
</div>
