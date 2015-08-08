<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

$report = $_REQUEST['reportId']; if($report == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));
//check fro payments here

//die(wrapError(-2,'Feature not ready'));
$report = deleteBrokerInvoice($conexion, $report);
mysql_close($conexion);
echo wrapSubmitResponse(0, $report);
?>
