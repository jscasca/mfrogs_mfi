<?php
$title = $_GET['title'];
$subtitle = $_GET['sub'];
$type = $_GET['type'];
include_once '../report_header.php';
include '../datapack-functions/datapack.php';

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
if($type = 'ticket')$dataTable = getTicketsTable($conexion, $_GET);
if($type = 'broker')$dataTable = getBrokersTable($conexion, $_GET);
if($type = 'driver')$dataTable = getDriversTable($conexion, $_GET);
if($type = 'truck')$dataTable = getTrucksTable($conexion, $_GET);
if($type = 'fuel')$dataTable = getFuelLoadsTable($conexion, $_GET);

for($k = 1; $k < sizeOf($dataTable[0]); $k++) {
	$tableString.="<th>".$dataTable[0][$k]."</th>";
}
for($i = 1; $i < sizeOf($dataTable); $i++) {
	$tableString.="<tr>";
	for($j = 1; $j < sizeOf($dataTable[$i]); $j++) {
		$tableString.='<td align="right">'.$dataTable[$i][$j].'</td>';
	}
	$tableString.="</tr>";
}
mysql_close();
echo $tableString;
?>
</table>

</body>
</html>
