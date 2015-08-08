<?php
include_once '../nyro_header.php';
$brokerId = $_GET['brokerId'];
$brokerInfo = objectQuery($conexion, '*',$brokerExtendedTables,'brokerId = '.$brokerId);
?>
<script type="text/javascript">
$(document).ready(function() {
	
	//$('#submitButton').click(function() {
	$(document).on('click', '#submitButton', function() {
		//closeNM();
		submitEditBroker();
	});
});

function submitEditBroker() {
	disableButton()
	var data = getEditParams();
	var url = '../submit/submitEditBroker.php';
	
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
				alert("Broker edited successfully");
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

function getEditParams() {
	var dataArray = new Array();
	dataArray['brokerId'] = <?php echo $brokerId;?>;
	dataArray['pid'] = getVal('editBrokerPid');
	dataArray['name'] = getVal('editBrokerName');
	dataArray['contact'] = getVal('editBrokerContact');
	dataArray['tel'] = getVal('editBrokerTel');
	dataArray['fax'] = getVal('editBrokerFax');
	dataArray['radio'] = getVal('editBrokerRadio');
	dataArray['mobile'] = getVal('editBrokerMobile');
	dataArray['carrier'] = getVal('editBrokerCarrierId');
	dataArray['email'] = getVal('editBrokerMail');
	dataArray['line1'] = getVal('editBrokerAddressLine1');
	dataArray['line2'] = getVal('editBrokerAddressLine2');
	dataArray['city'] = getVal('editBrokerAddressCity');
	dataArray['state'] = getVal('editBrokerAddressState');
	dataArray['zip'] = getVal('editBrokerAddressZip');
	dataArray['box'] = getVal('editBrokerAddressBox');
	dataArray['term'] = getVal('editBrokerTermId');
	dataArray['tax'] = getVal('editBrokerTax');
	dataArray['icc'] = getVal('editBrokerIccCert');
	dataArray['inswc'] = getVal('editBrokerInsWc');
	dataArray['wcexp'] = getVal('editBrokerWcExpire');
	dataArray['inslb'] = getVal('editBrokerInsLiability');
	dataArray['lbexp'] = getVal('editBrokerLbExpire');
	dataArray['genlb'] = getVal('editBrokerGeneralLiability');
	dataArray['glexp'] = getVal('editBrokerGlExp');
	dataArray['percentage'] = getVal('editBrokerPercentage');
	dataArray['start'] = getVal('editBrokerStartDate');
	dataArray['gender'] = getVal('editBrokerGender');
	dataArray['ethnic'] = getVal('editBrokerEthnicId');
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
		$flag = true; 
		echo createFormRowTextField('Pid (Short Name)', 'editBrokerPid', $flag ? 'class="bg"' : '', true, "size='14px' value='".$brokerInfo['brokerPid']."'"); $flag = !$flag; 
		echo createFormRowTextField('Broker Name', 'editBrokerName', $flag ? 'class="bg"' : '', true, "size='14px' value='".$brokerInfo['brokerName']."'"); $flag = !$flag; 
		echo createFormRowTextField('Contact Name', 'editBrokerContact', $flag ? 'class="bg"' : '', false, "size='14px' value='".$brokerInfo['brokerContact']."'"); $flag = !$flag; 
		echo createFormRowTextField('Broker Phone', 'editBrokerTel', $flag ? 'class="bg"' : '', false, "size='14px' value='".showPhoneNumber($brokerInfo['brokerTel'])."'"); $flag = !$flag; 
		echo createFormRowTextField('Broker Fax', 'editBrokerFax', $flag ? 'class="bg"' : '', false, "size='14px' value='".showPhoneNumber($brokerInfo['brokerFax'])."'"); $flag = !$flag; 
		echo createFormRowTextField('Broker Radio', 'editBrokerRadio', $flag ? 'class="bg"' : '', false, "size='14px' value='".$brokerInfo['brokerRadio']."'"); $flag = !$flag; 
		echo createFormRowTextField('Broker Mobile', 'editBrokerMobile', $flag ? 'class="bg"' : '', false, "size='14px' value='".showPhoneNumber($brokerInfo['brokerMobile'])."'"); $flag = !$flag; 
		echo createFormRow('Carrier',$flag ? 'class="bg"' : '',false,createCarrierSelect($conexion, 'editBrokerCarrierId', $brokerInfo['carrierId'])); $flag = !$flag;
		echo createFormRowTextField('Broker Email', 'editBrokerMail', $flag ? 'class="bg"' : '', false, "size='14px' value='".$brokerInfo['brokerEmail']."'"); $flag = !$flag; 
		echo createFormRowTextField('Address', 'editBrokerAddressLine1', $flag ? 'class="bg"' : '', false, "size='14px' value='".$brokerInfo['addressLine1']."'"); $flag = !$flag; 
		echo createFormRowTextField('Address Line 2', 'editBrokerAddressLine2', $flag ? 'class="bg"' : '', false, "size='14px' value='".$brokerInfo['addressLine2']."'"); $flag = !$flag; 
		echo createFormRowTextField('City', 'editBrokerAddressCity', $flag ? 'class="bg"' : '', false, "size='14px' value='".$brokerInfo['addressCity']."'"); $flag = !$flag; 
		echo createFormRow('State',$flag ? 'class="bg"' : '',false,createStateSelect($conexion, 'editBrokerAddressState', $brokerInfo['addressState'])); $flag = !$flag;
		echo createFormRowTextField('Zip', 'editBrokerAddressZip', $flag ? 'class="bg"' : '', false, "size='14px' value='".$brokerInfo['addressZip']."'"); $flag = !$flag; 
		echo createFormRowTextField('P.O.Box', 'editBrokerAddressBox', $flag ? 'class="bg"' : '', false, "size='14px' value='".$brokerInfo['addressPOBox']."'"); $flag = !$flag; 
		echo createFormRow('Payment Terms',$flag ? 'class="bg"' : '',true,createTermSelect($conexion, 'editBrokerTermId', $brokerInfo['termId'])); $flag = !$flag;
		echo createFormRowTextField('Tax', 'editBrokerTax', $flag ? 'class="bg"' : '', false, "size='14px' value='".$brokerInfo['brokerTax']."'"); $flag = !$flag; 
		echo createFormRowTextField('ICC', 'editBrokerIccCert', $flag ? 'class="bg"' : '', false, "size='14px' value='".$brokerInfo['brokerIccCert']."'"); $flag = !$flag; 
		echo createFormRowTextField('Insurance WC', 'editBrokerInsWc', $flag ? 'class="bg"' : '', true, "size='14px' value='".$brokerInfo['brokerInsuranceWc']."'"); $flag = !$flag; 
		echo createFormRowTextField('WC Expiration Date', 'editBrokerWcExpire', $flag ? 'class="bg"' : '', true, "size='14px' value='".to_MDY($brokerInfo['brokerWcExpire'])."'"); $flag = !$flag; 
		echo createFormRowTextField('Automovile Liability', 'editBrokerInsLiability', $flag ? 'class="bg"' : '', true, "size='14px' value='".$brokerInfo['brokerInsuranceLiability']."'"); $flag = !$flag; 
		echo createFormRowTextField('Expiration Date', 'editBrokerLbExpire', $flag ? 'class="bg"' : '', true, "size='14px' value='".to_MDY($brokerInfo['brokerLbExpire'])."'"); $flag = !$flag; 
		echo createFormRowTextField('General Liability', 'editBrokerGeneralLiability', $flag ? 'class="bg"' : '', false, "size='14px' value='".$brokerInfo['brokerGeneralLiability']."'"); $flag = !$flag; 
		echo createFormRowTextField('Expiration Date', 'editBrokerGlExp', $flag ? 'class="bg"' : '', false, "size='14px' value='".to_MDY($brokerInfo['brokerGlExp'])."'"); $flag = !$flag; 
		echo createFormRowTextField('Percentage', 'editBrokerPercentage', $flag ? 'class="bg"' : '', false, "size='14px' value='".$brokerInfo['brokerPercentage']."'"); $flag = !$flag; 
		echo createFormRowTextField('Broker Start Date', 'editBrokerStartDate', $flag ? 'class="bg"' : '', false, "size='14px' value='".to_MDY($brokerInfo['brokerStartDate'])."'"); $flag = !$flag; 
		echo createFormRow('Gender',$flag ? 'class="bg"' : '',true,arrayToSelect(array("0"=>"Select Gender","male" => "Male","female"=>"Female"),$brokerInfo['brokerGender'],'editBrokerGender','Gender',true)); $flag = !$flag;
		echo createFormRow('Ethnicity',$flag ? 'class="bg"' : '',true,createEthnicitySelect($conexion, 'editBrokerEthnicId', $brokerInfo['ethnicId'])); $flag = !$flag;
			
		?>
	</table>
	<table>
		<tr>
			<td><?php echo createSimpleButton('submitButton', 'Submit'); ?></td>
		</tr>
	</table>
</div>
