<?php
include_once '../nyro_header.php';
$truckId = $_GET['truckId'];
$truckInfo = objectQuery(
	$conexion, 
	'*',
	'truck JOIN address ON (truck.addressId = address.addressId) 
		JOIN broker USING (brokerId)',
	'truckId = '.$truckId);
?>
<script type="text/javascript">
$(document).ready(function() {
	
	//$('#submitButton').click(function() {
	$(document).one('click', '#submitEditTruckButton', function() {
		console.log("algo");
		//closeNM();
		submitEditTruck();
	});
});

function submitEditTruck() {
	disableButton()
	var data = getEditTruckParams();
	var url = '../submit/submitEditTruck.php';
	
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
				alert("Truck edited successfully");
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

function getEditTruckParams() {
	var dataArray = new Array();
	dataArray['truckId'] = <?php echo $truckId;?>;
	dataArray['broker'] = getVal('editTruckBrokerId');
	dataArray['number'] = getVal('editTruckNumber');
	dataArray['driver'] = getVal('editTruckDriver');
	dataArray['plates'] = getVal('editTruckPlates');
	dataArray['addinfo'] = getVal('editTruckInfo');
	dataArray['brand'] = getVal('editTruckBrand');
	dataArray['year'] = getVal('editTruckYear');
	dataArray['serial'] = getVal('editTruckSerial');
	dataArray['tire'] = getVal('editTruckTireSize');
	//address
	dataArray['line1'] = getVal('editTruckAddressLine1');
	dataArray['line2'] = getVal('editTruckAddressLine2');
	dataArray['city'] = getVal('editTruckAddressCity');
	dataArray['state'] = getVal('editTruckAddressState');
	dataArray['zip'] = getVal('editTruckAddressZip');
	dataArray['box'] = getVal('editTruckAddressBox');
	dataArray['features'] = getFeatures();
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
			<th colspan='2'>Edit Broker</th>
		</tr>
		<?php
		
		//Truck
		$flag = true; 
		echo createFormRow('Broker', $flag ? 'class="bg"' : '', true, arrayToSelect(brokersArray($conexion), $truckInfo['brokerId'], 'editTruckBrokerId', 'Broker')); $flag = !$flag;
		echo createFormRowTextField('Truck Number', 'editTruckNumber', $flag ? 'class="bg"' : '', true, "size='10px' value='".$truckInfo['truckNumber']."'"); $flag = !$flag; 
		echo createFormRowTextField('Driver', 'editTruckDriver', $flag ? 'class="bg"' : '', false, "size='10px' value='".$truckInfo['truckDriver']."'"); $flag = !$flag; 
		echo createFormRowTextField('Plates', 'editTruckPlates', $flag ? 'class="bg"' : '', false, "size='10px' value='".$truckInfo['truckPlates']."'"); $flag = !$flag; 
		echo createFormRowTextField('Truck Brand', 'editTruckBrand', $flag ? 'class="bg"' : '', false, "size='10px' value='".$truckInfo['truckBrand']."'"); $flag = !$flag; 
		echo createFormRowTextField('Truck Year Model', 'editTruckYear', $flag ? 'class="bg"' : '', false, "size='10px' value='".$truckInfo['truckYear']."'"); $flag = !$flag; 
		echo createFormRowTextField('Serial Number', 'editTruckSerial', $flag ? 'class="bg"' : '', false, "size='10px' value='".$truckInfo['truckSerial']."'"); $flag = !$flag; 
		echo createFormRowTextField('Tire Size', 'editTruckTireSize', $flag ? 'class="bg"' : '', false, "size='10px' value='".$truckInfo['truckTireSize']."'"); $flag = !$flag; 
		
		//parking address
		echo createFormRowTextField('Parking Address', 'editTruckAddressLine1', $flag ? 'class="bg"' : '', false, "size='10px' value='".$truckInfo['addressLine1']."'"); $flag = !$flag; 
		echo createFormRowTextField('Address Line 2', 'editTruckAddressLine2', $flag ? 'class="bg"' : '', false, "size='10px' value='".$truckInfo['addressLine2']."'"); $flag = !$flag; 
		echo createFormRowTextField('City', 'editTruckAddressCity', $flag ? 'class="bg"' : '', false, "size='10px' value='".$truckInfo['addressCity']."'"); $flag = !$flag; 
		//state
		echo createFormRow('State',$flag ? 'class="bg"' : '',false,createStateSelect($conexion, 'editTruckAddressState',$truckInfo['addressState'])); $flag = !$flag;
		echo createFormRowTextField('Zip', 'editTruckAddressZip', $flag ? 'class="bg"' : '', false, "size='10px' value='".$truckInfo['addressZip']."'"); $flag = !$flag; 
		echo createFormRowTextField('P.O.Box', 'editTruckAddressBox', $flag ? 'class="bg"' : '', false, "size='10px' value='".$truckInfo['addressPOBox']."'"); $flag = !$flag; 
		echo createFormRow('Additional Information', $flag ? 'class="bg"' : '', false, createInputTextArea('editTruckInfo','','rows="2" cols="43"',$truckInfo['truckInfo'])); $flag = !$flag;
		echo createFormRow('Features', $flag ? 'class="bg"' : '', false, featureArrayToCheckBoxes(objectArray($conexion, 'feature', 'featureName', 'featureId', 'featureName'),5,getTruckCheckedFeatures($conexion, $truckId)));
				
		?>
	</table>
	<table>
		<tr>
			<td><?php echo createSimpleButton('submitEditTruckButton', 'Submit'); ?></td>
		</tr>
	</table>
</div>
