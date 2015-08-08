<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

$item = $_REQUEST['itemProposalId']; if($item == '') die(wrapError(ERROR_CODE_FIVE,'INTERNAL ERROR'));

//die(wrapError(-2,'Feature not ready'));
$itemId = deleteItemProposal($conexion, $item);
mysql_close($conexion);
echo wrapSubmitResponse(0, $item);
?>
