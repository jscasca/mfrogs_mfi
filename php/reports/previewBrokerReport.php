<?php
$title = "Report";
$subtitle = "Broker Balance";
$type = $_GET['type'];
include_once '../report_header.php';
include '../datapack-functions/datapack.php';

$broker = $_GET['brokerId'];
$driver = 0;
$isDriver = false;

if($_GET['startDate']==''){$fromDate='0000-00-00';}
else{$fromDate=to_YMD(mysql_real_escape_string($_GET['startDate']));}

if($_GET['endDate']==''){$toDate=date("Y-m-d");}
else{$toDate=to_YMD(mysql_real_escape_string($_GET['endDate']));}

$nextInvoiceId=0;
$queryStatus="SHOW TABLE STATUS LIKE 'report'";
$status = mysql_query($queryStatus,$conexion);
$stat = mysql_fetch_assoc($status);
$nextInvoice = $stat['Auto_increment'];

$brokerInfo = getBasicBrokerInfo($conexion, $broker);
$termDays = $brokerInfo['termValue'];
$percentage = $brokerInfo['brokerPercentage'];
if(isset($_GET['driverId']) && $_GET['driverId'] != '' && $_GET['driverId'] != 0) {
	$driver = $_GET['driverId'];
	$isDriver = true;
	
	$driverInfo = getBasicDriverInfo($conexion, $driver);
	$termDays = $driverInfo['termValue'];
	$percentage = $driverInfo['driverPercentage'];
}

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
		<img src='/trucking/img/logo2print.gif' width="140" height="100" />
	</td>
	<td width="30%" align="right" >
		<table class="invinfo">
			<tr><th>Date</th><td><? echo to_MDY($mfiInfo['CURDATE()']);?></td></tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan='3'><hr></td>
</tr>
<tr>
<td colspan='2'>
	<table class="subcontractor">
		<caption><? if($isDriver)echo"Drivers";?></caption>
		<tr>
			<td><?echo "<strong>".$brokerInfo['brokerName']."</strong> ".($isDriver ? " for ".$driverInfo['driverLastName'].", ".$driverInfo['driverFirstName']: "");?></td>
		</tr>
		<tr>
			<td>
				<table class='insurance'>
					<tr>
						<th>Liability Ins Policy No:</th><td><?echo $brokerInfo['brokerInsuranceLiability'];?></td>
						<th>Wc Ins Policy No:</th><td><?echo $brokerInfo['brokerInsuranceWc'];?></td>
					</tr>
					<tr>
						<th>Liability Ins Expiration Date:</th><td><?echo to_MDY($brokerInfo['brokerLbExpire']);?></td>
						<th>WC Ins Expiration Date:</th><td><?echo to_MDY($brokerInfo['brokerWcExpire']);?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</td>
<td>
	<table class="dates">
		<tr>
			<th>Week:</th>
			<td></td>
			<td class='right'><?echo getDateWeek($fromDate);?></td>
		</tr>
		<tr>
			<th>From:</th>
			<td></td>
			<td class='right'><?echo to_MDY($fromDate);?></td>
		</tr>
		<tr>
			<th>To:</th>
			<td></td>
			<td class='right'><?echo to_MDY($toDate);?></td>
		</tr>
		<tr class="bordermark">
			<th><strong>Due Date</strong></th>
			<td> </td>
			<td class='right'><?echo date('m/d/Y', strtotime('+'.$termDays.' days', strtotime($fromDate)));?></td>
		</tr>
		<tr>
			<th>Report:</th>
			<td></td>
			<td><? echo $nextInvoice; ?></td>
		</tr>
	</table>
</td>
</tr>
</table>

<table align="center" class="report" width="100%" cellspacing="0" >
<tr>
	<th width="8%" >Date</th>
	<th width="13%" >Customer</th>
	<th width="6%" >Truck</th>
	<th width="9%" >Ticket #</th>
	<th width="16%" >From</th>
	<th width="16%" >To</th>
	<th width="5%" >L/T/H</th>
	<th width="6%" >Rate</th>
	<th width="8%" >Amount</th>
	<th width="5%" >%</th>
	<th width="8%" >Total</th>
</tr>

<?php

$queryInvoice="
SELECT 
	*
FROM
	ticket
	JOIN item using (itemId)
	JOIN material using (materialId)
	JOIN truck using (truckId)
	LEFT JOIN reportticket using (ticketId)
WHERE
	reportId IS NULL
	AND brokerId=".$broker." ".($isDriver? " AND driverId = ".$driver : "")."
	AND ticketDate BETWEEN '".$fromDate."' AND '".$toDate."' 
ORDER BY
	ticketDate, ticketId
";
$invoices = mysql_query($queryInvoice,$conexion);

mysql_close();
$total=0;
$count=0;
$subtotal = 0;
while($ticket=mysql_fetch_assoc($invoices)){

$customerInfo = objectQuery($conexion, '*', 'project JOIN customer using (customerId)', "projectId = ".$ticket['projectId']);

	echo "<tr>";
		echo "<td>".to_MDY($ticket['ticketDate'])."</td>";
		echo "<td>".$customerInfo['customerName']."</td>";
		echo "<td>".$brokerInfo['brokerPid']."-".$ticket['truckNumber']."</td>";
		echo "<td align=left >".$ticket['ticketMfi'];if($ticket['ticketNumber']!="")echo"/".$ticket['ticketNumber'];echo"</td>";
		echo "<td align=left>".$ticket['itemDisplayFrom']."</td>";
		echo "<td align=left>".$ticket['itemDisplayTo']."</td>";
		echo "<td >".decimalPad($ticket['ticketBrokerAmount']);echo"</td>";
		echo "<td align=right >".decimalPad($ticket['itemBrokerCost']);echo"</td>";
		echo "<td align=right >".decimalPad($ticket['ticketBrokerAmount']*$ticket['itemBrokerCost']);echo"</td>";
		
		if(strpos(strtolower($ticket['itemDescription']),"toll")===FALSE){
			echo "<td>".decimalPad($projectInfo['brokerPercentage'])."%</td>";
			echo "<td align=right >".decimalPad($ticket['ticketBrokerAmount']*$ticket['itemBrokerCost']*($percentage/100));echo"</td>";
			$total+=$ticket['ticketBrokerAmount']*$ticket['itemBrokerCost']*($percentage/100);
		}else{
			echo "<td>".decimalPad('100')."%</td>";
			echo "<td align=right >".decimalPad($ticket['ticketBrokerAmount']*$ticket['itemBrokerCost']);echo"</td>";
			$total+=$ticket['ticketBrokerAmount']*$ticket['itemBrokerCost'];
		}
		
	echo "</tr>";
	$subtotal+=$ticket['ticketBrokerAmount']*$ticket['itemBrokerCost'];
	
	$count++;
}
echo "<tr><td colspan='2' align=center> $count Tickets </td><td colspan='5'></td><th>Subtotal</th><td align=right >".decimalPad($subtotal);echo"</td><td></td><td align=right >".decimalPad($total);echo"</td></tr>";
echo "<tr><td colspan='9'></td><th><span>Total</span></th><td align=right ><strong>".decimalPad($total);echo"</strong></td></tr>";
?>
</table>

</body>
</html>
