<?php
$title = "General Fuel Report";
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
//$query = "SELECT * FROM fuel_load JOIN broker ON (broker.brokerId=fuel_load.brokerId) JOIN truck USING (truckId) WHERE fuelLoadDate BETWEEN '$fromDate' AND '$toDate' ORDER BY fuelLoadStart desc";
$query = "SELECT * FROM fuel_load JOIN broker ON (broker.brokerId=fuel_load.brokerId) JOIN truck USING (truckId) WHERE fuelLoadDate BETWEEN '$fromDate' AND '$toDate' ORDER BY truckId,fuelLoadStart desc";
$loads = mysql_query($query, $conexion);
$lastStart = 0;
$lastTruck = 0;
$lastMileage = 0;
$lastFuel = array();
$totalGals = 0;
$totalMiles = 0;
$truckGals = 0;
$truckMiles = 0;
$truckMpg = 0;
$truckCount = 0;
$totalMpg = 0;
$totalCount = 0;
while($fuel = mysql_fetch_assoc($loads)) {
	$separator = "";
	$gals = $lastFuel['fuelLoadFinish']-$lastFuel['fuelLoadStart'];
	if($lastFuel['truckId'] == $fuel['truckId']) {
		$miles = $lastFuel['fuelLoadMileage'] - $fuel['fuelLoadMileage'];
		$mpg = $miles / $gals;
		$truckGals +=  ($gals > 0 ? $gals : 0);
		$truckMiles += ($miles > 0 ? $miles : 0);
		$truckMpg += ($mpg > 0 ? $mpg : 0);
		$truckCount += ($mpg > 0 ? 1 : 0);
	} else {
		//echo "SELECT * FROM fuel_load WHERE fuelLoadStart > '".$lastFuel['fuelLoadStart']."' ORDER BY fuelLoadStart desc limit 1";
		$lastInDB = mysql_fetch_assoc(mysql_query("SELECT * FROM fuel_load WHERE fuelLoadStart < '".$lastFuel['fuelLoadStart']."' AND truckId = '".$lastFuel['truckId']."' ORDER BY fuelLoadStart desc limit 1", $conexion));
		if($lastInDB == null) {
			$miles = " -- ";
			$mpg = " -- ";
		} else {
			$miles =  $lastFuel['fuelLoadMileage'] - $lastInDB['fuelLoadMileage'];
			$mpg = $miles / $gals;
			$truckMpg += ($mpg > 0 ? $mpg : 0);
			$truckCount += ($mpg > 0 ? 1 : 0);
		}
		$truckGals +=  ($gals > 0 ? $gals : 0);
		$truckMiles += ($miles > 0 ? $miles : 0);
		$totalGals += $truckGals;
		$totalMiles += $truckMiles;
		
		$avgMpg = decimalPad($truckCount > 0? $truckMpg/$truckCount : 0);
		$totalMpg += $avgMpg;
		$totalCount += ($truckMpg > 0 ? 1 : 0);
		if(isset($lastFuel['truckId']))$separator = "<tr><td colspan='4'></td><th>Gallons</th><td>$truckGals</td><th>Miles</th><td>$truckMiles</td><th>MPG</th><td>$avgMpg</td></tr>";
		
		$truckCount = 0;
		$truckMpg = 0;
		$truckMiles = 0;
		$truckGals = 0;
	}
	
	if(isset($lastFuel['fuelLoadId'])) {
	
	
	$tbody.="<tr>
		<td>".to_MDY($lastFuel['fuelLoadDate'])."</td>
		<td>".$lastFuel['brokerName']."</td>
		<td>".$lastFuel['brokerPid']."-".$lastFuel['truckNumber']."</td>
		<td>".$lastFuel['fuelLoadStart']."</td>
		<td>".$lastFuel['fuelLoadFinish']."</td>
		<td>".$gals."</td>
		<td>".$lastFuel['fuelLoadRegistered']."</td>
		<td>".$miles."</td>
		<td>".$lastFuel['fuelLoadMileage']."</td>
		<td>".decimalPad($mpg)."</td>
	</tr>";
	}
	$tbody.=$separator;
	$lastFuel = $fuel;
}
$gals = $lastFuel['fuelLoadFinish']-$lastFuel['fuelLoadStart'];
$lastInDB = mysql_fetch_assoc(mysql_query("SELECT * FROM fuel_load WHERE fuelLoadStart < '".$lastFuel['fuelLoadStart']."' AND truckId = '".$lastFuel['truckId']."' ORDER BY fuelLoadStart desc limit 1", $conexion));
$miles =  $lastFuel['fuelLoadMileage'] - $lastInDB['fuelLoadMileage'];
$mpg = $miles / $gals;
$truckMpg += ($mpg > 0 ? $mpg : 0);
$truckCount += ($mpg > 0 ? 1 : 0);
$avgMpg = decimalPad($truckCount > 0? $truckMpg/$truckCount : 0);

		$truckGals +=  ($gals > 0 ? $gals : 0);
		$truckMiles += ($miles > 0 ? $miles : 0);
		$totalGals += $truckGals;
		$totalMiles += $truckMiles;
		$totalMpg += $avgMpg;
		$totalCount += ($truckMpg > 0 ? 1 : 0);
$tbody.="<tr>
		<td>".to_MDY($lastFuel['fuelLoadDate'])."</td>
		<td>".$lastFuel['brokerName']."</td>
		<td>".$lastFuel['brokerPid']."-".$lastFuel['truckNumber']."</td>
		<td>".$lastFuel['fuelLoadStart']."</td>
		<td>".$lastFuel['fuelLoadFinish']."</td>
		<td>".$gals."</td>
		<td>".$lastFuel['fuelLoadRegistered']."</td>
		<td> ".$miles."</td>
		<td>".$lastFuel['fuelLoadMileage']."</td>
		<td>".decimalPad($mpg)."</td>
	</tr>";
$tbody.= "<tr><td colspan='4'></td><th>Gallons</th><td>$truckGals</td><th>Miles</th><td>$truckMiles</td><th>MPG</th><td>$avgMpg</td></tr>";
$overallMpg = decimalPad($totalMpg/$totalCount);
$tbody.= "<tr><td colspan='4'></td><th>Total Gallons</th><td>$totalGals</td><th>Total Miles</th><td>$totalMiles</td><th>MPG</th><td>$overallMpg</td></tr>";
?>
<tr>
	<th>Date</th>
	<th>Broker</th>
	<th>Truck</th>
	<th>Start</th>
	<th>Finish</th>
	<th>Gals</th>
	<th>Registered</th>
	<th>Miles</th>
	<th>Mileage</th>
	<th>MPG</th>
</tr>
<?
echo $tbody;
?>
</table>

</body>
</html>
