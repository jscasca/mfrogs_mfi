<?php

include_once '../function_header.php';
include '../common_server_functions.php';

//print_r($_REQUEST);

$response = array();

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

mysql_close($conexion);

$response['code'] = 0;

echo json_encode($response);

?>
