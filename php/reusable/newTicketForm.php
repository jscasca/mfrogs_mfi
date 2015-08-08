<?php

?>
<script type="text/javascript">
$(document).ready(function() {
	
	$('#newTicketCustomer').change(function() { getTicketProjectsOptions('newTicketProject',$(this).val()); });
	$('#newTicketProject').change(function() { getTicketItemsOptions('newTicketItem',$(this).val()); });
	
	$('#newTicketBroker').change(function() { 
		getTrucksOptions('newTicketTruck',$(this).val()); 
		getDriversOptions('newTicketDriver',$(this).val()); 
	});
	
	$('#newTicketAmount').blur(function() { 
		$('#newTicketBrokerAmount').val($(this).val());  
	});
	
	$('#submitNewTicket').click(function() {
		submitNewTicket();
	});
	
	$('#refreshNewTicket').click(function() {
		refreshNewTicket();
	});
	
	$('#newFuelLoadBroker').change(function() {
		getTrucksOptions('newFuelLoadTruck',$(this).val());
	});
	
});

function submitNewTicket() {
	var data = getNewTicketParams();
	var url = '../submit/submitNewTicket.php';
	
	submitNewObject(url, data);
}

function getNewTicketParams() {
	var dataArray = new Array();
	dataArray['item'] = getVal('newTicketItem');
	dataArray['truck'] = getVal('newTicketTruck');
	dataArray['driver'] = getVal('newTicketDriver');
	dataArray['date'] = evalDate(getVal('newTicketDate'));
	dataArray['mfi'] = getVal('newTicketMfi');
	dataArray['number'] = getVal('newTicketNumber');
	dataArray['amount'] = getVal('newTicketAmount');
	dataArray['brokerAmount'] = getVal('newTicketBrokerAmount');
	
	
	return arrayToDataString(dataArray);
}
</script>
	<div class="table">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th colspan='7'>New Fuel Load</th>
			</tr>
			<tr>
				<td><strong>Customer/Project/Item:</strong></td>
				<td><strong>Broker/Truck/Driver:</strong></td>
				<td><strong>Date:</strong></td>
				<td><strong>MFI:</strong></td>
				<td><strong>Dump/Material:</strong></td>
				<td><strong>Amount/Broker Amount:</strong></td>
			</tr>
			<tr class='bg'>
				<td><?php echo arrayToSelect(customersArray($conexion),'0','newTicketCustomer',"Customer", false, "tabindex='1'");?></td>
				<td><?php echo arrayToSelect(brokersArray($conexion),'0','newTicketBroker',"Broker", false, "tabindex='4'");?></td>
				<td><?php echo createInputText('newTicketDate','',"size='6px' tabindex='7'");?></td>
				<td><?php echo createInputText('newTicketMfi','',"size='6px' tabindex='8'");?></td>
				<td><?php echo createInputText('newTicketNumber','',"size='6px' tabindex='9'");?></td>
				<td><?php echo createInputText('newTicketAmount','',"size='6px' tabindex='10'");?></td>
			</tr>
			<tr>
				<td><?php echo emptySelect('newTicketProject', 'Project', "tabindex='2'");?></td>
				<td><?php echo emptySelect('newTicketTruck', 'Truck', "tabindex='5'");?></td>
				<td></td>
				<td></td>
				<td></td>
				<td><?php echo createInputText('newTicketBrokerAmount','',"size='6px' tabindex='11'");?></td>
			</tr>
			<tr class='bg'>
				<td><?php echo emptySelect('newTicketItem', 'Item', "tabindex='3'");?></td>
				<td><?php echo emptySelect('newTicketDriver', 'Driver', "tabindex='6'");?></td>
				<td></td>
				<td></td>
				<td><?php echo createSimpleButton('refreshNewTicket','Reset','',"");?></td>
				<td><?php echo createSimpleButton('submitNewTicket','Submit','',"tabindex='12'");?></td>
			</tr>
		</table>
	</div>
