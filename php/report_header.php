<?php
include_once '../password.php';
include_once '../local_variables.php';
include_once '../variables.php';
include_once '../conexion.php';
include_once '../commons.php';

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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?echo $title." -".$subtitle; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
		<link rel="shortcut icon" href="/mfi/img/favicon.ico" type="image/x-icon" />
	<style media="all" type="text/css">@import "/mfi/css/mfireport.css";</style>
</head>
<script type="text/javascript" src="/mfi/js/jquery-2.0.2.js" ></script>
<script type="text/javascript" src="/mfi/js/json2.js" ></script>
<script type="text/javascript" src="/mfi/js/jquery.nyroModal.custom.js" ></script>
<script type="text/javascript" src="/mfi/js/mfi.functions.js" ></script>
<script type="text/javascript">
</script>
<style type="text/css">
body {
	font-size:12px;
	font-family:"Courier New", Courier, monospace;
}

	
table.report 
{text-align: center;
font-family: Verdana, Geneva, Arial, Helvetica, sans-serif ;
font-weight: normal;
font-size: 11px;
color: #fff;
width: '100%';
background-color: #666;
border: 0px;
border-collapse: collapse;
border-spacing: 0px;}

table.topt
{
width: '90%';
}

table.report td 
{
background-color: #fff;
color: #000;
padding: 4px;
border: 1px #000 solid;}

table.report td.empty{
background-color: #B0C4DE;
}

table.report th
{background-color: #666;
color: #fff;
padding: 4px;
text-align: center;

font-size: 12px;
font-weight: bold;}

table.mfinfo caption{
font-size: 16px;
font-weight: bold;
}
table.mfinfo td{
font-size: 16px;
font-style: italic;
}
table.invinfo caption{
font-size: 20px;
font-weight: bold;
font-style: italic;
}

table.invinfo th
{background-color: #666;
color: #fff;
padding: 4px;
text-align: center;
border-bottom: 2px #fff solid;
font-size: 15px;
font-weight: bold;}

table.billinfo th
{background-color: #666;
color: #fff;
padding: 4px;
width: "100%";
text-align: center;
border-bottom: 2px #fff solid;
font-size: 15px;
font-weight: bold;}

table.billinfo 
{
font-family: Verdana, Geneva, Arial, Helvetica, sans-serif ;
font-weight: normal;
font-size: 11px;
color: #000;
padding: 4px;
text-align: left;
border: 1px #fff solid;}

table.proinfo th
{background-color: #666;
color: #fff;
padding: 4px;
text-align: center;
border-bottom: 2px #fff solid;
font-size: 15px;
font-weight: bold;}

td.invoiceTd{
	text-align: right;
}

.hide_and_print{display:none;}
@media print {
	.show_no_print{display:none;}
	.hide_and_print{display:block;}
}
</style>
<body>
