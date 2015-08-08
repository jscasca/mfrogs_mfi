<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

$estimate= $_REQUEST['estimateId']; if($estimate == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));
//die(wrapError(-2,'Feature not ready'));
$estimateId = deleteEstimate($conexion, $estimate);
mysql_close($conexion);
echo wrapSubmitResponse(0, $estimateId);
?>
