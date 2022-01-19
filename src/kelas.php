<?php
// error_reporting(E_ALL);
require("includes/header-top.php");
require("includes/header-menu.php");

$sekolahClass = new Sekolah();
$kelasClass = new Kelas();
$mapelClass = new Mapel();

$infoKelas	= $kelasClass->getInfoKelas($_GET['id']);

if ($_SESSION['lms_status'] == 'superadmin' || $_SESSION['lms_status'] == 'admin' || $_SESSION['lms_status'] == 'pengawas' || $_SESSION['lms_status'] == 'kepsek' ) {
	# code...
	$hakKelas['status'] = 3;
	$hakKelas['posisi']	= 'Super Administrator';
}else{
	$hakKelas	= $kelasClass->getKeanggotaan($_GET['id'], $_SESSION['lms_id']);
	$anggota	= in_array($_SESSION['lms_id'], array_values($infoKelas['list_member'])) ? true : false;
	if(!$anggota){
		echo "<script>
				swal({
					title: 'Maaf!',
					text: 'Anda tidak terdaftar pada Kelas ini.',
					type: 'error'
				}, function() {
					window.location = 'index';
				});
			</script>";
			die();
	}
}

//---> Proses Penambahan Mata Pelajaran
if(isset($_POST['addMapel'])){
	//---> memeriksa Hak Akses dalam Kelas || Hanya Pemilik dan Guru Mapel yang dapat membuat Kelas
	if ($hakKelas['status'] == 1 || $hakKelas['status'] == 2) {
		$nama	= mysql_escape_string($_POST['namamapel']);
		$kelas	= mysql_escape_string($_GET['id']);
		$rest 	= $mapelClass->addMapel($nama, $kelas, $_SESSION['lms_id']);
		if ($rest['status'] == "Success") {
			echo	"<script>
						swal({
							title: 'Berhasil!',
							text: 'Mata Pelajaran dgn nama \'$nama\' berhasil dibuat!',
							type: 'success'
						}, function() {
							 window.location = 'mapel?id=".$rest['IDMapel']."';
						});
					</script>";
		}else {
			echo	"<script>
						swal({
							title: 'Maaf!',
							text: 'Mata Pelajaran tidak berhasil dibuat.',
							type: 'error'
						}, function() {
							 window.location = 'mapel?id=".$rest['IDMapel']."';
						});
					</script>";
		}
	}else {
		echo	"<script>
					swal({
						title: 'Maaf!',
						text: 'Anda tidak memiliki kewenangan dalam menambahkan Mata Pelajaran baru.',
						type: 'error'
					}, function() {
						 window.location = 'index';
					});
				</script>";
	}

}

//---> Proses Penambah Posting pada Kelas
if(isset($_POST['postingText'])){
	if ($hakKelas['status'] == 1 || $hakKelas['status'] == 2 || $hakKelas['status'] == 3) {
		$post	= trim(htmlentities($_POST['textPost']));
		$rest	= $kelasClass->addPost($post, $infoKelas['_id'], $_SESSION['lms_id'], $FuncProfile['sekolah']);

		if ($rest['status'] == "Success") {
			echo "<script>document.location='kelas?id=".$_GET['id']."'</script>";
		}else{
			echo	"<script>
						swal({
							title: 'Maaf!',
							text: 'Anda tidak memiliki kewenangan dalam menambahkan Posting-an baru.',
							type: 'error'
						}, function() {
							window.location = 'index';
						});
					</script>";
		}
	}else {
		echo	"<script>
					swal({
						title: 'Maaf!',
						text: 'Anda tidak memiliki kewenangan dalam menambahkan Posting-an baru.',
						type: 'error'
					}, function() {
						window.location = 'index';
					});
				</script>";
	}
}

