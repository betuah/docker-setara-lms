<?php
require_once './assets/js/lib/importExcel/Spout/Autoloader/autoload.php';

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

require("includes/header-top.php");
?>
<link rel="stylesheet" href="./assets/css/lib/daterangepicker/daterangepicker.css">
<script type="text/javascript" src="./assets/tinymce4/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
		tinymce.init({
			selector: '.myeditablediv',
			height : 100,
			menubar: false,
			auto_focus:true,


			// To avoid TinyMCE path conversion from base64 to blob objects.
			// https://www.tinymce.com/docs/configure/file-image-upload/#images_dataimg_filter
			images_dataimg_filter : function(img) {
				return img.hasAttribute('internal-blob');
			},
			setup : function(ed)
			{
				ed.on('init', function()
				{
					this.getDoc().body.style.fontSize = '16px';
					this.getDoc().body.style.fontFamily = 'Arial, "Helvetica Neue", Helvetica, sans-serif';
				});
			},
			plugins: [
				"advlist autolink link image lists charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
				"table contextmenu directionality emoticons paste textcolor responsivefilemanager code tiny_mce_wiris"
			],
			toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
			toolbar2: "| link unlink anchor | image media | forecolor backcolor | print preview | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry",
			image_advtab: true
		});
</script>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script> -->

<!-- Style for html code -->
<link type="text/css" rel="stylesheet" href="./assets/tinymce4/css/prism.css" />
<?php
require("includes/header-menu.php");

$kelasClass = new Kelas();
$mapelClass = new Mapel();
$modulClass = new Modul();
$quizClass  = new Quiz();
$soalClass	= new Soal();

$menuModul		= 4;
$infoQuiz	= $quizClass->getInfoQuiz($_GET['qz']);
$listSoal	= $soalClass->getListbyQuiz($infoQuiz['id_paket']);

// print_r($listSoal);
// $infoSoal	= $soalClass->getInfoSoal($)
$infoModul	= $modulClass->getInfoModul($_GET['md']);
$infoMapel	= $mapelClass->getInfoMapel($infoModul['id_mapel']);
$opsi=2;

if (isset($_POST['updateInfoQuiz'])) {
	$nama 			= $_POST['nama'];
	$durasi 		= $_POST['durasi'];
	$waktu 			= $_POST['waktu'];
	$publish 		= $_POST['publish'];
	$instruksi 		= $_POST['instruksi'];
	$jenis			= $_POST['jenis'];
	$random_soal	= $_POST['acak_soal'];
	$random_opsi	= $_POST['acak_opsi'];

	list($mulai, $selesai) = split(' - ', $waktu);

	$date 		= new DateTime($mulai);
	$mulai 		= $date->format('Y-m-d');
	$date 		= new DateTime($selesai);
	$selesai	= $date->format('Y-m-d');

	if($quizClass->updateQuiz($_GET['qz'], $nama, $durasi, $mulai, $selesai, $instruksi, $publish, $jenis, $random_soal, $random_opsi, $infoMapel['id_kelas'], $FuncProfile['sekolah'])){
		$infoQuiz	= $quizClass->getInfoQuiz($_GET['qz']);
		echo "<script>alert('Info Penilaian Berhasil Diperbarui');</script>";
	}else{
		echo "<script>alert('Info Penilaian Gagal Diperbarui');</script>";
	}
}

