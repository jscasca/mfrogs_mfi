<?php
include_once '../nyro_header.php';

?>
<script type="text/javascript">
$(document).ready(function() {
	
	//$('#submitButton').click(function() {
	//$(document).off('click', '#submitEditSupplierButton')
	//$(document).on('click', '#submitEditSupplierButton', function() {
		//closeNM();
		//submitEditSupplier();
	//});
});

function disableButton() {
	$('#submitButton').attr('disabled','disabled');
}

function enableButton() {
	$('#submitButton').removeAttr('disabled');
}

function closeNM() {
	//console.log("closing");
	$.nmTop().close();
}

function doAfterSubmit(data) {
	console.log(data);
	try{
		var obj = jQuery.parseJSON(data);
		switch(obj.code){
			case 0:
				alert("Item proposal created successfully");
				closeNM();
				break;
			case -1:
				alert(obj.msg);
				$('#' + obj.focus).focus();
				break;
			case -4:
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
<?php include '../reusable/newItemProposalForm.php';?>
