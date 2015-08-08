<?php
include_once '../app_header.php';
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#submitNewEstimateButton').click(function() {
		submitNewEstimate();
	});
});

function enableButton(){}
function disableButton(){}

function submitNewEstimate() {
	var data = getNewEstimateParams();
	var url = '../submit/submitNewEstimate.php';
	
	submitNewObject(url, data);
}

function getNewEstimateParams() {
	var dataArray = new Array();
	dataArray['name'] = getVal('newEstimateName');
	dataArray['customer'] = getVal('newEstimateCustomer');
	
	dataArray['line1'] = getVal('newEstimateAddressLine1');
	dataArray['line2'] = getVal('newEstimateAddressLine2');
	dataArray['city'] = getVal('newEstimateAddressCity');
	dataArray['state'] = getVal('newEstimateAddressState');
	dataArray['zip'] = getVal('newEstimateAddressZip');
	dataArray['box'] = getVal('newEstimateAddressBox');
	
	return arrayToDataString(dataArray);
}
</script>
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="2">New Estimate</th>
			</tr>
			<?php 
			$flag = true; 
			echo createFormRow('Customer',$flag ? 'class="bg"' : '',true,arrayToSelect(customersArray($conexion),0, 'newEstimateCustomer','Customer')); $flag = !$flag;
			
			echo createFormRowTextField("Name", 'newEstimateName', $flag ? 'class="bg"' : '', true, 'size=25px'); $flag = !$flag; 
			
			echo createFormRowTextField('Address', 'newEstimateAddressLine1', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('Address Line 2', 'newEstimateAddressLine2', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('City', 'newEstimateAddressCity', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRow('State',$flag ? 'class="bg"' : '',false,createStateSelect($conexion, 'newEstimateAddressState')); $flag = !$flag;
			echo createFormRowTextField('Zip', 'newEstimateAddressZip', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			echo createFormRowTextField('P.O.Box', 'newEstimateAddressBox', $flag ? 'class="bg"' : '', false, 'size=25px'); $flag = !$flag; 
			
			
			?>
		</table>
		<table>
			<tr>
				<td><?php echo createSimpleButton('submitNewEstimateButton', 'Submit');?></td>
			</tr>
		</table>
	</div>
</div>
