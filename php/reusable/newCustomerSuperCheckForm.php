<?php

?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#submitNewCustomerSuperCheck').click(function() {
		submitNewCustomerSuperCheck();
	});
	
});

function submitNewCustomerSuperCheck() {
	var data = getNewCustomerSuperCheckParams();
	var url = '../submit/submitNewCustomerSuperCheck.php';
	
	submitNewObject(url, data);
}

function getNewCustomerSuperCheckParams() {
	var dataArray = new Array();
	dataArray['customer'] = getVal('newCustomerCheckCustomer');
	dataArray['number'] = getVal('newCustomerCheckNumber');
	dataArray['amount'] = getVal('newCustomerCheckAmount');
	dataArray['date'] = evalDate(getVal('newCustomerCheckDate'));
	
	
	return arrayToDataString(dataArray);
}
</script>
<div id="center-column">
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="2">New Customer Check</th>
			</tr>
			<?php 
			$flag = true; 
			echo createFormRow('Customer', $flag ? 'class="bg"' : '', true, arrayToSelect(customersArray($conexion), (isset($_GET['customerId']) ? $_GET['customerId'] : 0 ), 'newCustomerCheckCustomer', 'Customer')); $flag = !$flag;
			echo createFormRowTextField('Number', 'newCustomerCheckNumber', $flag ? 'class="bg"' : '', true, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Amount', 'newCustomerCheckAmount', $flag ? 'class="bg"' : '', true, 'size=10px'); $flag = !$flag; 
			echo createFormRowTextField('Date', 'newCustomerCheckDate', $flag ? 'class="bg"' : '', true, 'size=10px'); $flag = !$flag; 
			
			?>
		</table>
		<table>
			<tr>
				<td><?php echo createSimpleButton('submitNewCustomerSuperCheck', 'Submit');?></td>
			</tr>
		</table>
	</div>
</div>
