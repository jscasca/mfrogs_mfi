<?php
include_once '../nyro_header.php';
$supplierId = $_GET['supplierId'];
$supplierInfo = objectQuery(
	$conexion, 
	'*',
	'supplier JOIN address ON (supplier.addressId = address.addressId) 
		JOIN vendor USING (vendorId)',
	'supplierId = '.$supplierId);
?>
<script type="text/javascript">
$(document).ready(function() {
	
	//$('#submitButton').click(function() {
	$(document).off('click', '#submitEditSupplierButton')
	$(document).on('click', '#submitEditSupplierButton', function() {
		//closeNM();
		submitEditSupplier();
	});
});

function submitEditSupplier() {
	disableButton()
	var data = getEditSupplierParams();
	var url = '../submit/submitEditSupplier.php';
	
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
				alert("Supplier edited successfully");
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

function getEditSupplierParams() {
	var dataArray = new Array();
	dataArray['supplierId'] = '<?php echo $supplierId;?>';
	dataArray['vendor'] = getVal('editSupplierVendor');
	dataArray['name'] = getVal('editSupplierName');
	dataArray['dumptime'] = getVal('editSupplierDumptime');
	dataArray['tel'] = getVal('editSupplierTel');
	dataArray['fax'] = getVal('editSupplierFax');
	dataArray['info'] = getVal('editSupplierInfo');
	
	dataArray['line1'] = getVal('editSupplierAddressLine1');
	dataArray['line2'] = getVal('editSupplierAddressLine2');
	dataArray['city'] = getVal('editSupplierAddressCity');
	dataArray['state'] = getVal('editSupplierAddressState');
	dataArray['zip'] = getVal('editSupplierAddressZip');
	dataArray['box'] = getVal('editSupplierAddressBox');
	
	return arrayToDataString(dataArray);
}
</script>
<div id='formDiv' class='table'>
	<img src="/mfi/img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="/mfi/img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan='2'>Edit Supplier</th>
		</tr>
		<?php
		
		//Supplier
		$flag = true; 
		echo createFormRow('Vendor', $flag ? 'class="bg"' : '', true, arrayToSelect(vendorsArray($conexion), $supplierInfo['vendorId'], 'editSupplierVendor', 'Vendor')); $flag = !$flag;
		
		echo createFormRowTextField("Name", 'editSupplierName', $flag ? 'class="bg"' : '', true, "size=25px value='".$supplierInfo['supplierName']."'"); $flag = !$flag; 
		echo createFormRowTextField('Tel', 'editSupplierTel', $flag ? 'class="bg"' : '', false, "size=25px value='".showPhoneNumber($supplierInfo['supplierTel'])."'"); $flag = !$flag; 
		echo createFormRowTextField('Fax', 'editSupplierFax', $flag ? 'class="bg"' : '', false, "size=25px value='".showPhoneNumber($supplierInfo['supplierFax'])."'"); $flag = !$flag; 
			
		//parking address
		echo createFormRowTextField('Parking Address', 'editSupplierAddressLine1', $flag ? 'class="bg"' : '', false, "size='25px' value='".$supplierInfo['addressLine1']."'"); $flag = !$flag; 
		echo createFormRowTextField('Address Line 2', 'editSupplierAddressLine2', $flag ? 'class="bg"' : '', false, "size='25px' value='".$supplierInfo['addressLine2']."'"); $flag = !$flag; 
		echo createFormRowTextField('City', 'editSupplierAddressCity', $flag ? 'class="bg"' : '', false, "size='25px' value='".$supplierInfo['addressCity']."'"); $flag = !$flag; 
		//state
		echo createFormRow('State',$flag ? 'class="bg"' : '',false,createStateSelect($conexion, 'editSupplierAddressState',$supplierInfo['addressState'])); $flag = !$flag;
		echo createFormRowTextField('Zip', 'editSupplierAddressZip', $flag ? 'class="bg"' : '', false, "size='25px' value='".$supplierInfo['addressZip']."'"); $flag = !$flag; 
		echo createFormRowTextField('P.O.Box', 'editSupplierAddressBox', $flag ? 'class="bg"' : '', false, "size='25px' value='".$supplierInfo['addressPOBox']."'"); $flag = !$flag; 
		
		echo createFormRowTextField('Dumptime', 'editSupplierDumptime', $flag ? 'class="bg"' : '', false, "size=25px value='".showPhoneNumber($supplierInfo['supplierDumptime'])."'"); $flag = !$flag; 
		echo createFormRow('Additional Information', $flag ? 'class="bg"' : '', false, createInputTextArea('editSupplierInfo','','rows="3" cols="32"',$supplierInfo['supplierInformation'])); $flag = !$flag;
		?>
	</table>
	<table>
		<tr>
			<td><?php echo createSimpleButton('submitEditSupplierButton', 'Submit'); ?></td>
		</tr>
	</table>
</div>
