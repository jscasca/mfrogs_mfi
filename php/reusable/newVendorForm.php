<?php
include_once '../app_header.php';
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#submitNewVendorButton').click(function() {
		submitNewVendor();
	});
});

function submitNewVendor() {
	var data = getNewVendorParams();
	var url = '../submit/submitNewVendor.php';
	
	submitNewObject(url, data);
}

function getNewVendorParams() {
	var dataArray = new Array();
	dataArray['name'] = getVal('newVendorName');
	dataArray['comment'] = getVal('newVendorCommentaries');
	dataArray['tel'] = getVal('newVendorTel');
	dataArray['fax'] = getVal('newVendorFax');
	dataArray['info'] = getVal('newVendorInfo');
	
	dataArray['line1'] = getVal('newVendorAddressLine1');
	dataArray['line2'] = getVal('newVendorAddressLine2');
	dataArray['city'] = getVal('newVendorAddressCity');
	dataArray['state'] = getVal('newVendorAddressState');
	dataArray['zip'] = getVal('newVendorAddressZip');
	dataArray['box'] = getVal('newVendorAddressBox');
	
	return arrayToDataString(dataArray);
}


function doAfterSubmit(data) {
	console.log(data);
	try{
		var obj = jQuery.parseJSON(data);
		switch(obj.code){
			case 0:
				alert("Vendor created successfully");
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
</script>
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="2">New Vendor</th>
			</tr>
			<?php 
			$flag = true; 
			echo createFormRowTextField("Name", 'newVendorName', $flag ? 'class="bg"' : '', true, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('Commentaries', 'newVendorCommentaries', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('Tel', 'newVendorTel', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('Fax', 'newVendorFax', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			
			echo createFormRowTextField('Address', 'newVendorAddressLine1', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('Address Line 2', 'newVendorAddressLine2', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('City', 'newVendorAddressCity', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRow('State',$flag ? 'class="bg"' : '',false,createStateSelect($conexion, 'newVendorAddressState')); $flag = !$flag;
			echo createFormRowTextField('Zip', 'newVendorAddressZip', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('P.O.Box', 'newVendorAddressBox', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			
			echo createFormRow('Additional Information', $flag ? 'class="bg"' : '', false, createInputTextArea('newVendorInfo','','rows="3" cols="32"')); $flag = !$flag;
			
			?>
		</table>
		<table>
			<tr>
				<td><?php echo createSimpleButton('submitNewVendorButton', 'Submit');?></td>
			</tr>
		</table>
	</div>
</div>