//---> Proses Update Pengaturan Kelas
if(isset($_POST['updateKelas'])){
	if ($hakKelas['status'] == 1) {
		$nama	= mysql_escape_string($_POST['namakelasupdate']);
		$sekolah	= $_POST['update-sekolah'];
		$post	= htmlentities($_POST['tentang']);
		$tkb	= $_POST['tkb'];
		$rest	= $kelasClass->updateKelas($_SESSION['lms_id'], $nama, $post, $sekolah, $tkb, $_GET['id']);

		echo	"<script>
					swal({
						title: '$rest[judul]',
						text: '$rest[message]',
						type: '$rest[status]'
					}, function() {
						 window.location = 'kelas?id=$rest[IDKelas]';
					});
				</script>";
	}else {
		echo	"<script>
					swal({
						title: 'Maaf!',
						text: 'Anda tidak memiliki kewenangan dalam merubah Pengaturan kelas.',
						type: 'error'
					}, function() {
						 window.location = 'index';
					});
				</script>";
	}
}

if(isset($_POST['updateSekolah'])){
	if ($hakKelas['status'] == 1) {
		$sekolah	= $_POST['updatesekolah'];
		$rest	= $kelasClass->updateSekolahKelas($_SESSION['lms_id'], $sekolah, $_GET['id']);

		echo	"<script>
					swal({
						title: '$rest[judul]',
						text: '$rest[message]',
						type: '$rest[status]'
					}, function() {
						 window.location = 'kelas?id=$rest[IDKelas]';
					});
				</script>";
	}else {
		echo	"<script>
					swal({
						title: 'Maaf!',
						text: 'Anda tidak memiliki kewenangan dalam merubah Pengaturan kelas.',
						type: 'error'
					}, function() {
						 window.location = 'index';
					});
				</script>";
	}
}

$logIDKelas = $_GET['id'];

