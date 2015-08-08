<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$responseCode = 0;

$invoice = $_REQUEST['reportId'];if($invoice =='') die(wrapFormError(ERROR_CODE_INVALID_VALUE,'Internal Error',''));
$invoiceTotal = getInvoiceTotal($invoice, $conexion);
$invoicePaid = getInvoicePaid($invoice, $conexion);
$invoiceBalance = $invoiceTotal - $invoicePaid;
if($invoiceBalance <= 0) die(wrapFormError(ERROR_CODE_INVALID_VALUE,'This invoice is already paid. Please refresh your page.',''));
$check = $_REQUEST['check']; if($check =='') die(wrapFormError(ERROR_CODE_INVALID_VALUE,'Internal Error',''));
$number = $_REQUEST['number']; if($number =='') die(wrapFormError(ERROR_CODE_INVALID_VALUE,'Internal Error',''));
$date = $_REQUEST['date']; if($date == '0') die(wrapFormError(ERROR_CODE_INVALID_VALUE,'The date si not in a correct format',''));
$amount = $_REQUEST['amount'];
if($amount > $invoiceBalance) die(wrapFormError(ERROR_CODE_INVALID_VALUE,"The amount you want to pay ($amount) is greater than the amount left to pay ($invoiceBalance). Please refresh your page. ",''));
if($check!='0') {
	$checkInfo = getCustomerSuperCheckInfo($conexion, $check);
	if($checkInfo['customerCreditAmount'] == null || $checkInfo['customerCreditAmount'] < $amount) die(wrapFormError(ERROR_CODE_INVALID_VALUE,'The amount you want to pay is greater than the check credit. Please refresh your page.',''));
	
}
/*
$customer = $_REQUEST['customer']; if($customer == '0' || $customer == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a customer',''));
$number = $_REQUEST['number']; if($number == '0' || $number == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a number',''));
$existing = mysql_fetch_assoc(mysql_query("SELECT * FROM customer_super_check WHERE customerId = '$customer' AND customerSuperCheckNumber = '$number'", $conexion));
if($existing!= null) die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,"The number '$number' is already used by the check with ID [".$existing['customerSuperCheckId']."]",''));
$amount = $_REQUEST['amount']; if($amount == '0' || $amount == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a amount',''));
$date = $_REQUEST['date']; if($date == '0' || $date == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a date',''));
$note = $_REQUEST['note'];
*/
//die(wrapError(-2,'Feature not ready'));
$checkId = saveCustomerCheck($conexion, $invoice, $number, $amount, $date, $check);
$response['code'] = 0;
$response['created'] = $checkId;
$response['invoiceBalance'] = $invoiceBalance - $amount;
echo json_encode($response);

?>
