<?php

function check_loged(){
	global $_SESSION;
	if(!isset($_SESSION["mfi_user"])){
		header("Location: /mfi/index.php");
	}
}

session_start();
check_loged();

?>
