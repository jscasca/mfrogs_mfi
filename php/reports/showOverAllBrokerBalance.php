<?php
$title = "Broker";
$subtitle = "Unpaid Balance";
$type = $_GET['type'];
include_once '../report_header.php';
include '../datapack-functions/datapack.php';

$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

if($_GET['fromDate']==''){$fromDate='0000-00-00';}
else{$fromDate=to_YMD(mysql_real_escape_string($_GET['fromDate']));}

if($_GET['toDate']==''){$toDate=date("Y-m-d");}
else{$toDate=to_YMD(mysql_real_escape_string($_GET['toDate']));}

?>

<table class="topt" align="center" >
<tr>
	<td width="30%" align="left" >
		<table class="invinfo" width='100%'>
			<caption>Martinez Frogs Inc.</caption>
			<tr><td width='177'><?echo$mfiInfo['addressLine1'];?></td></tr>
			<tr><td><?echo$mfiInfo['addressCity'].", ".$mfiInfo['addressState'].". ".$mfiInfo['addressZip'];?></td></tr>
			<tr><td><? echo "Ph # ".showPhoneNumber($mfiInfo['mfiTel']); ?></td></tr>
			<tr><td><? echo "Fax # ".showPhoneNumber($mfiInfo['mfiFax']); ?></td></tr>
		</table>
	</td>
	<td width="30%" align="center" >
		<img src='/mfi/img/logo2print.gif' width="140" height="100" />
	</td>
	<td width="30%" align="right" >
		<table class="invinfo">
			<tr><th>Date</th><td><? echo to_MDY($mfiInfo['CURDATE()']);?></td></tr>
		</table>
	</td>
</tr>
</table>

<table align="center" class="report" width="100%" cellspacing="0" >
<?php

$tableHolder = "";
$ticketTotaled = 0;
$reportedTotaled = 0;
$paidTotaled = 0;

$ticketsProfitQuery = "
	SELECT
		broker.brokerId,
		broker.brokerName,
		broker.brokerPercentage,
		SUM(ticketBrokerAmount * itemBrokerCost) as totalIncome,
		COUNT(*) as totalTickets
	FROM
		ticket
		JOIN item using (itemId)
		JOIN truck using (truckId)
		JOIN broker using (brokerId)
	WHERE
		ticketDate BETWEEN '$fromDate' AND '$toDate'
	GROUP BY
		brokerName
	ORDER BY
		brokerName
";

$ticketsBalance = mysql_query($ticketsProfitQuery,$conexion);
while($brokerTicketInfo = mysql_fetch_assoc($ticketsBalance)){
	
	$ticketsReportedQuery = "
		SELECT
			SUM(ticketBrokerAmount * itemBrokerCost) as totalReported,
			COUNT(*) as reportedTickets
		FROM
			report
			JOIN reportticket using (reportId)
			JOIN ticket using (ticketId)
			JOIN item using (itemId)
		WHERE
			brokerId = ".$brokerTicketInfo['brokerId']."
			AND ( reportStartDate between '$fromDate' AND '$toDate' OR reportEndDate between '$fromDate' AND '$toDate')
	";
	$reportedInfo = mysql_fetch_assoc(mysql_query($ticketsReportedQuery,$conexion));
	
	$reportsPaidQuery = "
		SELECT
			SUM(paidchequesAmount) as totalPaid,
			COUNT(*) as chequesPaid
		FROM
			report
			JOIN paidcheques using (reportId)
		WHERE
			brokerId = ".$brokerTicketInfo['brokerId']."
			AND ( reportStartDate between '$fromDate' AND '$toDate' OR reportEndDate between '$fromDate' AND '$toDate')
	";
	$paidInfo = mysql_fetch_assoc(mysql_query($reportsPaidQuery,$conexion));
	
	$brokerIncome = $brokerTicketInfo['totalIncome'] * $brokerTicketInfo['brokerPercentage']/100;
	$brokerReported = $reportedInfo['totalReported'] * $brokerTicketInfo['brokerPercentage']/100;
	$brokerPaid = $paidInfo['totalPaid'];
	
	$tableHolder.= "<tr>";
	$tableHolder.= "<td>".$brokerTicketInfo['brokerName']."</td>";
	$tableHolder.= "<td>".decimalPad($brokerTicketInfo['brokerPercentage'])."%</td>";
	$tableHolder.= "<td>".decimalPad($brokerIncome)."</td>";
	$tableHolder.= "<td>(".$brokerTicketInfo['totalTickets'].")</td>";
	$tableHolder.= "<td>".decimalPad($brokerReported)."</td>";
	$tableHolder.= "<td>(".($brokerTicketInfo['totalTickets'] - $reportedInfo['reportedTickets']).")</td>";
	$tableHolder.= "<td>".decimalPad($brokerPaid)."</td>";
	$tableHolder.= "<td>(".$paidInfo['chequesPaid'].")</td>";
	$tableHolder.= "</tr>";
	
	$ticketTotaled += $brokerIncome;
	$reportedTotaled += $brokerReported;
	$paidTotaled += $brokerPaid;
}

//echo $ticketsProfitQuery;
?>
	<tr>
		<td colspan='2'></td>
		<td><? echo decimalPad($ticketTotaled);?></td><td></td>
		<td><? echo decimalPad($reportedTotaled);?></td><td></td>
		<td><? echo decimalPad($paidTotaled);?></td><td></td>
	</tr>
	<tr>
		<th colspan='2'>Broker</th>
		<th >Income</th><th>Total tickets</th>
		<th >In report</th><th>Not reported</th>
		<th >Paid</th><th># cheques</th>
	</tr>

<?php

echo $tableHolder;
?>
</table>

</body>
</html>
