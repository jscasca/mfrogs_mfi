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
		submitDriver();
	});
});

function submitDriver() {
	var data = getParams();
	var url = '../submit/submitNewDriver.php';
	
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
				alert("Driver created successfully");
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

function getParams() {
	var dataArray = new Array();
	dataArray['broker'] = getVal('brokerId');
	dataArray['first'] = getVal('firstName');
	dataArray['last'] = getVal('lastName');
	dataArray['ssn'] = getVal('ssn');
	dataArray['tel'] = getVal('tel');
	dataArray['mobile'] = getVal('mobile');
	dataArray['carrier'] = getVal('carrierId');
	dataArray['email'] = getVal('mail');
	//address
	dataArray['line1'] = getVal('addressLine1');
	dataArray['line2'] = getVal('addressLine2');
	dataArray['city'] = getVal('addressCity');
	dataArray['state'] = getVal('addressState');
	dataArray['zip'] = getVal('addressZip');
	dataArray['box'] = getVal('addressBox');
	
	dataArray['term'] = getVal('termId');
	
	dataArray['percentage'] = getVal('percentage');
	dataArray['start'] = getVal('startDate');
	dataArray['gender'] = getVal('gender');
	dataArray['ethnic'] = getVal('ethnicId');
	dataArray['class'] = getVal('driverClass');
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
				<th class="full" colspan="2">New Driver</th>
			</tr>
			<?php 
			$flag = true; 
			echo createFormRow('Broker', $flag ? 'class="bg"' : '', true, arrayToSelect(brokersArray($conexion), (isset($_GET['brokerId']) ? $_GET['brokerId'] : 0 ), 'brokerId', 'Broker')); $flag = !$flag;
			echo createFormRowTextField('First Name', 'firstName', $flag ? 'class="bg"' : '', true, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Last Name', 'lastName', $flag ? 'class="bg"' : '', true, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('SSN', 'ssn', $flag ? 'class="bg"' : '', true, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Phone', 'tel', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Mobile', 'mobile', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRow('Carrier',$flag ? 'class="bg"' : '',false,createCarrierSelect($conexion, 'carrierId')); $flag = !$flag;
			echo createFormRowTextField('E-mail', 'mail', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			
			//parking address
			echo createFormRowTextField('Parking Address', 'addressLine1', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Address Line 2', 'addressLine2', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('City', 'addressCity', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRow('State',$flag ? 'class="bg"' : '',false,createStateSelect($conexion, 'addressState')); $flag = !$flag;
			echo createFormRowTextField('Zip', 'addressZip', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('P.O.Box', 'addressBox', $flag ? 'class="bg"' : '', false, 'size=10px'); $flag = !$flag; 
			
			//
			echo createFormRow('Payment Terms',$flag ? 'class="bg"' : '',true,createTermSelect($conexion, 'termId')); $flag = !$flag;
			echo createFormRowTextField('Percentage', 'percentage', $flag ? 'class="bg"' : '', true, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Driver Start Date', 'startDate', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRow('Gender',$flag ? 'class="bg"' : '',true,arrayToSelect(array("0"=>"--Select Gender--","male" => "Male","female"=>"Female"),"0",'gender','Gender',true)); $flag = !$flag;
			echo createFormRow('Ethnicity',$flag ? 'class="bg"' : '',true,createEthnicitySelect($conexion, 'ethnicId')); $flag = !$flag;
			echo createFormRow('Class',$flag ? 'class="bg"' : '',true,arrayToSelect(array("--Select Class--","Class 1","Class 2","Class 3", "Class 4"),"0",'driverClass','Class',true)); $flag = !$flag;
			
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
