<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

$vendor= $_REQUEST['vendorId']; if($vendor == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));
if(getVendorSupplierCount($conexion, $vendor) > 0) die(wrapError(ERROR_CODE_INVALID_VALUE, 'The vendor you are trying to delete has assigned suppliers. Please delete the suppliers first.'));
//if(getBrokerTicketCount($conexion, $vendor) > 0) die(wrapError(ERROR_CODE_INVALID_VALUE, 'The vendor you want to delete has assigned tickets. Please delete the tickets first.'));

//die(wrapError(-2,'Feature not ready'));
$vendorId = deleteVendor($conexion, $vendor);
mysql_close($conexion);
echo wrapSubmitResponse(0, $vendorId);
?>
