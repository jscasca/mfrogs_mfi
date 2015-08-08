<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$responseCode = 0;

$supplier = $_REQUEST['supplier']; if($supplier == '0' || $supplier == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));
$material = $_REQUEST['material']; if($material == '0' || $material == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));

//die(wrapError(-2,'Feature not ready'));
deleteSupplierMaterial($conexion, $supplier, $material);

echo wrapSubmitResponse(SUCCESS_CODE, '');
?>
