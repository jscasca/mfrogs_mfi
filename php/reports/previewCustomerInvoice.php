<?php
$title = "Report";
$subtitle = "Broker Balance";
$type = $_GET['type'];
include_once '../report_header.php';
include '../datapack-functions/datapack.php';

$nextInvoiceId=0;
$queryStatus="SHOW TABLE STATUS LIKE 'invoice'";
$status = mysql_query($queryStatus,$conexion);
$stat = mysql_fetch_assoc($status);
$nextInvoice = $stat['Auto_increment'];

$optionalClause = "";

if(isset($_GET['material']) && $_GET['material']!=0) { $optionalClause = " AND materialId = ".$_GET['material']; }
if(isset($_GET['item']) && $_GET['item']!=0) { $optionalClause = " AND itemId = ".$_GET['item']; }

if($_GET['startDate']=='0'){$startDate='0000-00-00';}
else{$startDate=$_GET['startDate'];}

if($_GET['endDate']=='0'){$endDate=date("Y-m-d");}
else{$endDate=$_GET['endDate'];}


$project = $_GET['project'];

$queryMfi="
SELECT
	*,
	CURDATE()
FROM
	mfiinfo
JOIN address using (addressId)
";
$frogsInfo=mysql_query($queryMfi,$conexion);
$mfiInfo = mysql_fetch_assoc($frogsInfo);

$queryInfo="
SELECT
	*
FROM
	project
WHERE
	projectId=".$project;

$projectReg = mysql_query($queryInfo,$conexion);
$projectInfo = mysql_fetch_assoc($projectReg);

$queryInfo2="
SELECT addressId, addressLine1 as projectAddress, addressCity as projectCity, addressState as projectState
FROM
	address
WHERE
	addressId=".$projectInfo['addressId'];

$reg2=mysql_query($queryInfo2,$conexion);
$projectInfo2 = mysql_fetch_assoc($reg2);


$queryInfo3="
SELECT *
FROM
	customer
JOIN term using (termId)
WHERE
	customerId=".$projectInfo['customerId'];
	
$reg3=mysql_query($queryInfo3,$conexion);
$projectInfo3 = mysql_fetch_assoc($reg3);

$queryInfo4="
SELECT addressId, addressLine1 as customerAddress, addressCity as customerCity, addressState as customerState, addressZip as customerZip
FROM
	address
WHERE
	addressId=".$projectInfo3['addressId'];
	
$reg4=mysql_query($queryInfo4,$conexion);
$projectInfo4 = mysql_fetch_assoc($reg4);

$dueDate = date("m/d/Y",strtotime(date("m/d/Y", strtotime($endDate))  . " +". $projectInfo3['termValue']." days"));

//$dueDate = 0 + $projectInfo['termValue'];

$queryTickets="
SELECT * 
FROM 
	ticket
	JOIN item USING ( itemId ) 
	JOIN material USING (materialId)
	JOIN project USING ( projectId ) 
	JOIN truck USING (truckId)
	JOIN broker USING (brokerId)
	LEFT JOIN invoiceticket USING (ticketId)
WHERE projectId =".$project." 
	AND invoiceId IS NULL
	$optionalClause
	AND ticketDate BETWEEN '".mysql_real_escape_string($startDate)."' and '".mysql_real_escape_string($endDate)."'
ORDER BY
	ticketDate, ticketId
";
$tickets = mysql_query($queryTickets,$conexion);
$arraytickets = array();

$result=count($arraytickets);

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
	<img src='/tmfi/img/logo2print.gif' width="140" height="100" />
</td>
<td width="30%" align="right" >
	<table class="invinfo">
		<caption>Invoice</caption>
		<tr><th>Date</th><th>Invoice #</th></tr>
		<tr><td><? echo to_MDY($mfiInfo['CURDATE()']);?></td><td class="invoiceId"><? echo $nextInvoice;?></td></tr>
		<tr><th>Terms</th><th>Due Date</th></tr>
		<tr><td><? echo $projectInfo3['termName'];?></td><td><? echo $dueDate;?></td></tr>
	</table>
</td>
</tr>
<tr>
<td>
	<table class="billinfo">
		<th width="90%">Bill To</th>
		<tr><td width='177' ><? echo $projectInfo3['customerName']; ?></td></tr>
		<tr><td><? echo $projectInfo4['customerAddress']; ?></td></tr>
		<tr><td><? echo $projectInfo4['customerCity'].", ".$projectInfo4['customerState']." ".$projectInfo4['customerZip']; ?></td></tr>
		<tr><td><? echo "Ph # ".showPhoneNumber($projectInfo3['customerTel']); ?></td></tr>
		<tr><td><? echo "Fax # ".showPhoneNumber($projectInfo3['customerFax']); ?></td></tr>
	</table>
</td>
<td>
</td>
<td>
	<table class="proinfo">
		<tr><th>Project</th><td><? echo $projectInfo['projectName']; ?></td></tr>
		<tr><th>Address</th><td><? echo $projectInfo2['projectAddress']." ".$projectInfo2['projectCity'].", ".$projectInfo2['projectState']; ?></th></tr>
	</table>
</td>
</tr>
</table>
<?
if ($_GET['comment']!="") 
echo "
<br>
*{$_GET['comment']}
<br>";
?>
<br>

<table align="center" class="report" width="100%" cellspacing="0" >
<tr>
	<th width="10%" >Date</th>
	<th width="7%" >Truck</th>
	<th width="13%" >Ticket #</th>
	<th width="14%" >Material</th>
	<th width="23%" >From</th>
	<th width="23%" >To</th>
	<th width="5%" >L/T/H</th>
	<th width="10%" >Cost</th>
	<th width="10%" >Amount</th>
</tr>

<?php
$total=0;
$count=0;


while($ticket = mysql_fetch_assoc($tickets)) {

	echo "<tr>";
		echo "<td>".to_MDY($ticket['ticketDate'])."</td>";
		echo "<td>".$ticket['brokerPid']."-".$ticket['truckNumber']."</td>";
		echo "<td align=left >".$ticket['ticketMfi'];if($ticket['ticketNumber']!="")echo"/".$ticket['ticketNumber'];echo"</td>";
		echo "<td align=left>".$ticket['materialName']."</td>";
		echo "<td align=left>".$ticket['itemDisplayFrom']."</td>";
		echo "<td align=left>".$ticket['itemDisplayTo']."</td>";
		echo "<td >";printf("%01.2f",$ticket['ticketAmount']);echo"</td>";
		echo "<td align=right >";printf("%01.2f",$ticket['itemCustomerCost']);echo"</td>";
		echo "<td align=right >";printf("%01.2f",$ticket['ticketAmount']*$ticket['itemCustomerCost']);echo"</td>";
	echo "</tr>";
	$total+=$ticket['ticketAmount']*$ticket['itemCustomerCost'];
	$count++;
}
echo "<tr><td colspan='2' align=center> $count Tickets </td><td colspan='7'></td></tr>";
echo "<tr><td colspan='7'></td><th><span>Total</span></th><td align=right >";printf("%01.2f",$total);echo"</td></tr>";
mysql_close();
?>
</table>

</body>
</html>
