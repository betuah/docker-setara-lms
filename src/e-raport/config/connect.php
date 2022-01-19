<?php
error_reporting(0);

$host = "localhost";
$user = "root";
$pass = "s3T@r4PaUD1kM4sSQL";
$dbnm = "e_raport";

$konek=mysql_connect($host, $user, $pass);
if($konek){
	$buka=mysql_select_db($dbnm);
	if(!$buka){
		die ("Database tidak terhubung!");
	}
} else {
	die ("Server MySQL tidak terhubung!");
}
?>
