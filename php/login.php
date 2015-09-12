<?php

require_once 'local_variables.php';
require_once 'conexion.php';

session_start();

$userQuery = 
	"SELECT
		*
	FROM
		users
	WHERE
		userName = '".mysql_escape_string($_POST['user'])."' AND
		userPass = '".mysql_escape_string(md5($_POST['pass']))."'";
$userList = mysql_query($userQuery,$conexion);
$userCount = mysql_num_rows($userList);
if($userCount==0)
{
	header("Location:../index.php?e=true");
}else
{
	$userData = mysql_fetch_assoc($userList);
	$user = new stdClass;
	$user->id = $userData['userId'];
	$user->name = $userData['userName'];
	$user->Fname = $userData['userFirstName'];
	$user->Lname = $userData['userLastName'];
	$_SESSION['mfi_user'] = $user;
	header("Location:index/index.php");
}
?>
