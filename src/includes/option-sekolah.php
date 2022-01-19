<?php
	include '../setting/connection.php';
	spl_autoload_register(function ($class) {
		include '../setting/controller/' .$class . '.php';
	});

	$sekolahClass = new Sekolah();

	if($_SERVER['SERVER_NAME'] == 'setara.kemdikbud.go.id' OR $_SERVER['SERVER_NAME'] == 'setara.seamolec.org'){
		$program	= "Pendidikan Kesetaraan";
	}else{
		$program	= "";
	}

	if (isset($_REQUEST['q'])) {
		if (isset($_REQUEST['t'])) {
			$data = $sekolahClass->getListSekolahbyNamaByProgram($program, $_REQUEST['q'], $_REQUEST['t']);
		}else{
			$data = $sekolahClass->getListSekolahbyNamaByProgram($program, $_REQUEST['q']);
		}
	}else{
		$data = $sekolahClass->getListSekolahByProgram($program);
	}
	echo json_encode($data);
?>
