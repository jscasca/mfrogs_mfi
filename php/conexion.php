<?php

//$conexion=mysql_connect("192.168.1.252","remote","remote");
$conexion=mysql_connect(DB_HOST,DB_USER,DB_PASS);

mysql_select_db(DB_STRING,$conexion);

?>
