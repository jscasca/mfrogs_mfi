<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

$report = $_REQUEST['reportId']; if($report == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));

$number = $_REQUEST['number']; if($number == '') die(wrapError(ERROR_CODE_MISSING_PARAMETERS, 'Missing Check Number'));
if($number!='cash'){
	$existingNumber = objectQuery($conexion, '*', 'suppliercheque', "supplierchequeNumber = '$number'");
	if($existingNumber != null) die(wrapError(ERROR_CODE_DUPLICATE, 'Number is used by a different check'));
}
$date = $_REQUEST['date']; 
$amount = $_REQUEST['amount']; if($amount <= 0) die(wrapError(ERROR_CODE_INVALID_VALUE, 'The amount must be a value greater than "0".'));

//die(wrapError(-2,'Feature not ready'));
$checkId = saveSupplierCheck($conexion, $report, $number, $amount, $date);
if($checkId != null && $checkId > 0 ) {
	echo wrapSubmitResponse(SUCCESS_CODE, $checkId);
} else {
	echo wrapError(ERROR_CODE_INTERNAL_ERROR, 'There was an error inserting the check. Please contact the administrator.');
}

mysql_close($conexion);
?>
