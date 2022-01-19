<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	 <link href="../css/sweetalert.css" type="text/css" rel="stylesheet" media="screen,projection">
	 <script type="text/javascript" src="../js/sweetalert.min.js"></script>
</head>
<body>
	
</body>
</html>

<?php
include"../config/connect.php";
$menu=$_GET['menu'];
$aksi=$_GET['aksi'];
if(isset($menu) AND $aksi=="hapus"){
	if($menu=="kelassiswa"){$menu="datakelas";}
	$hapus=mysql_query("Delete from ".$menu." where kode_".$menu."='$_GET[id]'")or die("gagal".mysql_error());
	if($hapus){
		if($menu=="datakelas"){$menu="kelassiswa";}
		echo "<script>swal({
			title: 'Terhapus', 
			text: 'Data telah dihapus.', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
}
elseif($menu=="guru" AND $aksi=="tambah"){
	$pass=md5($_POST['password1']);
	$lokasi_file=$_FILES['foto']['tmp_name'];
	$nama_foto=$_FILES['foto']['name'];
	$tipe_file=$_FILES['foto']['type'];
	$ukuran_file=$_FILES['foto']['size'];
	$nama_baru = preg_replace("/\s+/", "_", $_POST['kode_guru'].$nama_foto);
	$direktori = "foto/$nama_baru";
	$maks_file=500000; //500kb

	//cek apakah username sudah ada atau belum
	$query = "SELECT username FROM guru WHERE username = '$_POST[username]'";
	$hasil = mysql_query($query);
	$data  = mysql_fetch_array($hasil);
	if($data['username']==$_POST['username']){
		echo "<script>swal({
			title: 'Oops...', 
			text: 'Username sudah ada, silahkan menggunakan yang lain!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	}

	//cek apakah format file adalah format gambar
	$formatgambar = array("image/jpg", "image/jpeg","image/gif", "image/png");
	if(!in_array($tipe_file, $formatgambar)) {
	  echo "<script>swal({
			title: 'Oops...', 
			text: 'Format file harus berupa gambar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();

	}
	//cek apakah ukuran file diatas 500kb 
	if($ukuran_file > $maks_file) {
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Ukuran file terlalu besar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	}
	move_uploaded_file($lokasi_file,"$direktori");
	$tambah=mysql_query("insert into guru(kode_guru, nip, username, password, nama_guru,   alamat, status, jenis_kelamin, foto,  telp)	VALUES('$_POST[kode_guru]','$_POST[nip]','$_POST[username]','$pass', '$_POST[nama_guru]',  '$_POST[alamat]', '$_POST[kolomstatus]','$_POST[jenis_kelamin]', '$nama_baru' , '$_POST[telp]')");
	if($tambah)
{		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah tersimpan!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	} else {
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Ada kesalahan, coba lagi!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
	}
}
elseif($menu=="guru" AND $aksi=="edit"){
	$query = "SELECT * FROM guru WHERE nip = '$_POST[nip]'";
	$hasil = mysql_query($query);
	$data  = mysql_fetch_array($hasil);

	
	$lokasi_file=$_FILES['edit_foto']['tmp_name'];
	$nama_foto=$_FILES['edit_foto']['name'];
	$tipe_file=$_FILES['edit_foto']['type'];
	$ukuran_file=$_FILES['edit_foto']['size'];
	$nama_baru = preg_replace("/\s+/", "_", $_POST['nip'].$nama_foto);
	$direktori = "foto/$nama_baru";
	$maks_file=500000; //500kb
	//cek apakah format file adalah format gambar
	$formatgambar = array("image/jpg", "image/jpeg","image/gif", "image/png"); 

	if(!empty($_POST['password1']) and !empty($_POST['password2']) and !empty($_POST['password3'])){
		$passwordlama  = $_POST['password1'];
		$passwordbaru1 = $_POST['password2'];
		$passwordbaru2 = $_POST['password3'];
	}

	if(empty($lokasi_file) and empty($passwordbaru1)){
	$edit=mysql_query("Update guru set nama_guru='$_POST[nama_guru]',
								 alamat='$_POST[alamat]',
								 status='$_POST[status]',
								 jenis_kelamin='$_POST[jenis_kelamin]',
								 telp='$_POST[telp]'
								 where username='$_POST[username]'")or die("gagal".mysql_error());
	if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
	} elseif(!empty($lokasi_file) and !empty($passwordbaru1)){
	if(!in_array($tipe_file, $formatgambar)) {
	  echo "<script>swal({
			title: 'Oops...', 
			text: 'Format file harus berupa gambar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();

	}
	//cek apakah ukuran file diatas 500kb 
	if($ukuran_file > $maks_file) {
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Ukuran file terlalu besar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	}
	if ($data['password'] == md5($passwordlama)){
		$passwordbaruenkrip = md5($passwordbaru1);

		move_uploaded_file($lokasi_file,"$direktori");
		$edit=mysql_query("Update guru set password='$passwordbaruenkrip',
								 nama_guru='$_POST[nama_guru]',
							     alamat='$_POST[alamat]',
							     jenis_kelamin='$_POST[jenis_kelamin]',
								 telp='$_POST[telp]',
								 foto='$nama_baru'
								 where username='$_POST[username]'")or die("gagal".mysql_error());
		if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
		}
	} else {
		echo "<script>swal({
			title: 'Oops...', 
			text: 'Password lama salah!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
	}
	
	
	} 

	if(empty($lokasi_file) and !empty($passwordbaru1)){
	if ($data['password'] == md5($passwordlama)){
		$passwordbaruenkrip = md5($passwordbaru1);

		$edit=mysql_query("Update guru set password='$passwordbaruenkrip',
								 nama_guru='$_POST[nama_guru]',
							     alamat='$_POST[alamat]',
							     jenis_kelamin='$_POST[jenis_kelamin]',
								 telp='$_POST[telp]'
								 where username='$_POST[username]'")or die("gagal".mysql_error());
		if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
		}
	} else {
		echo "<script>swal({
			title: 'Oops...', 
			text: 'Password lama salah!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
	}
	
	} elseif(!empty($lokasi_file) and empty($passwordbaru1)){
	if(!in_array($tipe_file, $formatgambar)) {
	  echo "<script>swal({
			title: 'Oops...', 
			text: 'Format file harus berupa gambar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();

	}
	//cek apakah ukuran file diatas 500kb 
	if($ukuran_file > $maks_file) {
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Ukuran file terlalu besar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	}

		move_uploaded_file($lokasi_file,"$direktori");
		$edit=mysql_query("Update guru set nama_guru='$_POST[nama_guru]',
							     alamat='$_POST[alamat]',
							     jenis_kelamin='$_POST[jenis_kelamin]',
								 telp='$_POST[telp]',
								 foto='$nama_baru'
								 where username='$_POST[username]'")or die("gagal".mysql_error());
	
	if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
	} 

}

elseif($menu=="siswa" AND $aksi=="tambah"){
	$tanggal=$_POST['tgl_lahir'];
	list($tgl,$bln,$thn)=explode("/", $tanggal);
	$pass=md5($_POST['password1']);
	$lokasi_file=$_FILES['foto']['tmp_name'];
	$nama_foto=$_FILES['foto']['name'];
	$tipe_file=$_FILES['foto']['type'];
	$ukuran_file=$_FILES['foto']['size'];
	$nama_baru = preg_replace("/\s+/", "_", $_POST['kode_siswa'].$nama_foto);
	$direktori = "foto/$nama_baru";
	$maks_file=500000; //500kb
	//cek apakah format file adalah format gambar
	$formatgambar = array("image/jpg", "image/jpeg","image/gif", "image/png");
	if(!in_array($tipe_file, $formatgambar)) {
	  echo "<script>swal({
			title: 'Oops...', 
			text: 'Format file harus berupa gambar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();

	}
	//cek apakah ukuran file diatas 500kb 
	if($ukuran_file > $maks_file) {
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Ukuran file terlalu besar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	}
	move_uploaded_file($lokasi_file,"$direktori");
	$tambah=mysql_query("insert into siswa(kode_siswa, nis, password, nama_siswa,   alamat, tmp_lahir ,tgl_lahir, agama, status, jenis_kelamin, tahun_angkatan, foto,  telp)	VALUES('$_POST[kode_siswa]','$_POST[nis]','$pass', '$_POST[nama_siswa]',  '$_POST[alamat]', '$_POST[tmp_lahir]', '$thn$bln$tgl', '$_POST[agama]', '$_POST[kolomstatus]','$_POST[jenis_kelamin]', '$_POST[tahun_angkatan]', '$nama_baru' , '$_POST[telp]')");
	if($tambah)
{		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah tersimpan!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	} else {
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Ada kesalahan, coba lagi!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
	}
}

elseif($menu=="siswa" AND $aksi=="edit"){
	$tanggal=$_POST['tgl_lahir'];
	list($tgl,$bln,$thn)=explode("/", $tanggal);


	$query = "SELECT * FROM siswa WHERE nis = '$_POST[nis]'";
	$hasil = mysql_query($query);
	$data  = mysql_fetch_array($hasil);

	$lokasi_file=$_FILES['edit_foto']['tmp_name'];
	$nama_foto=$_FILES['edit_foto']['name'];
	$tipe_file=$_FILES['edit_foto']['type'];
	$ukuran_file=$_FILES['edit_foto']['size'];
	$nama_baru = preg_replace("/\s+/", "_", $_POST['nis'].$nama_foto);
	$direktori = "foto/$nama_baru";
	$maks_file=500000; //500kb
	//cek apakah format file adalah format gambar
	$formatgambar = array("image/jpg", "image/jpeg","image/gif", "image/png"); 

	if(!empty($_POST['password1']) and !empty($_POST['password2']) and !empty($_POST['password3'])){
		$passwordlama  = $_POST['password1'];
		$passwordbaru1 = $_POST['password2'];
		$passwordbaru2 = $_POST['password3'];
	}

	if(empty($lokasi_file) and empty($passwordbaru1)){
	$edit=mysql_query("Update siswa set nama_siswa='$_POST[nama_siswa]',
								 alamat='$_POST[alamat]',
								 tmp_lahir='$_POST[tmp_lahir]',
								 tgl_lahir='$thn$bln$tgl',
								 agama='$_POST[agama]',
								 status='$_POST[kolomstatus]',
								 tahun_angkatan='$_POST[tahun_angkatan]',
								 jenis_kelamin='$_POST[jenis_kelamin]',
								 telp='$_POST[telp]'
								 where nis='$_POST[nis]'")or die("gagal".mysql_error());
	if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
	} elseif(!empty($lokasi_file) and !empty($passwordbaru1)){

	if(!in_array($tipe_file, $formatgambar)) {
	  echo "<script>swal({
			title: 'Oops...', 
			text: 'Format file harus berupa gambar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();

	}
	//cek apakah ukuran file diatas 500kb 
	if($ukuran_file > $maks_file) {
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Ukuran file terlalu besar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	}
	if ($data['password'] == md5($passwordlama)){
		$passwordbaruenkrip = md5($passwordbaru1);

		move_uploaded_file($lokasi_file,"$direktori");
		$edit=mysql_query("Update siswa set password='$passwordbaruenkrip',
								 nama_siswa='$_POST[nama_siswa]',
							     alamat='$_POST[alamat]',
							     tmp_lahir='$_POST[tmp_lahir]',
								 tgl_lahir='$thn$bln$tgl',
								 agama='$_POST[agama]',
								 status='$_POST[kolomstatus]',
								 tahun_angkatan='$_POST[tahun_angkatan]',
							     jenis_kelamin='$_POST[jenis_kelamin]',
								 telp='$_POST[telp]',
								 foto='$nama_baru'
								 where nis='$_POST[nis]'")or die("gagal".mysql_error());
		if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
		}
	} else {
		echo "<script>swal({
			title: 'Oops...', 
			text: 'Password lama salah!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
	}
	
	
	} 

	if(empty($lokasi_file) and !empty($passwordbaru1)){

	if ($data['password'] == md5($passwordlama)){
		$passwordbaruenkrip = md5($passwordbaru1);

		$edit=mysql_query("Update siswa set password='$passwordbaruenkrip',
								 nama_siswa='$_POST[nama_siswa]',
							     alamat='$_POST[alamat]',
							     tmp_lahir='$_POST[tmp_lahir]',
								 tgl_lahir='$thn$bln$tgl',
								 agama='$_POST[agama]',
								 status='$_POST[kolomstatus]',
								 tahun_angkatan='$_POST[tahun_angkatan]',
							     jenis_kelamin='$_POST[jenis_kelamin]',
								 telp='$_POST[telp]'
								 where nis='$_POST[nis]'")or die("gagal".mysql_error());
		if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
		}
	} else {
		echo "<script>swal({
			title: 'Oops...', 
			text: 'Password lama salah!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
	}
	
	} elseif(!empty($lokasi_file) and empty($passwordbaru1)){
	if(!in_array($tipe_file, $formatgambar)) {
	  echo "<script>swal({
			title: 'Oops...', 
			text: 'Format file harus berupa gambar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();

	}
	//cek apakah ukuran file diatas 500kb 
	if($ukuran_file > $maks_file) {
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Ukuran file terlalu besar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	}

		move_uploaded_file($lokasi_file,"$direktori");
		$edit=mysql_query("Update siswa set nama_siswa='$_POST[nama_siswa]',
							     alamat='$_POST[alamat]',
							     tmp_lahir='$_POST[tmp_lahir]',
								 tgl_lahir='$thn$bln$tgl',
								 agama='$_POST[agama]',
								 status='$_POST[kolomstatus]',
								 tahun_angkatan='$_POST[tahun_angkatan]',
							     jenis_kelamin='$_POST[jenis_kelamin]',
								 telp='$_POST[telp]',
								 foto='$nama_baru'
								 where nis='$_POST[nis]'")or die("gagal".mysql_error());
	
	if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
	} 

}

elseif($menu=="user" AND $aksi=="tambah"){
	$pass=md5($_POST['password1']);
	$lokasi_file=$_FILES['foto']['tmp_name'];
	$nama_foto=$_FILES['foto']['name'];
	$tipe_file=$_FILES['foto']['type'];
	$ukuran_file=$_FILES['foto']['size'];
	$nama_baru = preg_replace("/\s+/", "_", $_POST['username'].$nama_foto);
	$direktori = "foto/$nama_baru";
	$maks_file=500000; //500kb
	//cek apakah format file adalah format gambar
	$formatgambar = array("image/jpg", "image/jpeg","image/gif", "image/png");
	if(!in_array($tipe_file, $formatgambar)) {
	  echo "<script>swal({
			title: 'Oops...', 
			text: 'Format file harus berupa gambar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();

	}
	//cek apakah ukuran file diatas 500kb 
	if($ukuran_file > $maks_file) {
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Ukuran file terlalu besar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	}
	move_uploaded_file($lokasi_file,"$direktori");
	$tambah=mysql_query("insert into user(kode_user, username, password, nama_user,  telp, foto)	VALUES('$_POST[kode_user]', '$_POST[username]', '$pass', '$_POST[nama_user]',  '$_POST[telp]', '$nama_baru' )");
	if($tambah){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah tersimpan!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	} else {
		echo "<script>swal({
			title: 'Gagal!', 
			text: 'Username telah digunakan!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
	}
}

elseif($menu=="user" AND $aksi=="edit"){
	$query = "SELECT * FROM user WHERE username = '$_POST[username]'";
	$hasil = mysql_query($query);
	$data  = mysql_fetch_array($hasil);

	if(!empty($_POST['password1']) and !empty($_POST['password2']) and !empty($_POST['password3'])){
		$passwordlama  = $_POST['password1'];
		$passwordbaru1 = $_POST['password2'];
		$passwordbaru2 = $_POST['password3'];
	}

	

	$lokasi_file=$_FILES['edit_foto']['tmp_name'];
	$nama_foto=$_FILES['edit_foto']['name'];
	$tipe_file=$_FILES['edit_foto']['type'];
	$ukuran_file=$_FILES['edit_foto']['size'];
	$nama_baru = preg_replace("/\s+/", "_", $_POST['username'].$nama_foto);
	$direktori = "foto/$nama_baru";
	$maks_file=500000; //500kb
	//cek apakah format file adalah format gambar
	$formatgambar = array("image/jpg", "image/jpeg","image/gif", "image/png"); 

	if(empty($lokasi_file) and empty($passwordbaru1)){
	$edit=mysql_query("Update user set nama_user='$_POST[nama_user]',
								 telp='$_POST[telp]'
								 where username='$_POST[username]'")or die("gagal".mysql_error());
	if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
	} elseif(!empty($lokasi_file) and !empty($passwordbaru1)){
	if(!in_array($tipe_file, $formatgambar)) {
	  echo "<script>swal({
			title: 'Oops...', 
			text: 'Format file harus berupa gambar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();

	}
	//cek apakah ukuran file diatas 500kb 
	if($ukuran_file > $maks_file) {
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Ukuran file terlalu besar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	}
	if ($data['password'] == md5($passwordlama)){
		$passwordbaruenkrip = md5($passwordbaru1);

		move_uploaded_file($lokasi_file,"$direktori");
		$edit=mysql_query("Update user set password='$passwordbaruenkrip',
								 nama_user='$_POST[nama_user]',
								 telp='$_POST[telp]',
								 foto='$nama_baru'
								 where username='$_POST[username]'")or die("gagal".mysql_error());
		if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
		}
	} else {
		echo "<script>swal({
			title: 'Oops...', 
			text: 'Password lama salah!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
	}
	
	
	} 

	if(empty($lokasi_file) and !empty($passwordbaru1)){
	if ($data['password'] == md5($passwordlama)){
		$passwordbaruenkrip = md5($passwordbaru1);

		$edit=mysql_query("Update user set password='$passwordbaruenkrip',
								 nama_user='$_POST[nama_user]',
								 telp='$_POST[telp]'
								 where username='$_POST[username]'")or die("gagal".mysql_error());
		if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
		}
	} else {
		echo "<script>swal({
			title: 'Oops...', 
			text: 'Password lama salah!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
	}
	
	} elseif(!empty($lokasi_file) and empty($passwordbaru1)){
	if(!in_array($tipe_file, $formatgambar)) {
	  echo "<script>swal({
			title: 'Oops...', 
			text: 'Format file harus berupa gambar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();

	}
	//cek apakah ukuran file diatas 500kb 
	if($ukuran_file > $maks_file) {
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Ukuran file terlalu besar!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	}

		move_uploaded_file($lokasi_file,"$direktori");
		$edit=mysql_query("Update user set nama_user='$_POST[nama_user]',
								 telp='$_POST[telp]',
								 foto='$nama_baru'
								 where username='$_POST[username]'")or die("gagal".mysql_error());
	
	if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
	} 

}


elseif($menu=="pelajaran" AND $aksi=="tambah"){
	$tambah=mysql_query("insert into pelajaran(kode_pelajaran,
								  nama_pelajaran, keterangan)						
						   VALUES('$_POST[kode_pelajaran]',
						          '$_POST[nama_pelajaran]',
						          '$_POST[keterangan]')") or die("gagal".mysql_error());
	if($tambah){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah tersimpan!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
}
elseif($menu=="pelajaran" AND $aksi=="edit"){
	$edit=mysql_query("Update pelajaran set nama_pelajaran='$_POST[nama_pelajaran]', keterangan='$_POST[keterangan]'
								 where kode_pelajaran='$_POST[kode_pelajaran]'")or die("gagal".mysql_error());
	if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
}

elseif($menu=="kelas" AND $aksi=="tambah"){
	$cek=mysql_query("SELECT * FROM kelas where nama_kelas='$_POST[nama_kelas]' and tahun_ajar='$_POST[tahun_ajar]'");
	if(mysql_num_rows($cek)>=1){
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Nama Kelas $_POST[nama_kelas] dengan Tahun Ajaran yang sama sudah dibuat!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	} else {
		$guru=explode(" | ", $_POST['kode_guru']);
		$kodeguru=$guru['1'];
		$tambah=mysql_query("insert into kelas(kode_kelas, tahun_ajar, kelas,
									nama_kelas, kode_guru, status)						
						   VALUES('$_POST[kode_kelas]', '$_POST[tahun_ajar]', '$_POST[kelas]', '$_POST[nama_kelas]', '$kodeguru', '$_POST[pilihstatus]')") or die("gagal".mysql_error());
	if($tambah){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah tersimpan!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
	}
	
}
elseif($menu=="kelas" AND $aksi=="edit"){
	$guru=explode(" | ", $_POST['kode_guru']);
	$kodeguru=$guru['1'];
	$edit=mysql_query("Update kelas set  kelas='$_POST[kelas]', status='$_POST[kolomstatus]', kode_guru='$kodeguru'
								 where kode_kelas='$_POST[kode_kelas]'")or die("gagal".mysql_error());
	if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
}

elseif($menu=="kelassiswa" AND $aksi=="tambah"){
	$siswa=explode(" | ", $_POST['kode_siswa']);
	$kodesiswa=$siswa['1'];
	$tahunkelas=explode("-", $_POST['kode_kelas']);
	$kodekelas=$tahunkelas['0'];
	$tahunajar=$tahunkelas['1'];
	$cek=mysql_query("SELECT * FROM datakelas, kelas where  kelas.tahun_ajar='$tahunajar' AND datakelas.kode_kelas=kelas.kode_kelas AND kode_siswa='$kodesiswa'");
	if(mysql_num_rows($cek)>=1){
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Siswa telah ada di kelas dan tahun ajaran yang sama!', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	} else {
		
		$tambah=mysql_query("insert into datakelas(kode_datakelas, kode_kelas, kode_siswa, jurusan)						
						   VALUES('$_POST[kode_datakelas]', 
						   '$kodekelas',
						   '$kodesiswa', '$_POST[jurusan]')") or die("gagal".mysql_error());
	if($tambah){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah tersimpan!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
	}
	
}
elseif($menu=="kelassiswa" AND $aksi=="edit"){
	$edit=mysql_query("Update datakelas set jurusan='$_POST[jurusan]'
								 where kode_datakelas='$_POST[kode_datakelas]'")or die("gagal".mysql_error());
	if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}

}


elseif($menu=="nilai" AND $aksi=="tambah"){
	$guru=explode(" | ", $_POST['kode_guru']);
	$kodeguru=$guru['1'];
	$siswa=explode(" | ", $_POST['kode_siswa']);
	$kodesiswa=$siswa['1'];
	$cek=mysql_query("SELECT * FROM nilai where semester='$_POST[semester]' AND kode_pelajaran='$_POST[kode_pelajaran]' AND kode_siswa='$kodesiswa'");
	if(mysql_num_rows($cek)>=1){
	$query=mysql_query("SELECT nilai.semester, pelajaran.nama_pelajaran, siswa.nama_siswa, siswa.nis FROM nilai, siswa, pelajaran WHERE nilai.kode_siswa=siswa.kode_siswa AND nilai.kode_pelajaran=pelajaran.kode_pelajaran AND nilai.kode_pelajaran='$_POST[kode_pelajaran]' AND nilai.semester='$_POST[semester]' AND nilai.kode_siswa='$kodesiswa'");
	$data=mysql_fetch_array($query);
		 echo "<script>swal({
			title: 'Oops...', 
			text: 'Nilai Siswa $data[nama_siswa] | $data[nis]  sudah ada di Pelajaran $data[nama_pelajaran] | Semester $data[semester]', 
			type: 'error'
			}, 
			function(){ 
				window.history.back(); 
			}); </script>";
		exit();
	} else {
		$tambah=mysql_query("insert into nilai(kode_nilai, semester, kode_pelajaran, kode_guru, kode_kelas, kode_siswa, nilai_tugas, nilai_tugas2, nilai_tugas3, nilai_uts, nilai_uas, keterangan)						
						   VALUES('$_POST[kode_nilai]', 
						   '$_POST[semester]', '$_POST[kode_pelajaran]', '$kodeguru', '$_POST[kode_kelas]', '$kodesiswa', '$_POST[nilai_tugas]', '$_POST[nilai_tugas2]', '$_POST[nilai_tugas3]' , '$_POST[nilai_uts]', '$_POST[nilai_uas]',
						  '$_POST[keterangan]')") or die("gagal".mysql_error());
	if($tambah){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah tersimpan!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}
	
	}
}
elseif($menu=="nilai" AND $aksi=="edit"){
	$edit=mysql_query("Update nilai set nilai_tugas='$_POST[nilai_tugas]', nilai_tugas2='$_POST[nilai_tugas2]', nilai_tugas3='$_POST[nilai_tugas3]', nilai_uts='$_POST[nilai_uts]', nilai_uas='$_POST[nilai_uas]', 	keterangan='$_POST[keterangan]'
								 where kode_nilai='$_POST[kode_nilai]'")or die("gagal".mysql_error());
	if($edit){
		echo "<script>swal({
			title: 'Sukses!', 
			text: 'Data telah terganti!', 
			type: 'success'
			}, 
			function(){ 
				window.location.href='index.php?menu=$menu'; 
			}); </script>";
	}

}
?>