?>
<script type="text/javascript">document.title = "Kelas <?=$infoKelas['nama']?> - seTARA daring";</script>
<link rel="stylesheet" href="assets/css/separate/elements/tags-input.css">
<link rel="stylesheet" href="assets/css/lib/bootstrap-select/ajax-bootstrap-select.css"/>

	<div class="modal fade"
		 id="updateSekolah"
		 tabindex="-1"
		 role="dialog"
		 aria-labelledby="updateSekolahLabel"
		 aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form method="POST">
				<div class="modal-header">
					<h4 class="modal-title" id="updateSekolahLabel">Pengaturan Kelas</h4>
				</div>
				<div class="modal-body">
					<p>
						Untuk melanjutkan, silahkan lengkapi isian dibawah ini!
					</p>
					<div class="form-group row">
						<label for="tentang" class="col-md-3 form-control-label">Sekolah/Instansi</label>
						<div class="col-md-9">
							<select name="updatesekolah" id="ajax-select" class="selectpicker with-ajax form-control" data-live-search="true" required>
								<?php
									if ($infoKelas['sekolah'] != '') {
										$infoSekolah = $sekolahClass->getInfoSekolah($infoKelas['sekolah']);
										echo "<option value='$infoSekolah[_id]'>$infoSekolah[nama_sekolah_induk]</option>";
									}
								?>
							</select>
                            <br>
							<small class="text-muted" id="emailNotif">Jika nama satuan pendidikan anda belum terdaftar, silakan Kepala atau Perwakilan Satuan Pendidikan untuk mengisi form pengajuan melalui <a href="http://bit.ly/pengajuan_daring" target="_blank">http://bit.ly/pengajuan_daring</a>.</small>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-rounded btn-primary" name="updateSekolah" value="send" >Simpan</button>
				</div>
				</form>
			</div>
		</div>
	</div><!--.modal-->

	<div class="modal fade"
		 id="updateKelas"
		 tabindex="-1"
		 role="dialog"
		 aria-labelledby="updateKelasLabel"
		 aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form method="POST">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="updateKelasLabel">Pengaturan Kelas</h4>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label for="namakelasupdate" class="col-md-3 form-control-label">Nama Kelas</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="namakelasupdate" id="namakelasupdate" placeholder="Nama Kelas" value="<?=$infoKelas['nama']?>" />
						</div>
					</div>
					<div class="form-group row">
						<label for="tentang" class="col-md-3 form-control-label">Sekolah</label>
						<div class="col-md-9">
							<select name="update-sekolah" id="ajax-select" class="selectpicker with-ajax form-control" data-live-search="true" required>
								<?php
									if ($infoKelas['sekolah'] != '') {
										$infoSekolah = $sekolahClass->getInfoSekolah($infoKelas['sekolah']);
										echo "<option value='$infoSekolah[_id]'>$infoSekolah[nama_sekolah_induk]</option>";
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="tentang" class="col-md-3 form-control-label">Tentang Kelas</label>
						<div class="col-md-9">
							<textarea class="form-control" name="tentang" id="tentang" placeholder="Deskripsikan tentang kelas anda - Maksimal 200 karakter" maxlength="200"><?=$infoKelas['tentang']?></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label for="tentang" class="col-md-3 form-control-label">Kelompok Belajar</label>
						<div class="col-md-9">
							<!-- <textarea id="tags-editor-textarea" placeholder="Nama Kelompok Belajar"></textarea> -->
							<input type="tags" name="tkb" data-separator=' ' placeholder="" id="tags" value="<?=$infoKelas['tkb']?>" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-rounded btn-danger pull-left" onclick="removeCl('<?=$infoKelas['_id']?>')" name="hapusKelas"><i class="font-icon-trash"></i> Hapus Kelas</button>
					<button type="submit" class="btn btn-rounded btn-primary" name="updateKelas" value="send" >Simpan</button>
					<button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
				</div>
				</form>
			</div>
		</div>
	</div><!--.modal-->

	<div class="modal fade"
		 id="addMapel"
		 tabindex="-1"
		 role="dialog"
		 aria-labelledby="addMapelLabel"
		 aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form method="POST">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="addMapelLabel">Tambah Mata Pelajaran Baru</h4>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label for="namamapel" class="col-md-3 form-control-label">Nama Mata Pelajaran</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="namamapel" id="namamapel" placeholder="Nama Mata Pelajaran" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="addMapel" value="send" class="btn btn-rounded btn-primary">Simpan</button>
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
												<p class="title"><?=$infoKelas['nama']?></p>
												<p>Kelas</p>
											</div>
											<div class="tbl-cell tbl-cell-stat">
												<div class="inline-block">
													<p class="title"><a style="color: #fff" href="anggota-kelas?id=<?=$_GET['id']?>"><?=$infoKelas['member']?></a></p>
													<p><a style="color: #fff" href="anggota-kelas?id=<?=$_GET['id']?>" title="Anggota Kelas" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-content="Lihat Anggota disini!">Anggota</a></p>
												</div>
											</div>
											<div class="tbl-cell tbl-cell-stat">
												<div class="inline-block">
													<p class="title" id="jumlahMapel"><a style="color: #fff" href="mapel-kelas?id=<?=$_GET['id']?>">0</a></p>
													<p><a style="color: #fff" href="mapel-kelas?id=<?=$_GET['id']?>" title="Mata Pelajaran" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Lihat Mata Pelajaran disini!">Mata Pelajaran</a></p>
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
		<?php
		//---> memeriksa Hak Akses dalam Kelas || Hanya pemilik Kelas yang dapat membuka Pengaturan Kelas
		if ($hakKelas['status'] == 1) {
		?>
			<button type="button" class="change-cover" onclick="update()">
				<i class="font-icon font-icon-pencil"></i>
				Pengaturan Kelas
			</button>
		<?php
		}
		?>
		</div><!--.profile-header-photo-->

		<div class="container-fluid">
			<div class="row">
				<div class="col-xl-3 col-lg-4">
					<aside id="menu-fixed2" class="profile-side" style="margin: 0 0 20px">
						<section class="box-typical">
							<header class="box-typical-header-sm bordered">
								Kode Kelas
							<?php
							//---> memeriksa Hak Akses dalam Kelas || Hanya pemilik Kelas yang dapat Kunci kelas/Buka kelas
							if ($hakKelas['status'] == 1) {
							?>
								<div class="btn-group" style='float: right;'>
									<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Aksi
									</button>
									<div class="dropdown-menu" style="margin-left: -100px">
									<?php
									//---> perintah Kunci Kelas / Buka Kelas
									if ($infoKelas['status'] != 'LOCKED') {
		                            	echo '<a class="dropdown-item" onclick="lockKelas(\'1\')"><i class="font-icon font-icon-lock"></i>Kunci Kelas</a>';
									}else {
										echo '<a class="dropdown-item" onclick="lockKelas(\'2\')"><span class="font-icon fa fa-unlock"></span>Buka Kelas</a>';
									}
									?>
									</div>
								</div>
							<?php
							}
							?>
							</header>
							<div class="box-typical-inner">
								<p style="font-size: 2em; font-weight:bold; text-align: center;">
									<?php
									if ($infoKelas['status'] != 'LOCKED') {
										echo '<u>'.$infoKelas['kode'].'</u>';
									}else {
										echo '<i class="font-icon font-icon-lock"></i> TERKUNCI';
									}
									?>
								</p>
							</div>
						</section>

						<section class="box-typical">
							<header class="box-typical-header-sm bordered">Tentang Kelas</header>
							<div class="box-typical-inner">
								<p><?=nl2br($infoKelas['tentang'])?></p>
							</div>
						</section>

						<section class="box-typical">
							<header class="box-typical-header-sm bordered">
								Daftar Mata Pelajaran

							<?php
							if ($hakKelas['status'] == 1 || $hakKelas['status'] == 2) {
							?>
								<div class="btn-group" style='float: right;'>
									<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Aksi
									</button>
									<div class="dropdown-menu" style="margin-left: -100px">
										<a class="dropdown-item" href="#" data-toggle="modal" data-target="#addMapel"><span class="font-icon font-icon-plus"></span>Tambah Mata Pelajaran</a>
		                                <a class="dropdown-item" href="mapel-kelas?id=<?=$_GET['id']?>"><span class="font-icon font-icon-pencil"></span>Kelola Mata Pelajaran</a>
									</div>
								</div>
							<?php
							}
							?>
							</header>
							<div class="box-typical-inner" id="listMapel">
								<p style="text-align: center;">
									Menunggu..
								</p>
							</div>
						</section>

					</aside><!--.profile-side-->
				</div>

				<div class="col-xl-9 col-lg-8">
					<section class="tabs-section">

						<div class="tab-content no-styled profile-tabs">
						<?php
							if ($hakKelas['status'] == 3) {
								// echo '	<article class="box-typical profile-post" style="border-radius: 10px">
								// 			<div class="profile-post-content">
								// 				<h3 align="center" style="margin:0; color: red; font-style: italic">
								// 				 Jangan lupa untuk meng-update konten di server lokal anda!
								// 				</h3>
								// 			</div>
								// 		</article>';
							}

							if ($_SESSION['lms_status'] == 'guru' || $hakKelas['status'] == 1) {
						?>
							<form class="box-typical" method="post" action="">
								<textarea class="write-something" name="textPost" id="textPost" placeholder="Apa yang ingin anda beritahukan?" required></textarea>
								<div class="box-typical-footer">
									<div class="tbl">
										<div class="tbl-row">
											<!-- <div class="tbl-cell">
												<button type="button" class="btn-icon" title="lampiran tautan">
													<i class="font-icon font-icon-earth"></i>
												</button>
												<button type="button" class="btn-icon" title="lampiran gambar">
													<i class="font-icon font-icon-picture"></i>
												</button>
												<button type="button" class="btn-icon">
													<i class="font-icon font-icon-calend"></i>
												</button>
												<button type="button" class="btn-icon">
													<i class="font-icon font-icon-video-fill"></i>
												</button>
											</div> -->
											<div class="tbl-cell tbl-cell-action">
												<button type="submit" name="postingText" class="btn btn-rounded pull-right">Kirim</button>
											</div>
										</div>
									</div>
								</div>
							</form><!--.box-typical-->
						<?php
							}

								$listPosting	= $kelasClass->postingKelas($_GET['id'], isset($_GET['halaman']) ? $_GET['halaman'] : 1);

								if ($listPosting['count'] > 0) {
									// echo "<pre>";
									// print_r($listPosting['data']);
									// echo "</pre>";
									foreach ($listPosting['data'] as $posting) {
										$image		= empty($posting['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='media/Assets/foto/".$posting['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
										echo '	<article class="box-typical profile-post">
													<div class="profile-post-header">
														<div class="user-card-row">
															<div class="tbl-row">
																<div class="tbl-cell tbl-cell-photo">
																	<a href="#">'.$image.'</a>
																</div>
																<div class="tbl-cell">
																	<div class="user-card-row-name"><a href="#">'.$posting['user'].'</a></div>
																	<div class="color-blue-grey-lighter"><small>'.$posting['user_akses'].' - '.selisih_waktu($posting['date_created']).'</small></div>
																</div>
															</div>
														</div>
													';
										if ($_SESSION['lms_id'] == $posting['creator']) {
										echo '		<a class="shared" onclick="removePost(\''.$posting['_id'].'\')" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus kiriman yang sudah dibuat.">
														<i class="font-icon font-icon-trash"></i>
													</a>';
										}
										echo '		</div>
													<div class="profile-post-content">
														<p>
															'.nl2br($posting['isi_postingan']).'
														</p>
													</div>
												</article>';
									}
									echo $listPosting['tombol'];
								} else {
									echo '	<article class="box-typical profile-post">
												<div class="profile-post-content">
													<p align="center">
													 Belum ada Postingan saat ini.
													</p>
												</div>
											</article>';
								}

							?>
							<!-- <article class="box-typical profile-post">
								<div class="profile-post-header">
									<div class="user-card-row">
										<div class="tbl-row">
											<div class="tbl-cell tbl-cell-photo">
												<a href="#">
													<img src="assets/img/photo-64-2.jpg" alt="">
												</a>
											</div>
											<div class="tbl-cell">
												<div class="user-card-row-name"><a href="#">Pansera Guru</a> &nbsp; &gt; &nbsp; <a href="#">Contoh Kelas 1</a></div>
												<div class="color-blue-grey-lighter">3 hari lalu</div>
											</div>
										</div>
									</div>
									<a href="#" class="shared">
										<i class="font-icon font-icon-share"></i>
									</a>
								</div>
								<div class="profile-post-content">
									<p>
										Pengumuman<br />
										<br />
										Besok kelas di liburkan, kepada seluruh Tutor harap memberitahu siswa/i yang berada di masing-masing TKB.<br />
										<br />
										Terima Kasih
									</p>
								</div>
							</article> -->

						</div><!--.tab-content-->
					</section><!--.tabs-section-->
				</div>
			</div><!--.row-->
		</div><!--.container-fluid-->
	</div><!--.page-content-->

<?php
	require('includes/footer-top.php');
?>
<script type="text/javascript" src="assets/js/lib/bootstrap-select/ajax-bootstrap-select.js"></script>
<script src="assets/js/lib/autoresize/autoresize-textarea.js"></script>
<script src="assets/js/lib/tags-input/tags-input.js"></script>

	<script>
		function clearText(elementID){
			$(elementID).html("");
		}

		$(function(){
	      $('#textPost').autoResize();
	    });

		function update(){
      		$('#updateKelas').trigger("reset");
      		$('#updateKelas').modal("show");
      		$('#updateKelasLabel').text(
      		   $('#updateKelasLabel').text().replace('Tambah Modul', 'Pengaturan Kelas')
      		).show();
      	}

		function removeCl(ID){
			swal({
			  title: "Apakah anda yakin?",
			  text: "Semua data yang sudah dihapus, tidak dapat dikembalikan lagi!",
			  type: "warning",
			  showCancelButton: true,
				confirmButtonText: "Ya",
				confirmButtonClass: "btn-danger",
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true
			}, function () {
				$.ajax({
					type: 'POST',
					url: 'url-API/Kelas/',
					data: {"action": "rmv", "ID": "<?=$_GET['id']?>", "u":"<?=$_SESSION['lms_id']?>"},
					success: function(res) {
						swal({
							title: res.response,
							text: res.message,
							type: res.icon
						}, function() {
							 window.location = './';
						});
					},
					error: function () {
						swal("Gagal!", "Data tidak terhapus!", "error");
					}
				});
			});
		}

		function removePost(ID){
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
      				url: 'url-API/Kelas/Posting/',
      				data: {"action": "remv", "ID": ID},
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

		function lockKelas(ID){
			var isiText = '';
			if(ID == 1){
				isiText = 'Saat Kelas di Kunci, maka tidak ada yang dapt bergabung ke dalam Kelas hingga anda membukanya kembali.';
			}else {
				isiText = 'Kelas akan dibuka, sehingga siapa saja yang memiliki Kode Kelas ini dapat bergabung ke dalam Kelas.';
			}
      		swal({
      		  title: "Apakah anda yakin?",
      		  text: isiText,
      		  type: "warning",
      		  showCancelButton: true,
			  	confirmButtonText: "Setuju!",
      			confirmButtonClass: "btn-warning",
      		  closeOnConfirm: false,
      		  showLoaderOnConfirm: true
      		}, function () {
      			$.ajax({
      				type: 'POST',
      				url: 'url-API/Kelas/',
      				data: {"action": "lockKelas", "ID": "<?=$_GET['id']?>", "user": "<?=$_SESSION['lms_id']?>"},
      				success: function(res) {
						if(res.status == 'Success'){
							swal({
								title: 'Berhasil!',
								text: res.message,
								type: 'success'
							}, function() {
								 window.location = "kelas?id=<?=$_GET['id']?>";
							});
						}else {
							swal('Maaf!', 'Kelas tidak berhasil di Kunci.', 'error');
						}
      				},
      				error: function () {
      					swal("Maaf!", "Data tidak berhasil diubah!", "error");
      				}
      			});
      		});
      	}

      	// ----> AJAX Bootstrap Select
		var options = {
	        ajax          : {
	            url     : 'includes/option-sekolah.php',
	            type    : 'POST',
	            dataType: 'json',
	            // Use "{{{q}}}" as a placeholder and Ajax Bootstrap Select will
	            // automatically replace it with the value of the search query.
	            data    : {
	                q: '{{{q}}}',
	                t: 'select'
	            }
	        },
	        locale        : {
	            emptyTitle: 'Pilih Asal Sekolah'
	        },
	        log           : 3,
	        preprocessData: function (data) {
	            var i, l = data.length, array = [];
	            if (l) {
	                for (i = 0; i < l; i++) {
	                    array.push($.extend(true, data[i], {
	                        text : data[i].text,
	                        value: data[i].id,
	                        data : {
	                            subtext: 'NPSN : '+data[i].npsn+'<br>'+'Provinsi : '+data[i].provinsi+'<br>'+'Kabupaten/Kota : '+data[i].kab_kot
	                        }
	                    }));
	                }
	            }
	            // You must always return a valid array when processing data. The
	            // data argument passed is a clone and cannot be modified directly.
	            return array;
	        }
	    };

	    $('.selectpicker').selectpicker().filter('.with-ajax').ajaxSelectPicker(options);
	    $('select').trigger('change');
	    // ----> END AJAX Bootstrap Select

		$(document).ready(function() {
			<?php
				if ($hakKelas['status']==1 && $infoKelas['update'] != "2021.1") {
			?>
					$('#updateSekolah').modal({
						backdrop: 'static',
						keyboard: false
					});
			<?php
				}
			?>

			$.ajax({
				type: 'POST',
				url: 'url-API/Kelas/Mapel/',
				data: {"action": "showList", "ID": "<?=$_GET['id']?>"},
				success: function(res) {
					$('#listMapel').html('');
					$('#jumlahMapel').html(res.data.length);
					if(res.data.length > 0){
						res.data.forEach(function(entry) {
						var hak = entry.creator;
						var hsl = (hak == "<?=$_SESSION['lms_id']?>") ? ' <b>(Pengampu)</b>' : '';

						$('#listMapel').append('<p class="line-with-icon">'+
								'<i class="font-icon font-icon-folder"></i>'+
								'<a href="mapel?id='+entry._id.$id+'">'+entry.nama+hsl+'</a>'+
							'</p>');
						});
					}else{
						$('#listMapel').append('<p style="text-align:center;">'+
									'Belum ada Mata Pelajaran'+
								'</p>');
					}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					// console.log('ERROR !');
					 alert(textStatus);
				}
			});

			$(".fancybox").fancybox({
				padding: 0,
				openEffect	: 'none',
				closeEffect	: 'none'
			});

		});

		tagsInput(document.querySelector('input[type="tags"]'));
	</script>

<script src="assets/js/app.js"></script>
<?php
	require('includes/footer-bottom.php');
?>
