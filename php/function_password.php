<?php

function check_loged(){
	global $_SESSION;
	if(!isset($_SESSION["mfi_user"])){
		die(wrapError(ERROR_CODE_EXPIRED, 'The Session has expired'));
	}
}

session_start();
check_loged();

?>
