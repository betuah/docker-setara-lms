<?php
require("includes/header-top.php");
?>
<!-- Style for html code -->
<link rel="stylesheet" href="./assets/css/separate/pages/others.min.css">
<link rel="stylesheet" href="./assets/css/lib/daterangepicker/daterangepicker.css">
<?php
require("includes/header-menu.php");

$mapelClass = new Mapel();
$modulClass = new Modul();
$quizClass  = new Quiz();
$soalClass  = new Soal();
$kelasClass = new Kelas();

$menuModul	= 4;
$infoModul	= $modulClass->getInfoModul($_GET['modul']);
$infoMapel	= $mapelClass->getInfoMapel($infoModul['id_mapel']);
$infoKelas  = $kelasClass->getInfoKelas($infoMapel['id_kelas']);
$hakKelas	= $kelasClass->getKeanggotaan($infoMapel['id_kelas'], $_SESSION['lms_id']);

if ($_SESSION['lms_status'] == 'superadmin' || $_SESSION['lms_status'] == 'admin' || $_SESSION['lms_status'] == 'pengawas' || $_SESSION['lms_status'] == 'kepsek' ) {
	# code...
	$hakKelas['status'] = 3;
	$hakKelas['posisi']	= 'Super Administrator';
}else{
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
}

if($_SESSION['lms_status'] == 'siswa'){
	$listQuiz	= $quizClass->getListActivebyModul($_GET['modul']);
}else{
	$listQuiz	= $quizClass->getListbyModul($_GET['modul']);
}


if(isset($_POST['addQuiz'])){
	$nama = mysql_escape_string($_POST['namakuis']);

	if (!empty($_POST['idmodul'])) {
		$rest = $quizClass->setModul($nama, $_GET['modul'], $_POST['idmodul']);
	}else{
		list($mulai, $selesai) = split(' - ', $_POST['waktu']);

		$date 		= new DateTime($mulai);
		$mulai 		= $date->format('Y-m-d');
		$date 		= new DateTime($selesai);
		$selesai	= $date->format('Y-m-d');

		$rest = $quizClass->addQuiz($nama, $infoMapel['id_kelas'], $_GET['modul'], $_POST['durasi'], $mulai, $selesai, $_POST['instruksi'], $_SESSION['lms_id'], $FuncProfile['sekolah'], $_POST['jenis']);
	}

	if ($rest['status'] == "Sukses") {
		echo "<script>alert('".$rest['status']."'); document.location='quiz-action?act=update&md=".$_GET['modul']."&qz=".$rest['idQuiz']."'</script>";
	}
}
$logIDKelas = $infoMapel['id_kelas'];

