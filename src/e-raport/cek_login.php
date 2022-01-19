<?php
include "config/connect.php";
$username=$_POST['username'];
$password=md5($_POST['password1']);
$hak_akses=$_POST['hak_akses'];
?>
<html>
<head>
<link href='css/sweetalert.css' type='text/css' rel='stylesheet' media='screen,projection'>
            <script type='text/javascript' src='js/sweetalert.min.js'></script>
</head>
</html>
<?php

	if($hak_akses=='Admin'){
		$query=mysql_query("SELECT * FROM user WHERE username='$username'");
		$cek=mysql_num_rows($query);
		$data=mysql_fetch_array($query);

		
			if($password==$data['password']){
				session_start();
				$_SESSION['user']=$data['username'];
				$_SESSION['pass']=$data['password'];
				$_SESSION['nama']=$data['nama_user'];
				$_SESSION['telp']=$data['telp'];
				$_SESSION['foto']=$data['foto'];
				$_SESSION['hak']="Administrator";
				header("location:admin/index.php");
			} else {
				echo "Password dan username salah!";
				header("location:index.php");
			}
		
	} elseif($hak_akses=='Guru'){
		$query=mysql_query("SELECT * FROM guru WHERE username='$username'");
		$cek=mysql_num_rows($query);
		$data=mysql_fetch_array($query);

		
			if($password==$data['password']){
				session_start();
				$_SESSION['user']=$data['username'];
				$_SESSION['pass']=$data['password'];
				$_SESSION['nama']=$data['nama_guru'];
				$_SESSION['telp']=$data['telp'];
				$_SESSION['foto']=$data['foto'];
				$_SESSION['hak']="Guru";
				header("location:guru/index.php");
			} else {
				echo "Password dan username salah!";
				header("location:index.php");
			}
		
			
	} elseif($hak_akses='Siswa'){
		$query=mysql_query("SELECT * FROM siswa WHERE nis='$username'");
		$cek=mysql_num_rows($query);
		$data=mysql_fetch_array($query);

		
			if($password==$data['password']){
				session_start();
				$_SESSION['user']=$data['nis'];
				$_SESSION['kode']=$data['kode_siswa'];
				$_SESSION['pass']=$data['password'];
				$_SESSION['nama']=$data['nama_siswa'];
				$_SESSION['telp']=$data['telp'];
				$_SESSION['foto']=$data['foto'];
				$_SESSION['hak']="Siswa";
				header("location:siswa/index.php");
			} else {
				echo "Password dan username salah!";
				header("location:index.php");
			}
		
	}

?>