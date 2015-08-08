<?php
$title = "Broker";
$subtitle = "Payments Report";
$type = $_GET['type'];
include_once '../report_header.php';
include '../datapack-functions/datapack.php';


$customerId = $_GET['customerId'];
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
$globalTotal = 0;
$toPayTotal = 0;
$paidTotal = 0;

$totalAmount = 0;
$totalBalance = 0;

$tableHolder = "";
$now = time();

$customers = mysql_query("select * from customer JOIN term using (termId) ".($customerId!=0?" WHERE customerId = ".$customerId:""), $conexion);
while($customer = mysql_fetch_assoc($customers)){
	$shouldAdd = false;
	$tmpHolder = "";
	$customerTotalAmount = 0;
	$customerTotalBalance = 0;
	
	$lastProjectAmount = 0;
	$lastProjectBalance = 0;
	
	
	$termValue = $customer['termValue'];
	
	$invoicesQuery = "
		SELECT
			invoiceId,
			invoiceDate,
			project.projectId,
			projectName,
			SUM(ticketAmount * itemCustomerCost) as totalAmount
		FROM
			project
			JOIN invoice using (projectId)
			JOIN invoiceticket using (invoiceId)
			JOIN ticket using (ticketId)
			JOIN item using (itemId)
		WHERE
			customerId = ".$customer['customerId']."
		GROUP BY
			invoiceId
		ORDER BY
			projectId,
			invoiceId
	";
	$invoices = mysql_query($invoicesQuery, $conexion);
	$projectsIssued = array();
	if(mysql_num_rows($invoices) > 0){
		$tmpHolder = "<tr><th class='customer' colspan='7'>".$customer['customerName']."</th></tr>";
		
		while($invoice = mysql_fetch_assoc($invoices)){
			$paidInfo = mysql_fetch_assoc(mysql_query("SELECT COALESCE(SUM(receiptchequesAmount),0) as totalPaid, count(*) as totalCheques FROM receiptcheques WHERE invoiceId = ".$invoice['invoiceId'],$conexion));
			
			
			$invoiceAmount = decimalPad($invoice['totalAmount']);
			$invoicePaid = decimalPad($paidInfo['totalPaid']);
			$invoiceBalance = decimalPad($invoiceAmount - $invoicePaid);
			if($invoiceBalance == 0) continue;
			else $shouldAdd = true;
			
			if(!isset($projectsIssued[$invoice['projectId']])){
				if($lastProjectAmount!=0){
					$tmpHolder.="<tr><td colspan='5'></td><td align='right'>".decimalPad($lastProjectAmount)."</td><td align='right'>".decimalPad($lastProjectBalance)."</td></tr>";
					$lastProjectAmount = 0;
					$lastProjectBalance = 0;
				}
				
				$tmpHolder.="<tr><td></td><th class='project' colspan='6' >".$invoice['projectName']."</th></tr>";
				$projectsIssued[$invoice['projectId']] =1;
			}
			$startingDate = strtotime($invoice['invoiceDate']);
			$dateDiff = $now - $startingDate;
			$daysOff = floor($dateDiff/(60*60*24));
			
			$dueDate = date('Y-m-d', strtotime($invoice['invoiceDate']. " +".$termValue." days"));
			
			$customerTotalAmount += $invoiceAmount;
			$lastProjectAmount += $invoiceAmount;
			$totalAmount += $invoiceAmount;
			
			$customerTotalBalance += $invoiceBalance;
			$lastProjectBalance += $invoiceBalance;
			$totalBalance += $invoiceBalance;
			
			$tmpHolder.="<tr>";
				$tmpHolder.= "<td></td>";
				$tmpHolder.= "<td>".$invoice['invoiceId']."</td>";
				$tmpHolder.= "<td>".to_MDY($invoice['invoiceDate'])."</td>";
				$tmpHolder.= "<td>".$daysOff."</td>";
				$tmpHolder.= "<td>".to_MDY($dueDate)."</td>";
				$tmpHolder.= "<td align='right'>".$invoiceAmount."</td>";
				$tmpHolder.= "<td align='right'>".$invoiceBalance."</td>";
			$tmpHolder.="</tr>\n";
		}
		$tmpHolder.="<tr><td colspan='5'></td><td align='right'>".decimalPad($lastProjectAmount)."</td><td align='right'>".decimalPad($lastProjectBalance)."</td></tr>";
		$tmpHolder.="<tr><td colspan='5'></td><th class='total' align='right'>".decimalPad($customerTotalAmount)."</th><th class='total' align='right'>".decimalPad($customerTotalBalance)."</th></tr>";
		if($shouldAdd) $tableHolder.= $tmpHolder;
	}
}

echo "<tr><td colspan='5'></td><td align='right'>".decimalPad($totalAmount)."</td><td align='right'>".decimalPad($totalBalance)."</td></tr>";
?>
<tr>
	<th></th>
	<th>Invoice Number</th>
	<th>Date</th>
	<th>Aging</th>
	<th>Due Date</th>
	<th>Amount</th>
	<th>Balance</th>
</tr>
<?
echo $tableHolder;
echo "<tr><td colspan='5'></td><td align='right'>".decimalPad($totalAmount)."</td><td align='right'>".decimalPad($totalBalance)."</td></tr>";
?>
</table>

</body>
</html>
