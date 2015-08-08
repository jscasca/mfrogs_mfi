<?php
$title = "Vendor";
$subtitle = "Invoice Report";
$type = $_GET['type'];
include_once '../report_header.php';
include '../datapack-functions/datapack.php';

$vendor = $_GET['vendorId'];
$supplier = $_GET['supplierId'];

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
		</table>
	</td>
	<td width="30%" align="center" >
		<img src='/mfi/img/logo2print.gif' width="140" height="100" />
	</td>
	<td width="30%" align="right" >
		<table class="invinfo">
		<tr><td><? echo "Ph # ".showPhoneNumber($mfiInfo['mfiTel']); ?></td></tr>
		<tr><td><? echo "Fax # ".showPhoneNumber($mfiInfo['mfiFax']); ?></td></tr>
		</table>
	</td>
</tr>
<tr><td colspan='3'><hr></td></tr>
<tr>
	<td></td>
	<td></td>
	<td>
		<table class="dates">
		<tr>
			<th><strong>Date Issued: </strong></th>
			<td><?echo to_MDY(date("Y-m-d"));?></td>
			<td></td>
		</tr>
		<tr>
			<th>From:</th>
			<td><?echo to_MDY($fromDate);?></td>
			<td></td>
		</tr>
		<tr>
			<th>To:</th>
			<td><?echo to_MDY($toDate);?></td>
			<td></td>
		</tr>
	</table>
	</td>
</tr>
</table>

<table align="center" class="report" width="100%" cellspacing="0" >
<?php
$totalAmount = 0;
$toPayTotal = 0;
$paidTotal = 0;

$tableHolder = "";

$reportQuery = "
	SELECT
		*
	FROM
		supplierinvoice
		JOIN supplier using (supplierId)
	WHERE
		supplierInvoiceDate BETWEEN '$fromDate' AND '$toDate'
		AND vendorId = $vendor
		".($supplier != 0 ? " AND supplierId = $supplier" : "")."
	ORDER BY
		supplierInvoiceDate desc
";
//echo $reportQuery;
$reports = mysql_query($reportQuery,$conexion);

while($reportInfo=mysql_fetch_assoc($reports)){
	//For each report
	$paidAmount = "
		SELECT
			SUM(supplierchequeAmount) as totalPaid
		FROM
			suppliercheque
		WHERE
			supplierInvoiceId = ".$reportInfo['supplierInvoiceId']."
	";
	$paidInfo = mysql_fetch_assoc(mysql_query($paidAmount, $conexion));
	
	$invAmount = decimalPad($reportInfo['supplierInvoiceAmount']);
	$invPaid = decimalPad($paidInfo['totalPaid']);
	$invToPay = decimalPad($invAmount - $invPaid);
	
	$tableHolder.= "<tr>";
	$tableHolder.= "<td>".$reportInfo['supplierName']."</td>";
	$tableHolder.= "<td>".$reportInfo['supplierInvoiceNumber']."</td>";
	$tableHolder.= "<td>".to_MDY($reportInfo['supplierInvoiceDate'])."</td>";
	$tableHolder.= "<td align='right'>".$invAmount."</td>";
	$tableHolder.= "<td align='right'>".$invPaid."</td>";
	$tableHolder.= "<td align='right'>".$invToPay."</td>";
	$totalAmount += $invAmount;
	$paidTotal += $invPaid;
	$toPayTotal += $invToPay;
	$tableHolder.= "</tr>";
}

$tableHolder.= "<tr><td colspan='2'></td><th>Total</th><td align='right'>".decimalPad($totalAmount)."</td><td align='right'>".decimalPad($paidTotal)."</td><td align='right'>".decimalPad($toPayTotal)."</td></tr>";
echo "<tr><td colspan='3'></td><td align='right'>".decimalPad($totalAmount)."</td><td align='right'>".decimalPad($paidTotal)."</td><td align='right'>".decimalPad($toPayTotal)."</td></tr>";
?>
<tr>
	<th>Supplier</th>
	<th>Invoice #</th>
	<th>Date</th>
	<th>Amount</th>
	<th>Paid</th>
	<th>To pay</th>
</tr>
<?
echo $tableHolder;
mysql_close();
?>
</table>

</body>
</html>
