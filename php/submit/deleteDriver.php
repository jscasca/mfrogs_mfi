<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

$driver= $_REQUEST['driverId']; if($driver == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));
if(getDriverTicketCount($conexion, $driver) > 0) die(wrapError(ERROR_CODE_INVALID_VALUE, 'The driver you want to delete has assigned tickets. Please delete the tickets first.'));

//die(wrapError(-2,'Feature not ready'));
$driverId = deleteDriver($conexion, $driver);
mysql_close($conexion);
echo wrapSubmitResponse(0, $driverId);
?>
