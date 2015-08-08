<?php
include_once '../nyro_header.php';
$vendorId = $_GET['vendorId'];
$vendorInfo = objectQuery($conexion, '*',$vendorExtendedTables,'vendorId = '.$vendorId);
?>
<script type="text/javascript">
$(document).ready(function() {
	
	//$('#submitButton').click(function() {
	$(document).off('click', '#submitEditVendor');
	$(document).on('click', '#submitEditVendor', function() {
		//closeNM();
		submitEditVendor();
	});
});

function submitEditVendor() {
	disableButton()
	var data = getEditParams();
	var url = '../submit/submitEditVendor.php';
	
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
				alert("Vendor edited successfully");
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
	dataArray['vendorId'] = <?php echo $vendorId;?>;
	
	dataArray['name'] = getVal('editVendorName');
	
	dataArray['tel'] = getVal('editVendorTel');
	dataArray['fax'] = getVal('editVendorFax');
	
	dataArray['line1'] = getVal('editVendorAddressLine1');
	dataArray['line2'] = getVal('editVendorAddressLine2');
	dataArray['city'] = getVal('editVendorAddressCity');
	dataArray['state'] = getVal('editVendorAddressState');
	dataArray['zip'] = getVal('editVendorAddressZip');
	dataArray['box'] = getVal('editVendorAddressBox');
	
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
			<th colspan='2'>Edit Vendor</th>
		</tr>
		<?php
		$flag = true; 
		echo createFormRowTextField('Vendor Name', 'editVendorName', $flag ? 'class="bg"' : '', true, "size='14px' value='".$vendorInfo['vendorName']."'"); $flag = !$flag; 
		echo createFormRowTextField('Vendor Phone', 'editVendorTel', $flag ? 'class="bg"' : '', false, "size='14px' value='".showPhoneNumber($vendorInfo['vendorTel'])."'"); $flag = !$flag; 
		echo createFormRowTextField('Vendor Fax', 'editVendorFax', $flag ? 'class="bg"' : '', false, "size='14px' value='".showPhoneNumber($vendorInfo['vendorFax'])."'"); $flag = !$flag; 
		
		echo createFormRowTextField('Address', 'editVendorAddressLine1', $flag ? 'class="bg"' : '', false, "size='14px' value='".$vendorInfo['addressLine1']."'"); $flag = !$flag; 
		echo createFormRowTextField('Address Line 2', 'editVendorAddressLine2', $flag ? 'class="bg"' : '', false, "size='14px' value='".$vendorInfo['addressLine2']."'"); $flag = !$flag; 
		echo createFormRowTextField('City', 'editVendorAddressCity', $flag ? 'class="bg"' : '', false, "size='14px' value='".$vendorInfo['addressCity']."'"); $flag = !$flag; 
		echo createFormRow('State',$flag ? 'class="bg"' : '',false,createStateSelect($conexion, 'editVendorAddressState', $vendorInfo['addressState'])); $flag = !$flag;
		echo createFormRowTextField('Zip', 'editVendorAddressZip', $flag ? 'class="bg"' : '', false, "size='14px' value='".$vendorInfo['addressZip']."'"); $flag = !$flag; 
		echo createFormRowTextField('P.O.Box', 'editVendorAddressBox', $flag ? 'class="bg"' : '', false, "size='14px' value='".$vendorInfo['addressPOBox']."'"); $flag = !$flag; 
			
		?>
	</table>
	<table>
		<tr>
			<td><?php echo createSimpleButton('submitEditVendor', 'Submit'); ?></td>
		</tr>
	</table>
</div>
