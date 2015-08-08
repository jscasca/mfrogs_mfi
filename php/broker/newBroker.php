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
		submitBroker();
	});
});

function submitBroker() {
	var data = getParams();
	var url = '../submit/submitNewBroker.php';
	
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
				alert("Broker created successfully");
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
	dataArray['pid'] = getVal('brokerPid');
	dataArray['name'] = getVal('brokerName');
	dataArray['contact'] = getVal('brokerContact');
	dataArray['tel'] = getVal('brokerTel');
	dataArray['fax'] = getVal('brokerFax');
	dataArray['radio'] = getVal('brokerRadio');
	dataArray['mobile'] = getVal('brokerMobile');
	dataArray['carrier'] = getVal('carrierId');
	dataArray['email'] = getVal('brokerMail');
	dataArray['line1'] = getVal('addressLine1');
	dataArray['line2'] = getVal('addressLine2');
	dataArray['city'] = getVal('addressCity');
	dataArray['state'] = getVal('addressState');
	dataArray['zip'] = getVal('addressZip');
	dataArray['box'] = getVal('addressBox');
	dataArray['term'] = getVal('termId');
	dataArray['tax'] = getVal('brokerTax');
	dataArray['icc'] = getVal('brokerIccCert');
	dataArray['inswc'] = getVal('brokerInsWc');
	dataArray['wcexp'] = getVal('brokerWcExpire');
	dataArray['inslb'] = getVal('brokerInsLiability');
	dataArray['lbexp'] = getVal('brokerLbExpire');
	dataArray['genlb'] = getVal('brokerGeneralLiability');
	dataArray['glexp'] = getVal('brokerGlExp');
	dataArray['percentage'] = getVal('brokerPercentage');
	dataArray['start'] = getVal('brokerStartDate');
	dataArray['gender'] = getVal('gender');
	dataArray['ethnic'] = getVal('ethnicId');
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
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="2">New Broker</th>
			</tr>
			<?php 
			$flag = true; 
			echo createFormRowTextField('Pid (Short Name)', 'brokerPid', $flag ? 'class="bg"' : '', true, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Broker Name', 'brokerName', $flag ? 'class="bg"' : '', true, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Contact Name', 'brokerContact', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Broker Phone', 'brokerTel', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Broker Fax', 'brokerFax', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Broker Radio', 'brokerRadio', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Broker Mobile', 'brokerMobile', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRow('Carrier',$flag ? 'class="bg"' : '',false,createCarrierSelect($conexion, 'carrierId')); $flag = !$flag;
			echo createFormRowTextField('Broker Email', 'brokerMail', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Address', 'addressLine1', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Address Line 2', 'addressLine2', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('City', 'addressCity', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRow('State',$flag ? 'class="bg"' : '',false,createStateSelect($conexion, 'addressState')); $flag = !$flag;
			echo createFormRowTextField('Zip', 'addressZip', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('P.O.Box', 'addressBox', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRow('Payment Terms',$flag ? 'class="bg"' : '',true,createTermSelect($conexion, 'termId')); $flag = !$flag;
			echo createFormRowTextField('Tax', 'brokerTax', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('ICC', 'brokerIccCert', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Insurance WC', 'brokerInsWc', $flag ? 'class="bg"' : '', true, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('WC Expiration Date', 'brokerWcExpire', $flag ? 'class="bg"' : '', true, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Automovile Liability', 'brokerInsLiability', $flag ? 'class="bg"' : '', true, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Expiration Date', 'brokerLbExpire', $flag ? 'class="bg"' : '', true, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('General Liability', 'brokerGeneralLiability', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Expiration Date', 'brokerGlExp', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Percentage', 'brokerPercentage', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRowTextField('Broker Start Date', 'brokerStartDate', $flag ? 'class="bg"' : '', false, 'size=14px'); $flag = !$flag; 
			echo createFormRow('Gender',$flag ? 'class="bg"' : '',true,arrayToSelect(array("0"=>"Select Gender","male" => "Male","female"=>"Female"),"0",'gender','Gender',true)); $flag = !$flag;
			echo createFormRow('Ethnicity',$flag ? 'class="bg"' : '',true,createEthnicitySelect($conexion, 'ethnicId')); $flag = !$flag;
			
			
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