?>
<script type="text/javascript">document.title = "Daftar Penilaian Hasil Belajar Kegiatan Pembelajaran <?=$infoModul['nama']?> - seTARA daring";</script>
<link rel="stylesheet" href="assets/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="assets/css/separate/vendor/datatables-net.min.css">
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

	<div class="modal fade"
		 id="addModul"
		 role="dialog"
		 aria-labelledby="addModulLabel"
		 aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form method="POST">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="addModulLabel">Form Pembuatan Kegiatan Penilaian</h4>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label for="namamodul" class="col-md-3 form-control-label">Nama Penilaian Hasil Belajar</label>
						<input type="hidden" name="idmodul" id="idmodul" class="" maxlength="11" />
						<div class="col-md-9">
							<input type="text" class="form-control" name="namakuis" id="namamodul" placeholder="cth: Penilaian Modul 1 Materi 1" title="Nama Penilaian" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-content="Silahkan isikan Nama Penilaian yang akan dibuat!" required />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 form-control-label" name="durasi" for="exampleInput">Durasi</label>
						<div class="col-md-9">
								<input type="number" class="form-control" name="durasi" id="exampleInput" placeholder="0" min="0" maxlength="3" required>
								<small class="text-muted">Lama Pengerjaan dalam satuan menit.</small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 form-control-label" name="durasi" for="exampleInput">Bentuk Pengerjaan</label>
						<div class="col-md-9">
								<select class="select2" name="jenis">
									<option value="0" selected>Ulangan</option>
									<option value="1">Ujian</option>
								</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 form-control-label"  for="exampleInput">Waktu Pengerjaan</label>
						<div class="col-md-9">
						<input type="text" class="form-control tanggal" name="waktu" id="exampleInput" placeholder="yyyy-mm-dd" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-3 form-control-label" name="instruksi" for="exampleInput">Petunjuk Pengerjaan</label>
						<div class="col-md-9">
								<textarea class="form-control" row='3' name="instruksi" data-autosize></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="addQuiz" value="send" class="btn btn-rounded btn-primary">Simpan</button>
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
													<!-- <p class="title"><?//=$infoMapel['modul']?></p> -->
													<!-- <p>Quiz</p> -->
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
					<?php
						require("includes/modul-menu.php");
					?>
				</div>

				<div class="col-xl-9 col-lg-8">
					<section id="daftar-hasil-penilaian" class="widget widget-tasks card-default" style="display: none;">
						<header class="card-header">
							Daftar Hasil Penilaian
							<div class="btn-group" style="float: right;">
                                <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Kembali
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" onclick="lihat_daftar_penilaian('daftar-hasil-penilaian','daftar-penilaian')" title="Tugas" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk kembali ke daftar penilaian."><i class="font-icon font-icon-burger"></i> Kembali ke daftar penilaian</a>
                                </div>
                            </div>
						</header>
						<div class="widget-tasks-item">
							<table id="tabel-penilaian" class="display table table-striped" cellspacing="0" width="100%">
								<thead>
									<tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Warga Belajar</th>
                                        <th class="text-center">Nilai</th>
                                        <th class="text-center">Waktu Kumpul</th>
										<th class="text-center tb-lg" style="width: 50px;">Foto</th>
										<th class="text-center">Hasil Penilaian</th>
										<th class="text-center" style="width: 50px;">Aksi</th>
									</tr>
								</thead>
                                <tbody>
                                </tbody>
							</table>
						</div>
					</section><!--.widget-tasks-->

					<section id="daftar-penilaian" class="card card-inversed">
						<header class="card-header">
							Daftar Penilaian
							<span class="label label-pill label-primary"><?=$listQuiz->count();?></span>
							<?php
								// if($infoModul['creator'] == $_SESSION['lms_id']){
								if($hakKelas['status'] == 1 || $infoMapel['creator'] == $_SESSION['lms_id']){
							?>
							<div class="btn-group" style="float: right;">
								<button type="button" onclick="add()" title="Tambah" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menambahkan Quiz baru." class="btn btn-sm btn-rounded">+ Tambah Penilaian</button>
							</div>
							<?php
								}
							?>
						</header>

						<div class="card-block" id="accordion">
							<?php
					$no	= 1;
					// print_r($listQuiz);
					if ($listQuiz->count() > 0) {
						foreach ($listQuiz as $materi) {
							$submittedQuiz 	= $quizClass->isSumbmitted((string)$_SESSION['lms_id'], (string)$materi['_id']);
							$bentukQuiz		= $materi['jenis'];
							?>
								<article class="box-typical profile-post panel">
									<div class="profile-post-header">
										<div class="user-card-row">
											<div class="tbl-row">
												<div class="tbl-cell tbl-cell-photo">
													<a href="#demo<?=$no?>" data-toggle="collapse" data-parent="#accordion">
														<?php
															if($submittedQuiz){
														?>
															<img src="assets/img/quiz.png" alt="">
														<?php }else{ ?>
															<img src="assets/img/quiz-empty.png" alt="">
														<?php } ?>
													</a>
												</div>
												<div class="tbl-cell">
													<div class="user-card-row-name">
														<a href="#demo<?=$no?>" data-toggle="collapse" data-parent="#accordion"><?=$materi['nama']?></a>
													</div>
													<div class="color-blue-grey-lighter"><?=($materi['date_created'] == $materi['date_modified'] ? "Diterbitkan " : "Diperbarui ").selisih_waktu($materi['date_modified'])?></div>
												</div>
												<div class="tbl-cell" align="right">
												<?php if ($hakKelas['status'] == 1 || $_SESSION['lms_id'] == $infoMapel['creator']) {?>
														<span class="label label-<?=($materi['status'] == '1' ? 'success' : 'primary')?>" style="margin-right: 20px"><?=($materi['status'] == '1' ? 'publish' : 'draft')?></span>
														<a href="quiz-action?act=update&md=<?=$infoModul['_id']?>&qz=<?=$materi['_id']?>" class="shared" title="Edit" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk memperbarui isi dari Ujian yang sudah dibuat." style="right: 35px">
															<i class="font-icon font-icon-pencil"></i>
														</a>
														<a onclick="remove('<?=$materi['_id']?>')"   class="shared" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus Materi yang sudah dibuat.">
															<i class="font-icon font-icon-trash"></i>
														</a>
												<?php
													}
												?>
												</div>
											</div>
										</div>
									</div>
									<div id="demo<?=$no?>" class="profile-post-content collapse">
										<?php
											if($submittedQuiz){
												$jumlah_soal    = $soalClass->getNumberbyQuiz($materi['id_paket']);
												$nilaiQuiz      = $quizClass->getNilaiQuiz((string)$materi['_id'], $_SESSION['lms_id']);

										?>
										<b>Petunjuk Pengerjaan Penilaian Hasil Belajar :</b><br>
										<?=nl2br(@$materi["instruksi"])?>
										<br>
										<hr style="margin: 10px 0; ">
										<b><i class="fa fa-book"></i> Bentuk Pengerjaan :</b> <?=($materi["jenis"] == "0"? "Ulangan":"Ujian")?><br />
                                        <?=($materi["jenis"] == "1"? "<b><i class='fa fa-lock'></i> Kode Ujian :</b> ".(empty($materi['kode'])?"<span class='label label-pill label-danger'>Belum Tersedia</span>":($hakKelas['status'] !=  4?"<u>".$materi['kode']."</u>":"<span class='label label-danger'>Silakan menghubungi Tutor</span> <br />")):"")?><br />
										<b><i class="fa fa-calendar"></i> Tenggat Waktu :</b> <?=date('d F Y', strtotime($materi["end_date"]))?> <br />
										<b><i class="fa fa-clock-o"></i> Waktu Pengerjaan :</b> <?=$materi["durasi"]?> menit<br />
										<b><i class="fa fa-file-o"></i> Jumlah Soal :</b> <?=$jumlah_soal?> soal<br />
										<hr style="margin: 10px 0;">
										<?php
											$nilai_akhir = round((($nilaiQuiz['nilai']/$jumlah_soal)*100), 2);

											if($nilai_akhir > 100){
												$nilai_akhir = $nilaiQuiz['nilai'];
											}
										?>
										<b><i class="fa fa-calendar-check-o"></i> Nilai Penilaian Hasil Belajar :</b> <span style="font-size: 1.5em; text-decoration: underline"><?=$nilai_akhir?></span> <br />
										<?php
											}else{
												$jumlah_soal    = $soalClass->getNumberbyQuiz($materi['id_paket']);
										?>
										<b>Petunjuk Pengerjaan Penilaian Hasil Belajar :</b><br>
										<?=nl2br(@$materi["instruksi"])?>
										<br>
										<hr style="margin: 10px 0; ">
										<b><i class="fa fa-book"></i> Bentuk Pengerjaan :</b> <?=($materi["jenis"] == "0"? "Ulangan":"Ujian")?><br />
                                        <?=($materi["jenis"] == "1"? "<b><i class='fa fa-lock'></i> Kode Ujian :</b> ".(empty($materi['kode'])?"<span class='label label-pill label-danger'>Belum Tersedia</span><br />":($hakKelas['status'] !=  4?"<u>".$materi['kode']."</u> <br />":"<span class='label label-danger'>Silakan menghubungi Tutor</span> <br />")):"")?>
										<b><i class="fa fa-calendar"></i> Tenggat Waktu :</b> <?=date('d F Y', strtotime($materi["end_date"]))?> <br />
										<b><i class="fa fa-clock-o"></i> Waktu Pengerjaan :</b> <?=$materi["durasi"]?> menit<br />
										<b><i class="fa fa-file-o"></i> Jumlah Soal :</b> <?=$jumlah_soal?> soal<br />
										<hr style="margin: 10px 0;">
										<?php
											}
											if($_SESSION['lms_status'] == 'siswa'){
													if( strtotime(date('Y-m-d')) >= strtotime($materi["start_date"]) ){
														if( strtotime(date('Y-m-d')) <= strtotime($materi["end_date"]) ){
															if(!$submittedQuiz){
                                                                if($bentukQuiz == "0"){
														?>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <a href="quiz-start?id=<?=$materi['_id']?>&paket=<?=$materi['id_paket']?>" class="btn btn-rounded btn-primary pull-right" title="Penilaian Hasil Belajar" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk mulai mengerjakan penilaian hasil belajar." style="right: 35px">
                                                                                <i class="fa fa-clock-o" aria-hidden="true"></i> Kerjakan
                                                                            </a>
                                                                        </div>
                                                                    </div>
														<?php
                                                                }else{
                                                        ?>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                        <a href="#" data-toggle="modal" data-target="#joinUjian" title="Ujian" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk masuk ke ruang Ujian." class="btn btn-rounded btn-primary pull-right">
                                                                            <i class="fa fa-laptop" aria-hidden="true"></i> Masuk Ruang Uji
                                                                        </a>
                                                                        </div>
                                                                    </div>
                                                        <?php
                                                                }
															}else{
																if($bentukQuiz == "0"){
																	?>
																		<div class="row">
																			<div class="col-md-12">
																				<a onClick="confirm_quiz('<?=$materi['_id']?>', '<?=$materi['id_paket']?>'); return false;" class="btn btn-rounded btn-primary pull-right" title="Penilaian Hasil Belajar" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk mulai mengerjakan kembali penilaian hasil belajar." style="right: 35px">
																					<i class="fa fa-clock-o" aria-hidden="true"></i> Kerjakan Ulang
																				</a>
																			</div>
																		</div>
																	<?php
																}
															}
														}else{
                                                            if($bentukQuiz == "0"){
														?>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <a class="btn btn-rounded btn-default pull-right" title="Penilaian Hasil Belajar" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Penilaian Hasil Belajar tidak dapat dikerjakan, karena sudah melewati Tenggat Waktu." style="right: 35px">
                                                                            <i class="fa fa-clock-o" aria-hidden="true"></i> Tidak dapat Mengerjakan
                                                                        </a>
                                                                    </div>
                                                                </div>
														<?php
                                                            }else{
                                                                ?>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                        <a href="#" data-toggle="modal" data-target="#joinUjian" title="Ujian" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk masuk ke ruang Ujian." class="btn btn-rounded btn-primary pull-right">
                                                                            <i class="fa fa-laptop" aria-hidden="true"></i> Masuk Ruang Uji
                                                                        </a>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                            }
														}
													}else{
                                                            if($bentukQuiz == "0"){
														?>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <a class="btn btn-rounded btn-default pull-right" title="Penilaian Hasil Belajar" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Penilaian Hasil Belajar belum dapat dikerjakan, karena waktu mulai pengerjaan baru dibuka pada <?=date('d F Y', strtotime($materi["start_date"]))?>" style="right: 35px">
                                                                            <i class="fa fa-clock-o" aria-hidden="true"></i> Belum dapat Mengerjakan
                                                                        </a>
                                                                    </div>
                                                                </div>
														<?php
                                                            }else{
                                                                ?>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                        <a href="#" data-toggle="modal" data-target="#joinUjian" title="Ujian" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk masuk ke ruang Ujian." class="btn btn-rounded btn-primary pull-right">
                                                                            <i class="fa fa-laptop" aria-hidden="true"></i> Masuk Ruang Uji
                                                                        </a>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                            }
													}
											}else{
												if($hakKelas['status'] == 1 || $infoMapel['creator'] == $_SESSION['lms_id']){
													?>
													<div class="row">
														<div class="col-md-6">
															<a href="print-quiz?id=<?=$materi['_id']?>&paket=<?=$materi['id_paket']?>" class="btn btn-rounded btn-primary pull-left" title="Print" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk print penilaian hasil belajar." style="right: 60px" target="_blank">
																<i class="fa fa-print" aria-hidden="true"></i> Print Soal
															</a>
														</div>
														<div class="col-md-6">
															<a onclick="lihat_hasil_penilaian('<?=$materi[_id]?>','daftar-penilaian','daftar-hasil-penilaian','<?=$jumlah_soal?>')" class="btn btn-rounded btn-primary pull-right" title="Print" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk print penilaian hasil belajar." style="right: 60px" target="_blank">
																<i class="fa fa-table" aria-hidden="true"></i> Lihat Hasil Penilaian
															</a>
														</div>
													</div>
													<?php
												}else{
													?>
													<div class="row">
														<div class="col-md-12">
															<a href="print-quiz?id=<?=$materi['_id']?>&paket=<?=$materi['id_paket']?>" class="btn btn-rounded btn-primary pull-left" title="Print" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk print penilaian hasil belajar." style="right: 60px" target="_blank">
																<i class="fa fa-print" aria-hidden="true"></i> Print Soal
															</a>
														</div>
													</div>
													<?php
												}
											}
										?>
									</div>
								</article>
							<?php
							$no++;
						}
					}else {
						?>
							<article class="box-typical profile-post">
								<div class="add-customers-screen tbl">
									<div class="add-customers-screen-in">
										<div class="add-customers-screen-user">
											<i class="font-icon font-icon-notebook"></i>
										</div>
										<h2>Penilaian Hasil Belajar Kosong</h2>
										<p class="lead color-blue-grey-lighter">Belum ada Penilaian Hasil Belajar</p>
									</div>
								</div>
							</article>
				<?php
					}
				?>
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
	<script src="assets/js/lib/autosize/autosize.min.js"></script>
	<script type="text/javascript" src="assets/js/lib/datatables-net/datatables.min.js"></script>

	<script>
		$("select").select2();

        var jumlah_siswa = <?=$infoKelas['jumlah_siswa']?>;

        if(jumlah_siswa > 10){
            lengthList = [ [10, jumlah_siswa], [10, "ALL"] ];
        }else{
            lengthList = [jumlah_siswa];
        }

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
      		$('#addModul').trigger("reset");
      		$('#addModulLabel').text(
      		   $('#addModulLabel').text().replace('Tambah Modul', 'Edit Modul')
      		).show();
			// $('#addModul').modal('show');
      		$.ajax({
      			type: 'POST',
      			url: 'url-API/Kelas/Modul/',
      			data: {"action": "show", "ID": ID},
      			success: function(res) {
      				if(res.data._id.$id){
      					$('#addModul').modal('show');
      					$('#idmodul').val(ID);
      					$('#namamodul').val(res.data.nama);
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
      				url: 'url-API/Kelas/Modul/Quiz/',
      				data: {"action": "remv", "ID": ID, "user": "<?=$_SESSION['lms_id']?>", "sekolah": "<?=$FuncProfile['sekolah']?>", "kelas":"<?=$infoMapel['id_kelas']?>"},
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

		function lihat_daftar_penilaian(hideElement, showElement){

			$('#'+hideElement).hide();
			$('#'+showElement).show();

            $('#tabel-penilaian').DataTable().clear().destroy();
		}

		function lihat_hasil_penilaian(ID, hideElement, showElement, jumlah_soal){

			$('#'+hideElement).hide();
			$('#'+showElement).show();

            $('#tabel-penilaian').DataTable().clear().destroy();

            $('#tabel-penilaian').DataTable( {
    		    "processing"    : true,
    		    "bServerSide"   : true,
    		    "sAjaxSource"   : "url-API/Kelas/Modul/Quiz?action=showDaftarPenilaianSiswaAjax&kelas=<?=$infoMapel['id_kelas']?>&ID="+ID+"&jumlah_soal="+jumlah_soal,
    		    "deferRender"   : true,
    		    "aoColumns"     : [
                                    { "mDataProp": "no", "bVisible": false},
    		                        { "mDataProp": "nama", "bVisible": false},
                                    { "mDataProp": "nilai", "bVisible": false},
                                    { "mDataProp": "waktu_kumpul", "bVisible": false},
    		                        { "mDataProp": "foto_user" },
    		                        { "mDataProp": "nilai_user"},
    		                        { "mDataProp": "aksi"}
    		                        ],
    		    "searchCols"    : [null, null, null, null, null, null, null],
    		    "order"         : [[1, 'desc']],
    		    "language"      : {
    		                            "infoFiltered"  : "",
    		                            "processing"	: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Memuat...</span>'
    		                        },
    		    "columnDefs": [
    		        {"className": "text-center", "targets": [4,6]}
    		    ],
    			"dom"			 : '<"row"<"col-sm-4"l><"col-sm-4"Br><"col-sm-4"f>>t<"row"<"col-sm-5"i><"col-sm-7"p>>',
    			"buttons"		 : [
                                        {
                                            extend: 'excel',
                                            exportOptions: {
                                                columns: [ 0, 1, 2, 3 ]
                                            }
                                        },
                                        {
                                            extend: 'pdf',
                                            exportOptions: {
                                                columns: [ 0, 1, 2, 3 ]
                                            }
                                        },
                                        {
                                            extend: 'print',
                                            exportOptions: {
                                                columns: [ 0, 1, 2, 3 ]
                                            }
                                        }
                                    ],
    		    "scrollX"        : true,
    		    "scrollCollapse" : true,
                "lengthMenu"     : lengthList
    		} );
		}

		function reset_quiz_none(){
			alert("Belum ada nilai!");
		}

		function reset_quiz(ID, id_user){
			var result = confirm("Anda yakin akan mereset nilai ujian anggota kelas ini?");

			loading();

			if(result){
				$.ajax({
					type: 'POST',
					url: 'url-API/Kelas/Modul/Quiz/',
					data: {"action": "resetQuiz", "ID": ID, "user": id_user},
					success: function(res) {
						alert('Berhasil direset');
                        $('#'+id_user).html("<i class='fa fa-clock-o' aria-hidden='true'></i> - <br/><span class='label label-warning'>Belum mengerjakan</span>&nbsp;&nbsp;&nbsp;<i class='fa fa-table' aria-hidden='true'></i> Nilai: -");
						loaded();
					},
					error: function (res) {
						swal("Error!", "Data tidak ditemukan!", "error");
					}
				});
			}
		}

		function message(){
			swal({
                title: "Ujian Sudah Dikerjakan!",
                text: "Anda Tidak Bisa Mengerjakannya Kembali!",
                type: "warning",
                confirmButtonText: "Ya",
                confirmButtonClass: "btn-primary",
                closeOnConfirm: true,
                showLoaderOnConfirm: true
            });
		};

		$(document).ready(function() {
			table = $('#example').DataTable({
				"order": [[ 1, "asc" ]],
				'responsive' : true,
				'bInfo' : false,
				'bLengthChange' : false,
				'pagingType' : 'simple',
				"lengthMenu": [[20, 50, -1], [20, 50, "All"]]
			});

			$('.note-statusbar').hide();

			$(".fancybox").fancybox({
				padding: 0,
				openEffect	: 'none',
				closeEffect	: 'none'
			});

			$('.tanggal').daterangepicker({
				autoUpdateInput: true,
				showDropdowns: true,
				autoApply: true,
				startDate: "2000-01-01",
				minDate: "<?=date('d M Y')?>",
				maxDate: "<?=date('Y')+10;?>-12-31",
				locale: {
					format: 'DD MMM YYYY'
				}
			});
		});
	</script>
	<script>
		function confirm_quiz(id_quiz, id_paket){
			swal({
				title: "Apakah anda yakin?",
				text: "Akan Mengerjakan Kembali Penilaian Hasil Belajar!",
				type: "warning",
				showCancelButton: true,
				cancelButtonText: "Tidak",
				confirmButtonText: "Ya",
				confirmButtonClass: "btn-primary",
				closeOnConfirm: false
			}, function () {
				document.location.href="quiz-start?id="+id_quiz+"&paket="+id_paket;
			});
		}

		$("#ohyeah").click(function(){
			$.ajax({
  				type: 'POST',
  				url: 'url-API/Siswa/index.php',
  				data: {"action": "update", "text": "tôi"},
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
<?php
	require('includes/footer-bottom.php');
?>
