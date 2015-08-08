<?
$title = "Report";
$subtitle = "Broker Balance";
$type = $_GET['type'];
include_once '../report_header.php';
$invoiceId = $_GET['invoiceId'];

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
	invoice
WHERE
	invoiceId=".$invoiceId;
	
$reg=mysql_query($queryInfo,$conexion);
$projectInfo = mysql_fetch_assoc($reg);

$queryInfo1="
SELECT
	*
FROM
	project 
	JOIN (select addressId, addressLine1 as projectAddress, addressCity as projectCity, addressState as projectState from address) as pA using (addressId)
WHERE
	projectId=".$projectInfo['projectId'];
	
$reg1=mysql_query($queryInfo1,$conexion);
$projectInfo1 = mysql_fetch_assoc($reg1);

$queryInfo2="
SELECT
	*
FROM
	customer 
JOIN term using (termId)
JOIN (select addressId, addressLine1 as customerAddress, addressPOBox as customerBox, addressCity as customerCity, addressState as customerState, addressZip as customerZip from address) as cA using (addressId)
WHERE
	customerId=".$projectInfo1['customerId'];
	
$reg2=mysql_query($queryInfo2,$conexion);
$projectInfo2 = mysql_fetch_assoc($reg2);

$dueDate = date("m/d/Y",strtotime(date("m/d/Y", strtotime($projectInfo['invoiceEndDate']))  . " +". $projectInfo2['termValue']." days"));

$queryInvoice="
SELECT 
	*
FROM
	invoice
JOIN invoiceticket using (invoiceId)
JOIN ticket using (ticketId)
WHERE
	invoiceId=".$invoiceId."
	ORDER BY
	ticketDate,ticketId
";

$invoices = mysql_query($queryInvoice,$conexion);

mysql_close();
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
		<caption>Invoice</caption>
		<tr><th>Date</th><th>Invoice #</th></tr>
		<tr><td><? echo to_MDY($projectInfo['invoiceDate']);?></td><td class="invoiceTd" ><? echo $projectInfo['invoiceId'];?></td></tr>
		<tr><th>Terms</th><th>Due Date</th></tr>
		<tr><td><? echo $projectInfo2['termName'];?></td><td><? echo $dueDate;?></td></tr>
	</table>
</td>
</tr>
<tr>
<td>
	<table class="billinfo">
		<th width="90%">Bill To</th>
		<tr><td width='177' ><? echo $projectInfo2['customerName']; ?></td></tr>
		<tr><td><? echo $projectInfo2['customerAddress']==""?"P.O. Box: ".$projectInfo2['customerBox'] :$projectInfo2['customerAddress'] ; ?></td></tr>
		<tr><td><? echo $projectInfo2['customerCity'].", ".$projectInfo2['customerState']." ".$projectInfo2['customerZip']; ?></td></tr>
		<tr><td><? echo "Ph # ".showPhoneNumber($projectInfo2['customerTel']); ?></td></tr>
		<tr><td><? echo "Fax # ".showPhoneNumber($projectInfo2['customerFax']); ?></td></tr>
	</table>
</td>
<td>
</td>
<td>
	<table class="proinfo">
		<tr><th>Project</th><td><? echo $projectInfo1['projectName'] ?></td></tr>
		<tr><th>Address</th><td><? echo $projectInfo1['projectAddress']." ".$projectInfo1['projectCity'].", ".$projectInfo1['projectState']; ?></th></tr>
	</table>
</td>
</tr>
</table>

<?
if($projectInfo['invoiceComment']!="")echo "
<br>
<strong>*".$projectInfo['invoiceComment']."</strong>
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
while($ticket=mysql_fetch_assoc($invoices))
{
$queryInvoice1="
SELECT 
	*
FROM
	ticket
WHERE
	ticketId=".$ticket['ticketId'];

$invoices1 = mysql_query($queryInvoice1,$conexion);
if(mysql_num_rows($invoices1)==0)continue;
$invoiceInfo1 = mysql_fetch_assoc($invoices1);

$queryInvoice2="
SELECT 
	*
FROM
	item
JOIN material using (materialId)
WHERE
	itemId=".$invoiceInfo1['itemId'];

$invoices2 = mysql_query($queryInvoice2,$conexion);
$invoiceInfo2 = mysql_fetch_assoc($invoices2);

$queryInvoice3="
SELECT
		truckId,
		truckNumber,
		brokerPid
	FROM
		truck
	JOIN broker using(brokerId)
WHERE
	truckId=".$invoiceInfo1['truckId'];

$invoices3 = mysql_query($queryInvoice3,$conexion);
$invoiceInfo3 = mysql_fetch_assoc($invoices3);

	echo "<tr>";
		echo "<td>".to_MDY($invoiceInfo1['ticketDate'])."</td>";
		echo "<td>".$invoiceInfo3['brokerPid']."-".$invoiceInfo3['truckNumber']."</td>";
		echo "<td align=left >".$invoiceInfo1['ticketMfi'];if($invoiceInfo1['ticketNumber']!="" && $invoiceInfo1['ticketNumber']!=$invoiceInfo1['ticketMfi'])echo"/".$invoiceInfo1['ticketNumber'];echo"</td>";
		echo "<td align=left>".$invoiceInfo2['materialName']."</td>";
		echo "<td align=left>".$invoiceInfo2['itemDisplayFrom']."</td>";
		echo "<td align=left>".$invoiceInfo2['itemDisplayTo']."</td>";
		echo "<td >".decimalPad($invoiceInfo1['ticketAmount']);echo"</td>";
		echo "<td align=right >".decimalPad($invoiceInfo2['itemCustomerCost']);echo"</td>";
		echo "<td align=right >".decimalPad($invoiceInfo1['ticketAmount']*$invoiceInfo2['itemCustomerCost']);echo"</td>";
	echo "</tr>";
	$total+=$invoiceInfo1['ticketAmount']*$invoiceInfo2['itemCustomerCost'];
	$count++;
}
echo "<tr><td colspan='2' align=center> $count Tickets </td><td colspan='7'></td></tr>";
echo "<tr><td colspan='7'></td><th><span>Total</span></th><td align=right >".decimalPad($total,2);echo"</td></tr>";
?>
</table>
</body>
</html>
