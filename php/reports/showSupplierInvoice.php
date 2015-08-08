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
	supplierinvoice
WHERE
	supplierInvoiceId=".$invoiceId;
	
$reg=mysql_query($queryInfo,$conexion);
$projectInfo = mysql_fetch_assoc($reg);

$queryInfo2="
SELECT
	*
FROM
	supplier
WHERE
	supplierId=".$projectInfo['supplierId'];
	
$reg2=mysql_query($queryInfo2,$conexion);
$projectInfo2 = mysql_fetch_assoc($reg2);

$queryInfo3="
SELECT
	vendorId, vendorName 
FROM
	vendor
WHERE
	vendorId=".$projectInfo2['vendorId'];
	
$reg3=mysql_query($queryInfo3,$conexion);
$projectInfo3 = mysql_fetch_assoc($reg3);

$queryInfo4="
SELECT
	addressId, addressLine1 as supplierAddress, addressCity as supplierCity, addressState as supplierState, addressZip as supplierZip
FROM
	address
WHERE
	addressId=".$projectInfo2['addressId'];
	
$reg4=mysql_query($queryInfo4,$conexion);
$projectInfo4 = mysql_fetch_assoc($reg4);

$queryInfo5="
select MAX(ticketDate) as maxT, supplierInvoiceId 
	from supplierinvoiceticket 
join ticket using (ticketId) 
Where 
	supplierInvoiceId=".$invoiceId. "
	group by supplierInvoiceId";
	
$reg5=mysql_query($queryInfo5,$conexion);
$projectInfo5 = mysql_fetch_assoc($reg5);

$queryInfo6="
select MIN(ticketDate) as minT, supplierInvoiceId 
	from supplierinvoiceticket 
	join ticket using (ticketId)
Where 
	supplierInvoiceId=".$invoiceId."
	group by supplierInvoiceId";
	
$reg6=mysql_query($queryInfo6,$conexion);
$projectInfo6 = mysql_fetch_assoc($reg6);

//$dueDate = date("m/d/Y",strtotime(date("m/d/Y", strtotime($projectInfo['invoiceEndDate']))  . " +". $projectInfo['termValue']." days"));

$queryInvoice="
SELECT 
	*
FROM
	supplierinvoiceticket
WHERE
	supplierInvoiceId=".$invoiceId."
	ORDER BY 
	ticketId
	";
//echo $queryInvoice;
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
		
	</table>
</td>
<td width="30%" align="center" >
	<img src='/trucking/img/logo2print.gif' width="140" height="100" />
</td>
<td width="30%" align="right" >
	<table class="invinfo">
		<caption>Invoice</caption>
		<tr>
			<td><? echo $projectInfo['supplierInvoiceNumber'];?></td>
		</tr>
	</table>
</td>
</tr>
<tr>
<td>
	<table class="billinfo">
		<tr>
		<th width="90%">Bill From <?echo $projectInfo3['vendorName'];?></th>
		</tr>
		<tr><td><?echo $projectInfo2['supplierName'];?></td></tr>
		<tr><td><?echo $projectInfo4['supplierAddress'];?></td></tr>
		<tr><td><?echo $projectInfo4['supplierCity'].", ".$projectInfo4['supplierState']." ".$projectInfo4['supplierZip'];?></td></tr>
	</table>
</td>
<td>
</td>
<td>
	<table class="proinfo">
		<tr><th>Starting: </th><td><?echo to_MDY($projectInfo6['minT']);?></td></tr>
		<tr><th>Ending: </th><td><?echo to_MDY($projectInfo5['maxT']);?></td></tr>
	</table>
</td>
</tr>
</table>

<?
if($projectInfo['supplierInvoiceComment']!="")echo "
<br>
*".$projectInfo['supplierInvoiceComment']."
<br>";
?>
<br>

<table align="center" class="report" width="100%" cellspacing="0" >
<tr>
	<th width="10%" >Date</th>
	<th width="8%" >Truck</th>
	<th width="6%" >MFI</th>
	<th width="6%" >Ticket</th>
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

$queryInvoice2="
SELECT 
	*
FROM
	ticket
WHERE
	ticketId=".$ticket['ticketId']."
	ORDER BY ticketDate asc";
//echo $queryInvoice;
$invoices2 = mysql_query($queryInvoice2,$conexion);
if(mysql_num_rows($invoices2)==0)continue;
$invoices2Info = mysql_fetch_assoc($invoices2);

$queryInvoice3="
SELECT 
	*
FROM
	item
WHERE
	itemId=".$invoices2Info['itemId'];

$invoices3 = mysql_query($queryInvoice3,$conexion);
$invoices3Info = mysql_fetch_assoc($invoices3);

$queryInvoice4="
SELECT 
	*
FROM
	material
WHERE
	materialId=".$invoices3Info['materialId'];

$invoices4 = mysql_query($queryInvoice4,$conexion);
$invoices4Info = mysql_fetch_assoc($invoices4);

$queryInvoice5="
SELECT 
	*
FROM
	project
WHERE
	projectId=".$invoices3Info['projectId'];

$invoices5 = mysql_query($queryInvoice5,$conexion);
$invoices5Info = mysql_fetch_assoc($invoices5);

$queryInvoice6="
SELECT 
	truckId, truckNumber, brokerPid
FROM
	truck
	Join broker using (brokerId)
WHERE
	truckId=".$invoices2Info['truckId'];

$invoices6 = mysql_query($queryInvoice6,$conexion);
$invoices6Info = mysql_fetch_assoc($invoices6);

	echo "<tr>";
		echo "<td>".to_MDY($invoices2Info['ticketDate'])."</td>";
		echo "<td>".$invoices6Info['brokerPid']."-".$invoices6Info['truckNumber']."</td>";
		echo "<td align=left >".$invoices2Info['ticketMfi']."</td>";
		echo "<td align=left >".$invoices2Info['ticketNumber']."</td>";
		echo "<td align=left>".$invoices4Info['materialName']."</td>";
		echo "<td align=left>".$invoices3Info['itemDisplayFrom']."</td>";
		echo "<td align=left>".$invoices3Info['itemDisplayTo']."</td>";
		echo "<td >".decimalPad($invoices2Info['ticketAmount']);echo"</td>";
		echo "<td align=right >".decimalPad($invoices3Info['itemMaterialPrice']);echo"</td>";
		echo "<td align=right >".decimalPad($invoices2Info['ticketAmount']*$invoices3Info['itemMaterialPrice']);echo"</td>";
	echo "</tr>";
	$total+=$invoices2Info['ticketAmount']*$invoices3Info['itemMaterialPrice'];
	$count++;
}
echo "<tr><td colspan='2' align=center> $count Tickets </td><td colspan='8'></td></tr>";
echo "<tr><td colspan='8'></td><th><span>Total</span></th><td align=right >".decimalPad($total);echo"</td></tr>";
?>
</table>
</body>
</html>
