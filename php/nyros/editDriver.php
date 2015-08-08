<?php
include_once '../nyro_header.php';
$driverId = $_GET['driverId'];
$driverInfo = objectQuery(
	$conexion, 
	'*',
	'driver JOIN address ON (driver.addressId = address.addressId) 
		JOIN broker USING (brokerId)',
	'driverId = '.$driverId);
?>
<script type="text/javascript">
$(document).ready(function() {
	
	//$('#submitButton').click(function() {
	$(document).one('click', '#submitEditDriverButton', function() {
		//closeNM();
		submitEditDriver();
	});
});

function submitEditDriver() {
	disableButton()
	var data = getEditDriverParams();
	var url = '../submit/submitEditDriver.php';
	
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
				alert("Driver edited successfully");
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

function getEditDriverParams() {
	var dataArray = new Array();
	dataArray['driverId'] = '<?php echo $driverId;?>';
	
	dataArray['broker'] = getVal('editDriverBrokerId');
	dataArray['first'] = getVal('editDriverFirstName');
	dataArray['last'] = getVal('editDriverLastName');
	dataArray['ssn'] = getVal('editDriverSsn');
	dataArray['tel'] = getVal('editDriverTel');
	dataArray['mobile'] = getVal('editDriverMobile');
	dataArray['carrier'] = getVal('editDriverCarrierId');
	dataArray['email'] = getVal('editDriverMail');
	//address
	
	dataArray['term'] = getVal('editDriverTermId');
	
	dataArray['percentage'] = getVal('editDriverPercentage');
	dataArray['start'] = getVal('editDriverStartDate');
	dataArray['gender'] = getVal('editDriverGender');
	dataArray['ethnic'] = getVal('editDriverEthnicId');
	dataArray['class'] = getVal('editDriverClass');
	
	//address
	dataArray['line1'] = getVal('editDriverAddressLine1');
	dataArray['line2'] = getVal('editDriverAddressLine2');
	dataArray['city'] = getVal('editDriverAddressCity');
	dataArray['state'] = getVal('editDriverAddressState');
	dataArray['zip'] = getVal('editDriverAddressZip');
	dataArray['box'] = getVal('editDriverAddressBox');
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
			<th colspan='2'>Edit Driver</th>
		</tr>
		<?php
		
		//Driver
		$flag = true; 
		echo createFormRow('Broker', $flag ? 'class="bg"' : '', true, arrayToSelect(brokersArray($conexion), $driverInfo['brokerId'], 'editDriverBrokerId', 'Broker')); $flag = !$flag;
		echo createFormRowTextField('First Name', 'editDriverFirstName', $flag ? 'class="bg"' : '', true, "size='10px' value='".$driverInfo['driverFirstName']."'"); $flag = !$flag; 
		echo createFormRowTextField('Last Name', 'editDriverLastName', $flag ? 'class="bg"' : '', true, "size='10px' value='".$driverInfo['driverLastName']."'"); $flag = !$flag; 
		echo createFormRowTextField('SSN', 'editDriverSsn', $flag ? 'class="bg"' : '', true, "size='10px' value='".$driverInfo['driverSSN']."'"); $flag = !$flag; 
		echo createFormRowTextField('Phone', 'editDriverTel', $flag ? 'class="bg"' : '', false, "size='10px' value='".showPhoneNumber($driverInfo['driverTel'])."'"); $flag = !$flag; 
		echo createFormRowTextField('Mobile', 'editDriverMobile', $flag ? 'class="bg"' : '', false, "size='10px' value='".showPhoneNumber($driverInfo['driverMobile'])."'"); $flag = !$flag; 
		echo createFormRow('Carrier',$flag ? 'class="bg"' : '',false,createCarrierSelect($conexion, 'editDriverCarrierId')); $flag = !$flag;
		echo createFormRowTextField('E-mail', 'editDriverMail', $flag ? 'class="bg"' : '', false, "size='10px' value='".$driverInfo['driverMail']."'"); $flag = !$flag; 
		
		//parking address
		echo createFormRowTextField('Parking Address', 'editDriverAddressLine1', $flag ? 'class="bg"' : '', false, "size='10px' value='".$driverInfo['addressLine1']."'"); $flag = !$flag; 
		echo createFormRowTextField('Address Line 2', 'editDriverAddressLine2', $flag ? 'class="bg"' : '', false, "size='10px' value='".$driverInfo['addressLine2']."'"); $flag = !$flag; 
		echo createFormRowTextField('City', 'editDriverAddressCity', $flag ? 'class="bg"' : '', false, "size='10px' value='".$driverInfo['addressCity']."'"); $flag = !$flag; 
		//state
		echo createFormRow('State',$flag ? 'class="bg"' : '',false,createStateSelect($conexion, 'editDriverAddressState',$driverInfo['addressState'])); $flag = !$flag;
		echo createFormRowTextField('Zip', 'editDriverAddressZip', $flag ? 'class="bg"' : '', false, "size='10px' value='".$driverInfo['addressZip']."'"); $flag = !$flag; 
		echo createFormRowTextField('P.O.Box', 'editDriverAddressBox', $flag ? 'class="bg"' : '', false, "size='10px' value='".$driverInfo['addressPOBox']."'"); $flag = !$flag; 
		
		//
		echo createFormRow('Payment Terms',$flag ? 'class="bg"' : '',true,createTermSelect($conexion, 'editDriverTermId',$driverInfo['termId'])); $flag = !$flag;
		echo createFormRowTextField('Percentage', 'editDriverPercentage', $flag ? 'class="bg"' : '', true, "size='10px' value='".$driverInfo['driverPercentage']."'"); $flag = !$flag; 
		echo createFormRowTextField('Driver Start Date', 'editDriverStartDate', $flag ? 'class="bg"' : '', false, "size='10px' value='".to_MDY($driverInfo['driverStartDate'])."'"); $flag = !$flag; 
		echo createFormRow('Gender',$flag ? 'class="bg"' : '',true,arrayToSelect(array("0"=>"--Select Gender--","male" => "Male","female"=>"Female"),$driverInfo['driverGender'],'editDriverGender','Gender',true)); $flag = !$flag;
		echo createFormRow('Ethnicity',$flag ? 'class="bg"' : '',true,createEthnicitySelect($conexion, 'editDriverEthnicId', $driverInfo['ethnicId'])); $flag = !$flag;
		echo createFormRow('Class',$flag ? 'class="bg"' : '',true,arrayToSelect(array("--Select Class--","Class 1","Class 2","Class 3", "Class 4"),$driverInfo['driverClass'],'editDriverClass','Class',true)); $flag = !$flag;
		
				
		?>
	</table>
	<table>
		<tr>
			<td><?php echo createSimpleButton('submitEditDriverButton', 'Submit'); ?></td>
		</tr>
	</table>
</div>
