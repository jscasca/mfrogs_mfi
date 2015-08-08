<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

$ticket= $_REQUEST['ticketId']; if($ticket == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));
$inVendorInvoice = objectQuery($conexion,'*','supplierinvoiceticket JOIN supplierinvoice USING (supplierInvoiceId)',"ticketId = '$ticketId'");
if($inVendorInvoice != null) die(wrapError(ERROR_CODE_INVALID_VALUE,"This ticket is in vendor invoice [".$inVendorInvoice['supplierInvoiceNumber']."]. Please delete the invoice first."));
$inBrokerInvoice = objectQuery($conexion,'*','reportticket',"ticketId = '$ticketId'");
if($inBrokerInvoice != null) die(wrapError(ERROR_CODE_INVALID_VALUE,"This ticket is in broker invoice [".$inBrokerInvoice['reportId']."]. Please delete the invoice first."));
$inCustomerInvoice = objectQuery($conexion,'*','invoiceticket',"ticketId = '$ticketId'");
if($inCustomerInvoice != null) die(wrapError(ERROR_CODE_INVALID_VALUE,"This ticket is in customer invoice [".$inVendorInvoice['invoiceId']."]. Please delete the invoice first."));

//die(wrapError(-2,'Feature not ready'));
$ticketId = deleteTicket($conexion, $ticket);
mysql_close($conexion);
echo wrapSubmitResponse(0, $ticketId);
?>
