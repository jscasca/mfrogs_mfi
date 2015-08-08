<?php
$title = "Broker";
$subtitle = "Payments Report";
$type = $_GET['type'];
include_once '../report_header.php';
include '../datapack-functions/datapack.php';

$customer = $_GET['customerId'];
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
$chequesQuery = "
	SELECT
		*, 
		count(receiptchequesId) as cheques, 
		sum(receiptchequesAmount) as total
	FROM
		receiptcheques
		JOIN invoice USING (invoiceId)
		JOIN project USING (projectId)
		LEFT JOIN customer_super_check USING (customerSuperCheckId)
	WHERE
		receiptchequesDate BETWEEN '$fromDate' AND '$toDate'
		".($customer != 0 ? " AND project.customerId = $customer" : "")."
	GROUP BY receiptchequeNumber
	ORDER BY receiptchequesDate desc
";
//echo $chequesQuery;
$cheques = mysql_query($chequesQuery, $conexion);
$globalTotal = 0;
$toPayTotal = 0;
$paidTotal = 0;

$tableHolder = "";

while($chequeInfo = mysql_fetch_assoc($cheques)){
	//For each report
	if($chequeInfo['cheques'] == 1) {
		//just print one
		$invoiceQuery = "SELECT * FROM invoice JOIN project USING (projectId) WHERE invoiceId = ".$chequeInfo['invoiceId'];
		$invoiceInfo = mysql_fetch_assoc(mysql_query($invoiceQuery, $conexion));
		if($chequeInfo['customerSuperCheckAmount'] == null) {
			$totalChequeAmount = decimalPad($chequeInfo['total']);
			$totalChequeSum = decimalPad($chequeInfo['total']);
			$totalSum = $totalChequeSum;
		} else {
			$totalChequeAmount = decimalPad($chequeInfo['customerSuperCheckAmount']);
			$totalChequeSum = decimalPad($chequeInfo['total']);
			if($totalChequeSum == $totalChequeAmount) {
				$totalSum = $totalChequeSum;
			} else {
				if($totalChequeAmount < $totalChequeSum) {
					$totalSum = "<span style='color:red;'>$totalChequeSum</span>";
				} else {
					$totalSum = "<span style='color:green;'>$totalChequeSum</span>";
				}
			}
		}
		
		$tableHolder.= "<tr>";
		$tableHolder.= "<td>".$chequeInfo['receiptchequeNumber']."</td>";
		$tableHolder.= "<td>".to_MDY($chequeInfo['receiptchequesDate'])."</td>";
		$tableHolder.= "<td>".$totalChequeAmount."</td>";
		$tableHolder.= "<td>".$totalSum."</td>";
		$tableHolder.= "<td>".decimalPad($chequeInfo['receiptchequesAmount'])."</td>";
		$tableHolder.= "<td>".$invoiceInfo['invoiceId']."</td>";
		$tableHolder.= "<td>".$invoiceInfo['projectName']."</td>";
		$tableHolder.= "</tr>";
	} else {
		$rowspan = $chequeInfo['cheques'];
		if($chequeInfo['customerSuperCheckAmount'] == null) {
			$totalChequeAmount = decimalPad($chequeInfo['total']);
			$totalChequeSum = decimalPad($chequeInfo['total']);
			$totalSum = $totalChequeSum;
		} else {
			$totalChequeAmount = decimalPad($chequeInfo['customerSuperCheckAmount']);
			$totalChequeSum = decimalPad($chequeInfo['total']);
			if($totalChequeSum == $totalChequeAmount) {
				$totalSum = $totalChequeSum;
			} else {
				if($totalChequeAmount < $totalChequeSum) {
					$totalSum = "<span style='color:red;'>$totalChequeSum</span>";
				} else {
					$totalSum = "<span style='color:green;'>$totalChequeSum</span>";
				}
			}
		}
		
		$tableHolder.="<tr>";
		$tableHolder.= "<td rowspan='$rowspan'>".$chequeInfo['receiptchequeNumber']."</td>";
		$tableHolder.= "<td rowspan='$rowspan'>".to_MDY($chequeInfo['receiptchequesDate'])."</td>";
		$tableHolder.= "<td rowspan='$rowspan'>".$totalChequeAmount."</td>";
		$tableHolder.= "<td rowspan='$rowspan'>".$totalSum."</td>";
		$first = true;
		$chequesGroupQuery = "SELECT * FROM receiptcheques JOIN invoice USING (invoiceId) JOIN project USING (projectId) WHERE receiptchequeNumber = '".$chequeInfo['receiptchequeNumber']."' ORDER BY invoiceId asc";
		//echo $chequesGroupQuery."<br/>";
		$chequesGroup = mysql_query($chequesGroupQuery, $conexion);
		while($cheque = mysql_fetch_assoc($chequesGroup)) {
			if($first) {
				$first = false;
			} else {
				$tableHolder.= "</tr><tr>\n";
			}
			$tableHolder.= "<td>".decimalPad($cheque['receiptchequesAmount'])."</td>";
			$tableHolder.= "<td>".$cheque['invoiceId']."</td>";
			$tableHolder.= "<td>".$cheque['projectName']."</td>";
		}
		$tableHolder.= "</tr>\n";
	}
	
}
//echo "<tr><td colspan='6'></td><td>".decimalPad($globalTotal)."</td><td>".decimalPad($globalTotal - $paidTotal)."</td><td>".decimalPad($paidTotal)."</td><td colspan='3'></td></tr>";
?>
<tr>
	<th>Cheque Number</th>
	<th>Date</th>
	<th>Cheque Amount</th>
	<th>Paid</th>
	<th>Amount</th>
	<th>Invoice</th>
	<th>Project</th>
</tr>
<?
echo $tableHolder;
?>
</table>

</body>
</html>
