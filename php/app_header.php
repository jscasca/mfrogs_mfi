<?php
include_once '../password.php';
include_once '../local_variables.php';
include_once '../variables.php';
include_once '../conexion.php';
include_once '../commons.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?echo $title." -".$subtitle; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
		<link rel="shortcut icon" href="/mfi/img/favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" href="/mfi/css/nyroModal.css" type="image/x-icon" />
	<style media="all" type="text/css">@import "/mfi/css/longView.css";</style>
	<style media="all" type="text/css">@import "/mfi/css/nyroModal.css";</style>
</head>
<script type="text/javascript" src="/mfi/js/jquery-2.0.2.js" ></script>
<script type="text/javascript" src="/mfi/js/json2.js" ></script>
<script type="text/javascript" src="/mfi/js/jquery.nyroModal.custom.js" ></script>
<script type="text/javascript" src="/mfi/js/mfi.functions.js" ></script>
<script type="text/javascript">
$(document).ready(function() {
	//$.nmInternal({debug:true,callbacks: { afterClose:function(nm){doAfterClose();}}});
	$.nmObj({callbacks: {afterClose: function(nm){doAfterClose();}}});
	
	$(document).on('dblclick', '.<?php echo NYRO_CLASS;?>', function() {
		var url = '/mfi/php/nyros/' + $(this).attr('urlRef') + '.php?' + $(this).attr('attrName') + '=' + $(this).attr($(this).attr('attrName'));
		$.nmManual(url);
		//$.nmManual(url,{callbacks:{afterClose: function(nm){doAfterClose();}}});
		//$.nmManual(url,{callbacks:{afterClose: function(){doAfterClose();}}});
		//$.nmManual(url,{callbacks:{initFilters: function(nm){nm.filters.push('link');}}});
	});
	
	$(document).on('click', '.<?php echo ACTION_ICON_CLASS;?>', function() {
		console.log($(this));
		var url = $(this).attr('useurl');
		var data = $(this).attr('withparams');
		var type = $(this).attr('calltype');
		if(type == "show") {
			$.nmManual(url + "?" +data);
		}
		if(type == "confirm") {
			
		}
		if(type == "delete") {
			if(confirm("Are you sure you want to delete this Element?")) {
				doBeforeDelete($(this));
				deleteElement(url, data);
			}
		}
	});
	
});

function enableButton(){}
function disableButton(){}

function closeNM() {
	//console.log("closing");
	$.nmTop().close();
}

function deleteError() {
	
}

function deleteSuccess() {
	
}

function doBeforeDelete(element) {
	$(element).closest('tr').remove();
}

function doAfterDelete(data) {
	try{
		var obj = jQuery.parseJSON(data);
		switch(obj.code){
			case 0:
				deleteSuccess();
				break;
			default:
				alert(obj.msg);
				deleteError();
				break;
		}
	} catch(e) {
		alert("Internal Error: Please contact the administrator.");
	}
}

function doAfterClose() {
	//funciton to be overwritted
	console.log("closed");
}
</script>
<body>
<div id="main">
	<div id="header">
		<a href="/mfi/php/index/index.php" class="logo"><img src="/mfi/img/logo.gif" width="118" height="62" alt="" /></a>
		<a href="/mfi/php/logout.php" class="logout">Logout</a>
		<ul id="top-navigation">
			<li <?if($tab == "HOME")echo "class='active'";?></li><span><span><a href='/mfi/php/index/index.php'>Homepage</a></span></span></li>
			<li <?if($tab == "BROKER")echo "class='active'";?></li><span><span><a href='/mfi/php/broker/index.php'>Broker</a></span></span></li>
			<li <?if($tab == "CUSTOMER")echo "class='active'";?></li><span><span><a href='/mfi/php/customer/index.php'>Customer</a></span></span></li>
			<li <?if($tab == "VENDOR")echo "class='active'";?></li><span><span><a href='/mfi/php/vendor/index.php'>Vendor</a></span></span></li>
			<li <?if($tab == "INVOICING")echo "class='active'";?></li><span><span><a href='/mfi/php/invoicing/index.php'>Invoicing</a></span></span></li>
			<li <?if($tab == "FUEL")echo "class='active'";?></li><span><span><a href='/mfi/php/fuel/index.php'>Fuel</a></span></span></li>
			<li <?if($tab == "MAP")echo "class='active'";?></li><span><span><a href='/mfi/php/map/index.php'>Maps</a></span></span></li>
			<li <?if($tab == "SEARCH")echo "class='active'";?></li><span><span><a href='/mfi/php/search/index.php'>Search</a></span></span></li>
		
		</ul>
	</div>
