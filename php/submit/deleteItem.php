<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

$item = $_REQUEST['itemId']; if($item == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));
if(getItemTicketCount($conexion, $item) > 0) die(wrapError(ERROR_CODE_INVALID_VALUE, 'The item you want to delete has assigned tickets. Please delete the tickets first.'));

//die(wrapError(-2,'Feature not ready'));
$itemId = deleteTruck($conexion, $item);
mysql_close($conexion);
echo wrapSubmitResponse(0, $item);
?>
