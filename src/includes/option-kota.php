<?php
	include '../setting/connection.php';
// spl_autoload_register(function ($class) {
//   include '../setting/controller/' .$class . '.php';

  include '../setting/controller/Provkot.php';
// });

	$provkotClass = new Provkot();

    $id	= $_POST['id'];
?>
<script type="text/javascript">//alert('<?=$id?>')</script>
<option value="">Pilih Kabupaten/Kota</option>
<?php
	// $listKota = $provkotClass->getListProv();
	$listKota = $provkotClass->getListKotabyProv((int)$id);
	foreach ($listKota as $dataKota){
		// var_dump($dataKota);
		// echo "<option value='$dataKota[id_provinsi]'>$dataKota[nama_provinsi]</option>";
		echo "<option value='$dataKota[id_kab_kot]'>$dataKota[nama_kab_kot]</option>";
	}
?>
