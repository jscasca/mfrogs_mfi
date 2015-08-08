<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

$supplier= $_REQUEST['supplierId']; if($supplier == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));
if(getSupplierTicketCount($conexion, $supplier) > 0) die(wrapError(ERROR_CODE_INVALID_VALUE, 'The supplier you want to delete has assigned tickets. Please delete the tickets first.'));

//die(wrapError(-2,'Feature not ready'));
$supplierId = deleteSupplier($conexion, $supplier);
mysql_close($conexion);
echo wrapSubmitResponse(0, $supplierId);
?>
