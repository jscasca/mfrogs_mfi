<?
$title = "MFI";
$subtitle = "Customer";

$tab = "CUSTOMER";

include_once '../app_header.php';
?>
<div id="middle">
<?include_once 'navigation.php';?>
<?php include '../reusable/newProposalForm.php';?>
	
<?include_once '../news.php';?>
</div>
<script type="text/javascript">
function doAfterSubmit(data) {
	console.log(data);
	try{
		var obj = jQuery.parseJSON(data);
		switch(obj.code){
			case 0:
				alert("Item Proposal created successfully");
				location.reload();
				break;
			case -1:
				alert(obj.msg);
				$('#' + obj.focus).focus();
				break;
			default:
				alert(obj.msg);
				break;
		}
	} catch(e) {
		alert("Internal Error: Please contact the administrator.");
	}
	enableButton();
}
</script>
<?
include_once '../app_footer.php';
?>
