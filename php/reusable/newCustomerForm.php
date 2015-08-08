<?php
include_once '../app_header.php';
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#submitNewCustomerButton').click(function() {
		submitNewCustomer();
	});
});

function enableButton(){}
function disableButton(){}

function submitNewCustomer() {
	var data = getNewCustomerParams();
	var url = '../submit/submitNewCustomer.php';
	
	submitNewObject(url, data);
}

function getNewCustomerParams() {
	var dataArray = new Array();
	dataArray['name'] = getVal('newCustomerName');
	dataArray['term'] = getVal('newCustomerTerm');
	dataArray['tel'] = getVal('newCustomerTel');
	dataArray['fax'] = getVal('newCustomerFax');
	dataArray['web'] = getVal('newCustomerWebsite');
	
	dataArray['line1'] = getVal('newCustomerAddressLine1');
	dataArray['line2'] = getVal('newCustomerAddressLine2');
	dataArray['city'] = getVal('newCustomerAddressCity');
	dataArray['state'] = getVal('newCustomerAddressState');
	dataArray['zip'] = getVal('newCustomerAddressZip');
	dataArray['box'] = getVal('newCustomerAddressBox');
	
	return arrayToDataString(dataArray);
}
</script>
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="2">New Customer</th>
			</tr>
			<?php 
			$flag = true; 
			echo createFormRowTextField("Name", 'newCustomerName', $flag ? 'class="bg"' : '', true, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('Tel', 'newCustomerTel', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('Fax', 'newCustomerFax', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('Website', 'newCustomerWebsite', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRow('Term',$flag ? 'class="bg"' : '',true,arrayToSelect(termsArray($conexion),0, 'newCustomerTerm','Term')); $flag = !$flag;
			
			echo createFormRowTextField('Address', 'newCustomerAddressLine1', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('Address Line 2', 'newCustomerAddressLine2', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('City', 'newCustomerAddressCity', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRow('State',$flag ? 'class="bg"' : '',false,createStateSelect($conexion, 'newCustomerAddressState')); $flag = !$flag;
			echo createFormRowTextField('Zip', 'newCustomerAddressZip', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('P.O.Box', 'newCustomerAddressBox', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			
			
			?>
		</table>
		<table>
			<tr>
				<td><?php echo createSimpleButton('submitNewCustomerButton', 'Submit');?></td>
			</tr>
		</table>
	</div>
</div>
