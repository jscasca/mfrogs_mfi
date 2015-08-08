<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

$optionalClause = "";
if($_REQUEST['material'] != 0 ){$optionalClause = "AND materialId = '".$_REQUEST['material']."'";}
if($_REQUEST['item'] != 0 ){$optionalClause = "AND itemId = '".$_REQUEST['item']."'";}

$project = $_REQUEST['project'];
$start = $_REQUEST['startDate'];
$end = $_REQUEST['endDate'];
$comment = $_REQUEST['comment'];

$availability = mysql_fetch_assoc(mysql_query("SELECT count(*) as totalTickets FROM ticket JOIN item USING (itemId) LEFT JOIN invoiceticket USING (ticketId) WHERE projectId = '$project' AND invoiceId IS NULL 
	AND ticketDate BETWEEN '$start' AND '$end' $optionalClause",$conexion));

if($availability['totalTickets'] == 0) die (wrapError(-1, "There are no tickets in the specified dates"));

//die(wrapError(-2,'Feature not ready'));

$queryInvoice = "INSERT INTO invoice (invoiceDate, projectId, invoiceStartDate, invoiceEndDate, invoiceComment) 
	VALUES (CURDATE(),'$project','$start','$end','$comment')";
	
mysql_query($queryInvoice);
$invoiceId = mysql_insert_id();

$queryTickets = "INSERT INTO invoiceticket (ticketId, invoiceId) SELECT ticketId, '$invoiceId' FROM ticket JOIN item USING (itemId) LEFT JOIN invoiceticket USING (ticketId) WHERE projectId = '$project' AND invoiceId IS NULL 
	AND ticketDate BETWEEN '$start' AND '$end' $optionalClause";
mysql_query($queryTickets);
/*
$brokerId = $_REQUEST['brokerId'];
$driverId = $_REQUEST['driverId'];

if($_REQUEST['startDate'] == '') {
	$startDateQuery = "select ticketDate from ticket JOIN truck using (truckId) LEFT JOIN reportticket USING (ticketId) where reportId Is NULL AND brokerId = $brokerId ".($driverId!=0?" AND driverId = $driverId ORDER BY ticketDate asc limit 1":"");
	$startDateInfo = mysql_fetch_assoc(mysql_query($startDateQuery, $conexion));
	$fromDate = $startDateInfo['ticketDate'];
} else {
	$fromDate = to_YMD($_REQUEST['startDate']);
}
//echo $fromDate;
$toDate = ($_REQUEST['endDate'] != "" ? to_YMD($_REQUEST['endDate']) : date('Y-m-d') );
//echo $toDate;
$firstSunday = (isSunday($fromDate) ? $fromDate : lastSunday($fromDate) );
$thisSunday = $firstSunday;
$nextSaturday = getNextSaturday($fromDate);

while( strtotime($thisSunday) <= strtotime($toDate) ){
	//echo $thisSunday." __ ".$nextSaturday."<br/>";
	
	//check for tickets
	$queryTickets = "
		SELECT
			*
		FROM
			ticket
			JOIN truck using (truckId)
			LEFT JOIN reportticket using (ticketId)
		WHERE
			ticketDate BETWEEN '$thisSunday' AND '$nextSaturday'
			AND reportId is null
			AND brokerId = $brokerId 
			".($driverId != 0 ? " AND driverId = ".$driverId : "")."
	";
	//echo $queryTickets."<br/>";
	$ticketsForReport = mysql_query($queryTickets,$conexion);
	if(mysql_num_rows($ticketsForReport) >= 1 ){
		//echo "si hay sueltos<br/>";
		$queryInvoice = "
			INSERT INTO report (
				reportDate,
				brokerId,
				reportStartDate,
				reportEndDate,
				reportType
			)
			values (
				CURDATE(),
				".$_REQUEST['brokerId'].",
				'$thisSunday',
				'$nextSaturday',
				".$_REQUEST['driverId']."
			)";
		mysql_query($queryInvoice,$conexion);
		$reportId = mysql_insert_id();
		
		$insertTickets = "
			INSERT INTO reportticket(
				ticketId,
				reportId
			)
				SELECT
					ticketId,
					".$reportId."
				FROM
					ticket
					JOIN truck using (truckId)
					LEFT JOIN reportticket using (ticketId)
				WHERE
					ticketDate BETWEEN '$thisSunday' AND '$nextSaturday'
					AND reportId is null
					AND brokerId = $brokerId 
					".($driverId != 0 ? " AND driverId = ".$driverId : "")."
		";
		mysql_query($insertTickets,$conexion);
	}
	$thisSunday = date('Y-m-d',strtotime('+1 day',strtotime($nextSaturday)));
	$nextSaturday = getNextSaturday($thisSunday);
}
*/
mysql_close($conexion);

$response['code'] = 0;
$response['created'] = $invoiceId;

echo json_encode($response);

?>
