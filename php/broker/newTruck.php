<?
$title = "MFI";
$subtitle = "Broker";

$tab = "BROKER";

include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#submitButton').click(function() {
		submitTruck();
	});
});

function submitTruck() {
	var data = getParams();
	var url = '../submit/submitNewTruck.php';
	
	submitNewObject(url, data);
}

function disableButton() {
	$('#submitButton').attr('disabled','disabled');
}

function enableButton() {
	$('#submitButton').removeAttr('disabled');
}

function doAfterSubmit(data) {
	console.log(data);
	try{
		var obj = jQuery.parseJSON(data);
		switch(obj.code){
			case 0:
				alert("Truck created successfully");
				location.reload();
				break;
			case -1:
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

function getParams() {
	var dataArray = new Array();
	dataArray['broker'] = getVal('brokerId');
	dataArray['number'] = getVal('truckNumber');
	dataArray['driver'] = getVal('truckDriver');
	dataArray['plates'] = getVal('truckPlates');
	dataArray['addinfo'] = getVal('truckInfo');
	dataArray['brand'] = getVal('truckBrand');
	dataArray['year'] = getVal('truckYear');
	dataArray['serial'] = getVal('truckSerial');
	dataArray['tire'] = getVal('truckTireSize');
	
	dataArray['line1'] = getVal('addressLine1');
	dataArray['line2'] = getVal('addressLine2');
	dataArray['city'] = getVal('addressCity');
	dataArray['state'] = getVal('addressState');
	dataArray['zip'] = getVal('addressZip');
	dataArray['box'] = getVal('addressBox');
	dataArray['features'] = getFeatures();
	var data = "";
	var glue = "";
	for(key in dataArray) {
		data += glue + key + "=" + dataArray[key];
		glue = "&";
	}
	console.log(data);
	return data;
}
</script>
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="2">New Truck</th>
			</tr>
			<?php 
			$flag = true; 
			echo createFormRow('Broker', $flag ? 'class="bg"' : '', true, arrayToSelect(brokersArray($conexion), (isset($_GET['brokerId']) ? $_GET['brokerId'] : 0 ), 'brokerId', 'Broker')); $flag = !$flag;
			echo createFormRowTextField('Truck Number', 'truckNumber', $flag ? 'class="bg"' : '', true, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Driver', 'truckDriver', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Plates', 'truckPlates', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Truck Brand', 'truckBrand', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Truck Year Model', 'truckYear', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Serial Number', 'truckSerial', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Tire Size', 'truckTireSize', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			
			//parking address
			echo createFormRowTextField('Parking Address', 'addressLine1', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Address Line 2', 'addressLine2', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('City', 'addressCity', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			//state
			echo createFormRow('State',$flag ? 'class="bg"' : '',false,createStateSelect($conexion, 'addressState')); $flag = !$flag;
			echo createFormRowTextField('Zip', 'addressZip', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('P.O.Box', 'addressBox', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRow('Additional Information', $flag ? 'class="bg"' : '', false, createInputTextArea('truckInfo','','rows="2" cols="43"')); $flag = !$flag;
			echo createFormRow('Feautres', $flag ? 'class="bg"' : '', false, featureArrayToCheckBoxes(objectArray($conexion, 'feature', 'featureName', 'featureId', 'featureName'),5));
			?>
		</table>
		<table>
			<tr>
				<td><?php echo createSimpleButton('submitButton', 'Submit');?></td>
			</tr>
		</table>
	</div>
</div>
	
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
