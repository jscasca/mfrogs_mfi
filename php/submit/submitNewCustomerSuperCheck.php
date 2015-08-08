<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$responseCode = 0;

$customer = $_REQUEST['customer']; if($customer == '0' || $customer == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a customer',''));
$number = $_REQUEST['number']; if($number == '0' || $number == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a number',''));
$existing = mysql_fetch_assoc(mysql_query("SELECT * FROM customer_super_check WHERE customerId = '$customer' AND customerSuperCheckNumber = '$number'", $conexion));
if($existing!= null) die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,"The number '$number' is already used by the check with ID [".$existing['customerSuperCheckId']."]",''));
$amount = $_REQUEST['amount']; if($amount == '0' || $amount == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a amount',''));
$date = $_REQUEST['date']; if($date == '0' || $date == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a date',''));
$note = $_REQUEST['note'];

$checkId = saveNewCustomerSuperCheck($conexion, $customer, $number, $amount, $date, $note);

echo wrapSubmitResponse(SUCCESS_CODE, $checkId);
?>
