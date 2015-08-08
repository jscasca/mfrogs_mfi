<?php
$title = "General Broker Balance";
$subtitle = "";
$type = $_GET['type'];
include_once '../report_header.php';
include '../datapack-functions/datapack.php';


if($_GET['startDate']==''){$fromDate='0000-00-00';}
else{$fromDate=to_YMD(mysql_real_escape_string($_GET['startDate']));}

if($_GET['endDate']==''){$toDate=date("Y-m-d");}
else{$toDate=to_YMD(mysql_real_escape_string($_GET['endDate']));}

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
</table>

<table align="center" class="report" width="100%" cellspacing="0" >
<?php

$queryTotal = "
							SELECT
								SUM( (ticketBrokerAmount * itemBrokerCost) * (if(item.itemDescription = 'TOLL', 100, if(driver.driverId is null, broker.brokerPercentage, driver.driverPercentage ) ) )/100 ) as totalReported
							FROM
								reportticket
								JOIN report using (reportId)
								JOIN ticket using (ticketId)
								JOIN item using (itemId)
								JOIN broker using (brokerId)
								LEFT JOIN driver on (driver.driverId = report.reportType)
							WHERE
								reportId = ".$reportInfo['reportId']."
						";

$queryInvoices="
SELECT 
	brokerName,
	SUM(ticketBrokerAmount * itemBrokerCost) AS totalGross,
	SUM( (ticketBrokerAmount * itemBrokerCost) * ( if(item.itemDescription = 'TOLL', 100, broker.brokerPercentage)/100 ) ) as totalIncome
FROM 
	ticket
	JOIN item USING ( itemId )
	JOIN truck USING ( truckId )
	JOIN broker USING ( brokerId )
WHERE 
	ticketDate BETWEEN '".$fromDate."' AND '".$toDate."'
GROUP BY brokerId";

//echo $queryInvoices;
$totalGross=0;
$totalIncome=0;
$count=0;
$tbody = "";	
$invoices = mysql_query($queryInvoices,$conexion);					
while($invoice = mysql_fetch_assoc($invoices)){
						

		$tbody.= "<tr>";
			$tbody.= "<td>".$invoice['brokerName']."</td>";
			$tbody.= "<td align=right >$".decimalPad($invoice['totalGross']);$tbody.="</td>";
			$tbody.= "<td align=right >$".decimalPad($invoice['totalIncome']);$tbody.="</td>";
			$tbody.= "<td align=right >$".decimalPad($invoice['totalGross'] - $invoice['totalIncome']);$tbody.="</td>";
		
		$tbody.= "</tr>\n";
		$totalGross += $invoice['totalGross'];
		$totalIncome += $invoice['totalIncome'];
	}
$tbody.= "<tr><th><span>Total</span></th><td align=right ><strong>$".decimalPad($totalGross);$tbody.="</strong></td><td align=right ><strong>$".decimalPad($totalIncome);$tbody.="</strong></td><td align=right ><strong>$".decimalPad($totalGross - $totalIncome);$tbody.="</strong></td></tr>";


echo  "<tr><td></td><td align=right ><strong>$".decimalPad($totalGross);echo"</strong></td><td align=right ><strong>$".decimalPad($totalIncome);echo"</strong></td><td align=right ><strong>$".decimalPad($totalGross - $totalIncome);echo"</strong></td></tr>";

?>
<tr>
	<th width="25%" >Broker</th>
	<th width="25%" >Gross</th>
	<th width="25%" >Income</th>
	<th width="25%" >Profit</th>
</tr>
<?
echo $tbody;
?>
</table>

</body>
</html>
