<?php

include_once '../function_header.php';
include '../common_server_functions.php';

$item = $_REQUEST['item']; if($item == '' || $item == '0') die(wrapFormError(ERROR_CODE_DUPLICATE,"Please select an item from the list",'newTicketItem'));
$truck = $_REQUEST['truck']; if($truck == '' || $truck == '0') die(wrapFormError(ERROR_CODE_DUPLICATE,"Please select a truck from the list",'newTicketTruck'));
$driver = $_REQUEST['driver'];
$date = $_REQUEST['date']; if($date == '' || $date == '0') die(wrapFormError(ERROR_CODE_DUPLICATE,"Please type a valid date",'newTicketDate'));
$mfi = $_REQUEST['mfi']; if($mfi == '' || $mfi == '0') die(wrapFormError(ERROR_CODE_DUPLICATE,"Please type a ticket number",'newTicketMfi'));
$number = $_REQUEST['number']; 
$amount = $_REQUEST['amount']; if($amount == '' || $amount == '0') die(wrapFormError(ERROR_CODE_DUPLICATE,"Please type a valid amount",'newTicketAmount'));
$brokerAmount = $_REQUEST['brokerAmount']; if($brokerAmount == '' || $brokerAmount == '0') die(wrapFormError(ERROR_CODE_DUPLICATE,"Please type a valid broker amount",'newTicketBrokerAmount'));
$existing = objectQuery($conexion,'*','ticket',"ticketMfi = '$mfi' AND ticketNumber = '$number'");
if($existing != null) die(wrapFormError(ERROR_CODE_DUPLICATE,"The number '$number' with ticket '$mfi' is already in use by another ticket with ID [".$existing['ticketId']."]",'newTicketNumber'));

//die(wrapError(-2,'Feature not ready'));
$ticketId = saveNewTicket($conexion, $item, $truck, $driver, $date, $amount, $brokerAmount, $number, $mfi);

$response['code'] = 0;
$response['created'] = $ticketId;
$response['newMfi'] = $mfi + 1;
$ticket = objectQuery($conexion,'*','ticket JOIN truck USING (truckId) JOIN broker USING (brokerId) JOIN item USING (itemId) JOIN material USING (materialId)',"ticketId = '$ticketId'");
$response['newLine'] = "<tr class='doubleClickable' ticketId='".$ticket['ticketId']."'>
		<td>".to_MDY($ticket['ticketDate'])."</td>
		<td>".$ticket['projectId']."</td>
		<td>".$ticket['brokerPid']."-".$ticket['truckNumber']."</td>
		<td>".$ticket['materialName']."</td>
		<td>".$ticket['itemDisplayFrom']."</td>
		<td>".$ticket['itemDisplayTo']."</td>
		<td>".$ticket['ticketMfi']."</td>
		<td>".$ticket['ticketNumber']."</td>
	</tr>";
/*
 * echo "<tr class='doubleClickable' ticketId='".$ticket['ticketId']."'>
					<td>".to_MDY($ticket['ticketDate'])."</td>
					<td>".$ticket['projectId']."</td>
					<td>".$ticket['brokerPid']."-".$ticket['truckNumber']."</td>
					<td>".$ticket['materialName']."</td>
					<td>".$ticket['itemDisplayFrom']."</td>
					<td>".$ticket['itemDisplayTo']."</td>
					<td>".$ticket['ticketMfi']."</td>
					<td>".$ticket['ticketNumber']."</td>
				</tr>";
 */
echo json_encode($response);
?>
