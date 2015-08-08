<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

$broker= $_REQUEST['brokerId']; if($broker == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));
if(getBrokerTicketCount($conexion, $broker) > 0) die(wrapError(ERROR_CODE_INVALID_VALUE, 'The broker you want to delete has assigned tickets. Please delete the tickets first.'));

//die(wrapError(-2,'Feature not ready'));
$brokerId = deleteBroker($conexion, $broker);
mysql_close($conexion);
echo wrapSubmitResponse(0, $brokerId);
?>
