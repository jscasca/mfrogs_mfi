<?
$title = "MFI";
$subtitle = "Ticket";

$tab = "INVOICING";

include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>
<script type="text/javascript">
$(document).on('dblclick', '.doubleClickable', function() {
		var url = '/mfi/php/nyros/viewTicket.php?ticketId=' + $(this).attr('ticketId');
		$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
});

function refreshNewTicket() {
	location.reload();
}

function doAfterClose() {
	//funciton to be overwritted
	location.reload();
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
				alert("Item created successfully");
				//location.reload();
				$('#newTicketMfi').val(obj.newMfi);
				$(obj.newLine).insertAfter('#olderTicketsHeader');
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

function getFeatures() {
	var features = "";
	var glue = "";
	var checkedB = $('.featureCheckbox:checked');
	$.each(checkedB, function(index, obj) {
		features = features + glue + obj.value;
		glue = "~";
	});
	return escape(features);
}
</script>
<div id="center-column">
<?php include '../reusable/newTicketForm.php';?>
	<div class="table" id="oldTicketLoads">
		<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
				<th class="full" colspan="8">Older Elements</th>
			</tr>
			<tr id='olderTicketsHeader'>
				<th>Date</th>
				<th>Job</th>
				<th>Truck</th>
				<th>Material</th>
				<th>From</th>
				<th>To</th>
				<th>MFI</th>
				<th>Dump</th>
			</tr>
			<?php 
			$tickets = mysql_query("SELECT * FROM ticket JOIN truck USING (truckId) JOIN broker USING (brokerId) JOIN item USING (itemId) JOIN material USING (materialId) order by ticketId desc limit 20", $conexion);
			while($ticket = mysql_fetch_assoc($tickets)) {
				echo "<tr class='doubleClickable' ticketId='".$ticket['ticketId']."'>
					<td>".to_MDY($ticket['ticketDate'])."</td>
					<td>".$ticket['projectId']."</td>
					<td>".$ticket['brokerPid']."-".$ticket['truckNumber']."</td>
					<td>".$ticket['materialName']."</td>
					<td>".$ticket['itemDisplayFrom']."</td>
					<td>".$ticket['itemDisplayTo']."</td>
					<td>".$ticket['ticketMfi']."</td>
					<td>".$ticket['ticketNumber']."</td>
				</tr>";
			}
			?>
		</table>
	</div>
</div>
<?include_once '../news.php';?>
</div>
<?
include_once '../app_footer.php';
?>
