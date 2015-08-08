<?php
if(isset($_SESSION["mfi_user"])){
	header("Location: /mfi/php/index/index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Homepage</title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<style media="all" type="text/css">@import "css/all.css";</style>
</head>
<body>
	<div id="main">
		<div id="header">
			<img src="img/logo.gif" width="118" height="62" alt="" />
			<ul id="top-navigation">
			
			</ul>
		</div>
		
		<div id="middle">
			<div id="left-column">
				<form id='fvalida' name='fvalida' method='post' action='php/login.php' >
					<h3>Log In</h3>
					<ul class='nav'>
						<?php
						if(isset($_GET['e'])) {
							echo "<li><span style='color:red'>Try again</span></li>";
						}
						?>
						<li>User:</li>
						<li><input type='text' size='10' name='user' id='user' /></li>
						<li>Password: </li>
						<li><input type='password' size='10' name='pass' id='pass' /></li>
						<li class='last'><input type='submit' name='Continuar' id='Continue' value='Continue' /></li>
					</ul>
				</form>
			</div>
			
			<div id="center-column">
			
			</div>
			
			<div id="right-column">
			
			</div>
		</div>
		
		<div id="footer"></div>
	</div>
</body>
</html>
