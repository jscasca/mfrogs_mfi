<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

$check = $_REQUEST['paidchequesId']; if($check == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));

//die(wrapError(-2,'Feature not ready'));
$checkId = deleteBrokerCheck($conexion, $check);
mysql_close($conexion);
echo wrapSubmitResponse(0, $check);
?>
