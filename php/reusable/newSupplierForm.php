<?php
include_once '../app_header.php';
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#submitNewSupplierButton').click(function() {
		submitNewSupplier();
	});
});

function submitNewSupplier() {
	var data = getNewSupplierParams();
	var url = '../submit/submitNewSupplier.php';
	
	submitNewObject(url, data);
}

function disableButton() {
	$('#submitNewSupplierButton').attr('disabled','disabled');
}

function enableButton() {
	$('#submitNewSupplierButton').removeAttr('disabled');
}

function getNewSupplierParams() {
	var dataArray = new Array();
	dataArray['vendor'] = getVal('newSupplierVendor');
	dataArray['name'] = getVal('newSupplierName');
	dataArray['dumptime'] = getVal('newSupplierDumptime');
	dataArray['tel'] = getVal('newSupplierTel');
	dataArray['fax'] = getVal('newSupplierFax');
	dataArray['info'] = getVal('newSupplierInfo');
	
	dataArray['line1'] = getVal('newSupplierAddressLine1');
	dataArray['line2'] = getVal('newSupplierAddressLine2');
	dataArray['city'] = getVal('newSupplierAddressCity');
	dataArray['state'] = getVal('newSupplierAddressState');
	dataArray['zip'] = getVal('newSupplierAddressZip');
	dataArray['box'] = getVal('newSupplierAddressBox');
	
	return arrayToDataString(dataArray);
}

function doAfterSubmit(data) {
	console.log(data);
	try{
		var obj = jQuery.parseJSON(data);
		switch(obj.code){
			case 0:
				alert("Supplier created successfully");
				location.reload();//Go to materials
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
</script>
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="2">New Supplier</th>
			</tr>
			<?php 
			$flag = true; 
			echo createFormRow('Vendor',$flag ? 'class="bg"' : '',true,arrayToSelect(vendorsArray($conexion),0, 'newSupplierVendor','Vendor')); $flag = !$flag;
			echo createFormRowTextField("Name", 'newSupplierName', $flag ? 'class="bg"' : '', true, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('Tel', 'newSupplierTel', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('Fax', 'newSupplierFax', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			
			echo createFormRowTextField('Address', 'newSupplierAddressLine1', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('Address Line 2', 'newSupplierAddressLine2', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('City', 'newSupplierAddressCity', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRow('State',$flag ? 'class="bg"' : '',false,createStateSelect($conexion, 'newSupplierAddressState')); $flag = !$flag;
			echo createFormRowTextField('Zip', 'newSupplierAddressZip', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('P.O.Box', 'newSupplierAddressBox', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			
			echo createFormRowTextField('Dumptime', 'newSupplierDumptime', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRow('Additional Information', $flag ? 'class="bg"' : '', false, createInputTextArea('newSupplierInfo','','rows="3" cols="32"')); $flag = !$flag;
			
			?>
		</table>
		<table>
			<tr>
				<td><?php echo createSimpleButton('submitNewSupplierButton', 'Submit');?></td>
			</tr>
		</table>
	</div>
</div>
