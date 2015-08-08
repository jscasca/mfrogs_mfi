<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$responseCode = 0;

if($_REQUEST['tickets'] == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please insert tickets to create the invoice',''));
$tickets = explode('~',$_REQUEST['tickets']);
$supplier = $_REQUEST['supplierId']; if($supplier == '0' || $supplier == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please select a supplier','supplierId'));
$invoice = $_REQUEST['invoice']; if($invoice == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please type the invoice number','invoiceNumber'));
$date = $_REQUEST['date']; if($date == '0') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please type a valid date','invoiceDate'));
$amount = $_REQUEST['amount']; if($amount == '') die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'Please type a valid amount','invoiceAmount'));
$current = $_REQUEST['current']; if($current != $amount) die(wrapFormError(ERROR_CODE_MISSING_PARAMETERS,'The current value is different to the amount typed','invoiceAmount'));
$comment = $_REQUEST['comment']; 

//die(wrapError(-2,'Feature not ready'));
$invoiceId = saveNewVendorInvoice($conexion, $supplier, $invoice, $amount, $comment, $date, $tickets);

echo wrapSubmitResponse(SUCCESS_CODE, $invoiceId);
?>