if (isset($_POST['importSoal'])) {
		// ini_set('max_execution_time', 1800);
		// echo "ini looooh ".$inputFileName; application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
		// echo "Tipe file-nya adalah : ".$_FILES['sss']['type']." <br />";
	if ($_FILES['sss']['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {

		$inputFileName	= $_FILES['sss']['tmp_name'];
		$reader 		= ReaderFactory::create(Type::XLSX); // for XLSX files
		$reader->open($inputFileName);

		$i = 0;

		$start = microtime(true);

		foreach ($reader->getSheetIterator() as $sheet) {
			foreach ($sheet->getRowIterator() as $row) {
				if(!empty($row[0]) && $i > 0){
					$jawaban = '';
					$uwala = count($row);
					for($j=2; $j<$uwala; $j++){
						$jawaban[] = $row[$j];
					}

					$hasil = $soalClass->addSoal($infoMapel['id_kelas'], $row[1],$jawaban,0,$infoQuiz['id_paket'], $_SESSION['lms_id']);
				}
				$i++;
			}
			$time_elapsed_secs = microtime(true) - $start;
		}


		echo "<script>
				swal({
					title: '$hasil[hasil]',
					text: '$hasil[pesan]',
					type: '$hasil[icon]'
				}, function() {
					window.location = '?act=update&md=$_GET[md]&qz=$_GET[qz]';
				});
			</script>";
		// print_r($jawaban);
		// echo "<br/>";
		// echo "<br/><br/><br/>TOTAL TIME : " . $time_elapsed_secs;

		$reader->close();
	}else{
		echo "<script>swal('Maaf!','Silahkan unduh format untuk Impor Soal terlebih dahulu!','error');</script>";
	}
}

$date		= new DateTime($infoQuiz['start_date']);
$start_date = $date->format('d M Y');
$date		= new DateTime($infoQuiz['end_date']);
$end_date 	= $date->format('d M Y');

$hakKelas		= $kelasClass->getKeanggotaan($infoMapel['id_kelas'], $_SESSION['lms_id']);
if(!$hakKelas['status']){
	echo "<script>
			swal({
				title: 'Maaf!',
				text: 'Anda tidak terdaftar pada Kelas / Kelas tidak tsb tidak ada.',
				type: 'error'
			}, function() {
				window.location = 'index';
			});
		</script>";
		die();
}

$logIDKelas = $infoMapel['id_kelas'];

?>
<script type="text/javascript">document.title = "Kelola Penilaian <?=$infoQuiz['nama']?> - seTARA daring";</script>
	<div class="modal fade"
		id="updateMapel"
		tabindex="-1"
		role="dialog"
		aria-labelledby="updateMapelLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form method="POST">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="updateMapelLabel">Pengaturan Mata Pelajaran</h4>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label for="namaMapelupdate" class="col-md-3 form-control-label">Mata Pelajaran</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="namaMapelupdate" id="namaMapelupdate" placeholder="Nama Mata Pelajaran" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-rounded btn-danger pull-left" onclick="" name="hapusKelas"><i class="font-icon-trash"></i> Hapus Mata Pelajaran</button>
					<button type="submit" class="btn btn-rounded btn-primary" name="updateMapel" value="send" >Simpan</button>
					<button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
				</div>
				</form>
			</div>
		</div>
	</div><!--.modal-->

	<div class="modal fade bd-example-modal-lg"
		id="addModul"
		tabindex="-1"
		role="dialog"
		aria-labelledby="addModulLabel"
		aria-hidden="true"
		data-backdrop="static"
		data-keyboard="false">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<form id="form_tambah" method="POST">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="addModulLabel">Tambah Penilaian</h4>
				</div>
				<div class="modal-body">
					<fieldset class="form-group">
						<label class="form-label semibold" for="exampleInput">Soal</label>
						<textarea class ="myeditablediv" id="soal" name="soal" ></textarea>
					</fieldset>
					<label class="form-label semibold" for="exampleInput">Jawaban</label>
					<hr />
					<fieldset class="form-group">
						<label class="form-label " for="exampleInput">Pilhan 1</label>
						<textarea class ="myeditablediv" id="jawab1" name="jawaban[]" ></textarea>
						Atur Jawaban Benar <input type="radio" name="benar" value="0" checked="checked">
					</fieldset>
					<fieldset class="form-group">
						<label class="form-label " for="exampleInput">Pilihan 2</label>
						<textarea class="myeditablediv" id="jawab2" name="jawaban[]" ></textarea>
						Atur Jawaban Benar <input type="radio" name="benar" value="1">
					</fieldset>
					<div class ="opsitambahan">

					</div>
					<a style="align:right;color:#009dff;" id="tambahopsi" onclick="tambahOpsi();">+ Tambah Pilihan</a>
				</div>
				<div class="modal-footer">
					<button type="submit" name="addQuiz" value="send" class="btn btn-rounded btn-primary">Simpan</button>
					<button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
				</div>
				</form>
			</div>
		</div>
	</div><!--.modal-->

	<div class="modal fade bd-example-modal-lg"
		id="importSoal"
		tabindex="-1"
		role="dialog"
		aria-labelledby="importSoalLabel"
		aria-hidden="true"
		data-backdrop="static"
		data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="impor_soal" method="POST" action="" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="importSoalLabel">Impor Soal</h4>
				</div>
				<div class="modal-body">
					<fieldset class="form-group">
						<label class="form-label semibold"  for="exampleInput">Berkas</label>
						<input name="sss" type="file" accept=".xlsx" class="form-control" required>
						<small class="text-muted">Format untuk Impor Soal bisa diunduh <a target="_blank" href="Import.xlsx"><u>disini</u></a>.</small>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button type="submit" name="importSoal" value="send" class="btn btn-rounded btn-primary">Simpan</button>
					<button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
				</div>
				</form>
			</div>
		</div>
	</div><!--.modal-->

	<div class="modal fade bd-example-modal-lg"
		id="editInfoQuiz"
		tabindex="-1"
		role="dialog"
		aria-labelledby="editInfoQuizLabel"
		aria-hidden="true"
		data-backdrop="static"
		data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="edit_quiz" method="POST" action="">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="addModulLabel">Edit Info Penilaian</h4>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-md-3 form-label semibold" name="durasi" for="exampleInput">Terbitkan</label>
						<div class="col-md-4">
							<div class="radio">:&nbsp;
								<input type="radio" name="publish" id="radio-1" value="1" <?php if ($infoQuiz['status'] == "1") {echo "checked";} ?>>
								<label for="radio-1">Ya </label>
								&nbsp;
								<input type="radio" name="publish" id="radio-2" value="0" <?php if ($infoQuiz['status'] == "0") {echo "checked";} ?>>
								<label for="radio-2">Tidak</label>
							</div>
						</div>
					</div>
					<fieldset class="form-group">
						<label class="form-label semibold"  for="exampleInput">Nama Penilaian</label>
						<input type="text" class="form-control" name="nama" placeholder="cth: Penilaian Modul 1 Materi 1" value="<?=$infoQuiz['nama']?>" required>
					</fieldset>
					<fieldset class="form-group">
						<label class="form-label semibold" name="durasi" for="exampleInput">Durasi</label>
						<input type="number" class="form-control" name="durasi" id="exampleInput" placeholder="cth. 60" value="<?=$infoQuiz['durasi']?>" min="0" maxlength="3" max="999" required>
						<small class="text-muted">Lama Pengerjaan dalam satuan menit.</small>
					</fieldset>
					<fieldset class="form-group">
						<label class="form-label semibold" name="jenis" for="exampleInput">Bentuk Pengerjaan</label>
						<select class="select2" name="jenis">
							<option value="0" <?=($infoQuiz['jenis'] == "0"? 'selected':'')?>>Ulangan</option>
							<option value="1" <?=($infoQuiz['jenis'] == "1"? 'selected':'')?>>Ujian</option>
						</select>
					</fieldset>
					<fieldset class="form-group">
						<label class="form-label semibold"  for="exampleInput">Waktu Pengerjaan</label>
						<input type="text" class="form-control tanggal" name="waktu" id="exampleInput" value="<?=$start_date.' - '.$end_date;?>" placeholder="yyyy-mm-dd" required>
					</fieldset>
					<fieldset class="form-group">
						<label class="form-label semibold" for="exampleInput">Petunjuk Pengerjaan</label>
						<textarea class="form-control" name="instruksi" id="exampleInput" data-autosize><?=@$infoQuiz['instruksi']?></textarea>
					</fieldset>
					<div class="form-group row">
						<label class="col-md-3 form-label semibold" name="acak_opsi" for="exampleInput">Acak Soal</label>
						<div class="col-md-4">
							<div class="radio">:&nbsp;
								<input type="radio" name="acak_soal" id="radio-3" value="1" <?php if ($infoQuiz['random_soal'] == "1") {echo "checked";} ?>>
								<label for="radio-3">Ya </label>
								&nbsp;
								<input type="radio" name="acak_soal" id="radio-4" value="0" <?php if ($infoQuiz['random_soal'] == "0") {echo "checked";} ?>>
								<label for="radio-4">Tidak</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 form-label semibold" name="acak_opsi" for="exampleInput">Acak Opsi Soal</label>
						<div class="col-md-4">
							<div class="radio">:&nbsp;
								<input type="radio" name="acak_opsi" id="radio-5" value="1" <?php if ($infoQuiz['random_opsi'] == "1") {echo "checked";} ?>>
								<label for="radio-5">Ya </label>
								&nbsp;
								<input type="radio" name="acak_opsi" id="radio-6" value="0" <?php if ($infoQuiz['random_opsi'] == "0") {echo "checked";} ?>>
								<label for="radio-6">Tidak</label>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="updateInfoQuiz" value="send" class="btn btn-rounded btn-primary">Simpan</button>
					<button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
				</div>
				</form>
			</div>
		</div>
	</div><!--.modal-->

	<div class="page-content">
		<div class="profile-header-photo">
			<div class="profile-header-photo-in">
				<div class="tbl-cell">
					<div class="info-block">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-12">
									<div class="tbl info-tbl">
										<div class="tbl-row">
											<div class="tbl-cell">
												<p><i class="font-icon font-icon-user"></i>&nbsp;&nbsp;<small><?=($hakKelas['posisi'] == 'Guru Mata Pelajaran'?'Tutor (Pengampu Mata Pelajaran)':($hakKelas['posisi'] == 'Tutor'?'Tutor (Pendamping)':$hakKelas['posisi']))?></small></p>
												<br><br><br><br><br>
												<p class="title"><?=$_SESSION['lms_name']?></p>
												<p class="title"><u>Penilaian:</u> <?=$infoModul['nama']?></p>
												<p><u>Mata Pelajaran:</u> <?=$infoMapel['nama']?></p>
											</div>
											<div class="tbl-cell tbl-cell-stat">
												<div class="inline-block">
													<!-- <p class="title"><?//=$listSoal['jmlSoal']?></p> -->
													<!-- <p>Soal</p> -->
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- <button type="button" class="change-cover" onclick="update()">
				<i class="font-icon font-icon-pencil"></i>
				Pengaturan Mata Pelajaran
			</button> -->
		</div><!--.profile-header-photo-->

		<div class="container-fluid">
			<div class="row">
				<div class="col-xl-3 col-lg-4">
					<aside id="menu-fixed" class="profile-side" style="margin: 0 0 20px">
					<form method="POST" action="">
                        <?php 
                            if($infoQuiz['jenis'] == 1){
                        ?>
                            <section class="box-typical">
                                <header class="box-typical-header-sm bordered">
                                    Kode Ujian
                                <?php
                                //---> memeriksa Hak Akses dalam Kelas || Hanya pemilik Kelas yang dapat Kunci kelas/Buka kelas
                                if (($hakKelas['status'] == 1) || ($infoMapel['creator'] == $_SESSION['lms_id'])) {
                                    if (empty($infoQuiz['kode'])) {
                                ?>
                                        <div class="btn-group" style='float: right;'>
                                            <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Aksi
                                            </button>
                                            <div class="dropdown-menu" style="margin-left: -100px">
                                                <a class="dropdown-item" onclick="generateKodeUjian('<?=$infoQuiz['_id']?>')"><i class="font-icon font-icon-lock"></i>Generate Kode Ujian</a>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                                </header>
                                <div class="box-typical-inner">
                                    <p style="font-size: 2em; font-weight:bold; text-align: center;">
                                        <?php
                                        if (!empty($infoQuiz['kode'])) {
                                            echo '<u>'.$infoQuiz['kode'].'</u>';
                                        }else {
                                            echo '<span class="label label-pill label-danger">Belum Tersedia</span>';
                                        }
                                        ?>
                                    </p>
                                </div>
                            </section>
                        <?php
                            }
                        ?>
						<section class="box-typical">
							<header class="box-typical-header-sm bordered">
								<div class="btn-group" style="float: right; margin-bottom: 12px;">
									<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Aksi
									</button>
									<div class="dropdown-menu dropdown-menu-right">
									<?php
									if (($hakKelas['status'] == 1) || ($infoMapel['creator'] == $_SESSION['lms_id'])) {
									?>
										<a href="#editInfoQuiz" class="dropdown-item" title="Info Penilaian" data-toggle="modal" data-placement="right" data-trigger="hover" data-content="Edit info Penilaian"><i class="font-icon font-icon-pencil"></i> Edit Info</a>
									<?php
									}
									?>
										<a href="create-quiz?modul=<?=$infoModul['_id']?>" class="dropdown-item" title="Menu Penilaian" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Kembali ke menu Penilaian"><i class="font-icon font-icon-answer"></i> Menu Penilaian</a>
									</div>
								</div>
								<input type="text" class="form-control" name="nama" value="<?=$infoQuiz['nama']?>" readonly>
							</header>
							<div class="box-typical-inner" id="opsitambahan">
								<fieldset class="form-group">
									<label class="form-label semibold" name="durasi" for="exampleInput">Durasi</label>
									<input type="number" class="form-control" name="durasi" id="exampleInput" placeholder="0" value="<?=$infoQuiz['durasi']?>" maxlength="3" readonly>
									<small class="text-muted">Lama Pengerjaan dalam satuan menit.</small>
								</fieldset>
								<fieldset class="form-group">
									<label class="form-label semibold"  for="exampleInput">Tanggal Mulai</label>
									<input type="text" class="form-control" name="mulai" id="exampleInput" value="<?=$start_date?>" placeholder="" readonly>
								</fieldset>
								<fieldset class="form-group">
									<label class="form-label semibold" for="exampleInput">Tanggal Selesai</label>
									<input type="text" class="form-control" name="selesai" id="exampleInput" value="<?=$end_date?>" placeholder="mm/dd/yyyy" readonly>
								</fieldset>
								<hr />
								<div class="form-group row">
									<label class="col-md-5 form-control-label" name="durasi" for="exampleInput">Terbitkan</label>
									<div class="col-md-6">:&nbsp;
										<?php if ($infoQuiz['status'] == "1") {?>
											<span><i class="fa fa-check-circle" aria-hidden="true" style="color: #3ac9d6;"></i> Ya</span>
											<span><i class="fa fa-circle-o" aria-hidden="true"></i> Tidak</span>
										<?php }else{ ?>
											<span><i class="fa fa-circle-o" aria-hidden="true"></i> Ya</span>
											<span><i class="fa fa-check-circle" aria-hidden="true" style="color: #3ac9d6;"></i> Tidak</span>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-5 form-control-label" name="durasi" for="exampleInput">Acak Soal</label>
									<div class="col-md-6">:&nbsp;
										<?php if ($infoQuiz['random_soal'] == "1") {?>
											<span><i class="fa fa-check-circle" aria-hidden="true" style="color: #3ac9d6;"></i> Ya</span>
											<span><i class="fa fa-circle-o" aria-hidden="true"></i> Tidak</span>
										<?php }else{ ?>
											<span><i class="fa fa-circle-o" aria-hidden="true"></i> Ya</span>
											<span><i class="fa fa-check-circle" aria-hidden="true" style="color: #3ac9d6;"></i> Tidak</span>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-md-5 form-control-label" name="durasi" for="exampleInput">Acak Pilihan</label>
									<div class="col-md-6">:&nbsp;
										<?php if ($infoQuiz['random_opsi'] == "1") {?>
											<span><i class="fa fa-check-circle" aria-hidden="true" style="color: #3ac9d6;"></i> Ya</span>
											<span><i class="fa fa-circle-o" aria-hidden="true"></i> Tidak</span>
										<?php }else{ ?>
											<span><i class="fa fa-circle-o" aria-hidden="true"></i> Ya</span>
											<span><i class="fa fa-check-circle" aria-hidden="true" style="color: #3ac9d6;"></i> Tidak</span>
										<?php } ?>
									</div>
								</div>
								<!-- <div class ="opsitambahan"> -->
								<button style="display: none;" class="btn " name="updateInfoQuiz">Simpan</button>
							</form>
					<!-- </div> -->
					<!-- <a style="align:right;color:#009dff;" onclick="tambahOpsi();">+ Tambah Pilihan</a> -->
							</div>
						</section>

					</aside><!--.profile-side-->
				</div>

				<div class="col-xl-9 col-lg-8">
					<section class="widget widget-activity">
						<header class="widget-header">
							Paket Soal - <?=$infoQuiz['nama']?>
							<?php
							if (($hakKelas['status'] == 1) || ($infoMapel['creator'] == $_SESSION['lms_id'])) {
							?>
							<div class="btn-group" style="float:right;">
								<?php  
									if($infoQuiz['status'] == "1"){
								?>
									<a href="javascript:void(0)" onclick="peringatan_edit()" class="btn btn-sm btn-inline btn-warning" title="Impor Soal" data-toggle="modal" data-placement="left" data-trigger="hover" data-content="Tombol untuk meng-impor Soal."><i class="fa fa-upload"></i> Import Soal</a>
									<a href="javascript:void(0)" onclick="peringatan_edit()" class="btn btn-sm btn-inline" title="Tambah" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menambahkan Soal baru.">+ Tambah Soal</a>
								<?php
									}else{
								?>
									<a href="#importSoal" class="btn btn-sm btn-inline btn-warning" title="Impor Soal" data-toggle="modal" data-placement="left" data-trigger="hover" data-content="Tombol untuk meng-impor Soal."><i class="fa fa-upload"></i> Import Soal</a>
									<a href="edit-quiz?md=<?=$_GET['md']?>&&qz=<?=$_GET['qz']?>" class="btn btn-sm btn-inline" title="Tambah" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menambahkan Soal baru.">+ Tambah Soal</a>
								<?php

									}
								?>
									

							</div>
							<?php
							}
							?>

						</header>
						<div>
							<div class="card-block" id="accordion">
								<table id="tabel-soal" class="display table table-striped" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th class="text-center" style="width: 10%;">No</th>
										<th class="text-center">Butir Soal</th>
										<th class="text-center" style="width: 20%;">Aksi</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
								</table>
							</div>
						</div>
					</section><!--.widget-tasks-->
				</div>
			</div><!--.row-->
		</div><!--.container-fluid-->
	</div><!--.page-content-->

<?php
	require('includes/footer-top.php');
?>
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="assets/js/lib/daterangepicker/daterangepicker.js"></script>

    <script src="assets/js/lib/datatables-net/datatables.min.js"></script>
    <script src="assets/js/lib/datatables-net/buttons-1.2.0/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/lib/datatables-net/buttons-1.2.0/js/buttons.flash.min.js"></script>
    <script src="assets/js/lib/datatables-net/buttons-1.2.0/js/buttons.print.min.js"></script>
    <script>

        var jumlah_soal = <?=$listSoal->count()?>;

        if(jumlah_soal > 5){
            lengthList = [ [5, jumlah_soal], [5, "ALL"] ];
        }else{
            lengthList = [jumlah_soal];
        }

        table = $('#tabel-soal').DataTable({
            "processing"    : true,
            "bServerSide"   : true,
            "sAjaxSource"   : "url-API/Kelas/Modul/Quiz?action=showDaftarSoal&modul=<?=$_GET['md']?>&quiz=<?=$_GET['qz']?>&paket=<?=$infoQuiz['id_paket']?>&hakKelas=<?=$hakKelas['status']?>&status=<?=$infoQuiz['status']?>",
            "deferRender"   : true,
            "aoColumns"     : [
                                { "mDataProp": "no_soal" },
                                { "mDataProp": "view_soal"},
                                { "mDataProp": "menu_soal"}
                                ],
            "searchCols"    : [null, null, null ],
            "order"         : [[1, 'desc']],
            "language"      : {
                                    "infoFiltered"  : "",
                                    "processing"	: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Memuat...</span>'
                                },
            "columnDefs": [
                {"className": "text-center font-weight-bold", "targets": [0,2]}
            ],
            "dom"			 : '<"row"<"col-sm-4"l><"col-sm-4"Br><"col-sm-4"f>>t<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "buttons"		 : ["excel", "pdf", "print"],
            "scrollX"        : true,
            "scrollCollapse" : true,
            "lengthMenu"     : lengthList
        });

		var i = 2;
		var j = 1;

		function tambahOpsi(){
			i = i+1;
			j = j+1;
			// js.src = "./assets/tinymce4/js/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image";
			// js.src = "./assets/tinymce4/js/tinymce/tinymce.min.js";

			$(".opsitambahan").append("<fieldset class='form-group' id='pilihan "+i+"'><label class='form-label' id="+i+" for='exampleInput'>Pilihan "+i+"</label><textarea class='myeditablediv' name='jawaban[]' ></textarea>Atur Jawaban Benar <input type='radio' name='benar' value='"+j+"'><a onclick='hapuspilihan("+i+")' name=''>Hapus</a></fieldset>");

			tinymce.init({
	    		selector: '.myeditablediv',
	    		height : 100,
	        	menubar: false,
	        	auto_focus:true,
    	        // To avoid TinyMCE path conversion from base64 to blob objects.
    	        // https://www.tinymce.com/docs/configure/file-image-upload/#images_dataimg_filter
    	        images_dataimg_filter : function(img) {
    	            return img.hasAttribute('internal-blob');
    	        },
    	        setup : function(ed)
    	        {
    	            ed.on('init', function()
    	            {
    	                this.getDoc().body.style.fontSize = '16px';
    	                this.getDoc().body.style.fontFamily = 'Arial, "Helvetica Neue", Helvetica, sans-serif';
    	            });
    	        },
    	        plugins: [
    	            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
    	            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
    	            "table contextmenu directionality emoticons paste textcolor responsivefilemanager code tiny_mce_wiris"
    	        ],
    	        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
    	        toolbar2: "| link unlink anchor | image media | forecolor backcolor | print preview | tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry",
    	        image_advtab: true
	  		});
		}

		$(document).ready(function() {
			$('.note-statusbar').hide();
		});

		function clearText(elementID){
			$(elementID).html("");
		}

		function hapuspilihan(a){
			var ab = '#pilihan '+a;
			alert (ab);
			$(ab).hide();
		}
	</script>

    <script type="text/javascript" src="./assets/tinymce4/js/wirislib.js"></script>
	<script type="text/javascript" src="./assets/tinymce4/js/prism.js"></script>
	<script src="assets/js/lib/autosize/autosize.min.js"></script>

	<script>
		$(function() {
			autosize($('textarea[data-autosize]'));
		});

		function clearText(elementID){
			$(elementID).html("");
		}

		function add(){
      		$('#addModul').trigger("reset");
      		$('#addModul').modal('show');
			$('#addModulLabel').text(
      		   $('#addModulLabel').text().replace('Edit Modul', 'Tambah Modul')
      		).show();
      	};

		function update(){
      		$('#updateMapel').trigger("reset");
      		$('#updateMapel').modal("show");
      		$('#updateMapelLabel').text(
      		   $('#updateMapelLabel').text().replace('Tambah Modul', 'Pengaturan Mata Pelajaran')
      		).show();
			$('#namaMapelupdate').val("<?=$infoMapel['nama']?>");
      	}

		function edit(ID){
      		$('#addModul').trigger("reset").show();
      		$('#addModulLabel').text(
      		   $('#addModulLabel').text().replace('Tambah Penilaian', 'Edit Soal')
      		).show();
			$('#addModul').modal('show');
      		$.ajax({
      			type: 'POST',
      			url: 'url-API/Kelas/Modul/Quiz/Soal/',
      			data: {"action": "show", "ID": ID},
      			success: function(res) {
      				if(res.data._id.$id){
      					$('#addModul').modal('show');
      					$('#idmodul').val(ID);
      					$('#soal').val(res.data.text);
      					alert(data);
      				}else {
      					swal("Gagal!", "Data tidak ditemukan!", "error");
      				}
      			},
      			error: function () {
      				swal("Gagal!", "Gagal mencari data!", "error");
      			}
      		});
      	}

		function remove(ID){
      		swal({
      		  title: "Apakah anda yakin?",
      		  text: "Data yang sudah dihapus tidak dapat dikembalikan!",
      		  type: "warning",
      		  showCancelButton: true,
			  	confirmButtonText: "Setuju!",
      			confirmButtonClass: "btn-danger",
      		  closeOnConfirm: false,
      		  showLoaderOnConfirm: true
      		}, function () {
      			$.ajax({
      				type: 'POST',
      				url: 'url-API/Kelas/Modul/Quiz/Soal/',
      				data: {"action": "remv", "ID": ID, "user": "<?=$_SESSION['lms_id']?>", "kelas":"<?=$infoMapel['id_kelas']?>"},
      				success: function(res) {
						swal({
				            title: res.response,
				            text: res.message,
				            type: res.icon
				        }, function() {
				            location.reload();
				        });
      				},
      				error: function () {
      					swal("Gagal!", "Data tidak terhapus!", "error");
      				}
      			});
      		});
      	}

		function peringatan_edit(){
      		swal({
      		  title: "Perhatian!",
      		  text: "Perubahan hanya bisa dilakukan jika penilaian belum DITERBITKAN!",
      		  type: "warning"
      		});
      	}

        function generateKodeUjian(ID){
            $.ajax({
                type: 'POST',
                url: 'url-API/Kelas/Modul/Quiz/',
                data: {"action": "generateKode", "ID": ID},
                success: function(res) {
                    if(res.status == 'Success'){
                        swal({
                            title: 'Berhasil!',
                            text: res.message,
                            type: 'success'
                        }, function() {
                            location.reload();
                        });
                    }else {
                        swal('Maaf!', 'Kode Ujian gagal Digenerate', 'error');
                    }
                },
                error: function () {
                    swal("Maaf!", "Error by System!", "error");
                }
            });
      	}

		$(document).ready(function() {
			$(".fancybox").fancybox({
				padding: 0,
				openEffect	: 'none',
				closeEffect	: 'none'
			});

			$('.tanggal').daterangepicker({
				autoUpdateInput: true,
				showDropdowns: true,
				autoApply: true,
				minDate: "<?=date('Y')-1?>",
				maxDate: "<?=date('Y')+10;?>-12-31",
				locale: {
					format: 'DD MMM YYYY'
				}
			});
		});
	</script>
	<script>
		$("#ohyeah").click(function(){
			$.ajax({
  				type: 'POST',
  				url: 'url-API/Siswa/index.php',
  				data: {"action": "update", "text": "t�i"},
  				success: function(res) {
	  				alert(res.text1);
	  				alert(res.text2);
	  				alert(res.text3);
  				},
  				error: function () {

  				}
  			});
		})
	</script>
<script src="assets/js/app.js"></script>
<script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML">
</script>
<?php
	require('includes/footer-bottom.php');
?>
