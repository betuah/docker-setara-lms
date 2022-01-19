<?php
// error_reporting(E_ALL);
require("includes/header-top.php");
require("includes/header-menu.php");

$kelasClass = new Kelas();
$mapelClass = new Mapel();
$userClass	= new User();

$infoKelas	= $kelasClass->getInfoKelas($_GET['id']);

$hakKelas	= $kelasClass->getKeanggotaan($_GET['id'], $_SESSION['lms_id']);
// echo "Ini loh hak kelas-nya : ".$hakKelas['status'];
// $anggota	= in_array($_SESSION['lms_id'], array_values($infoKelas['list_member'])) ? true : false;
if(!$hakKelas['status']){
	echo "<script>
			swal({
				title: 'Maaf!',
				text: 'Anda tidak terdaftar pada Kelas / Kelas tidak tsb tidak ada.',
				type: 'error'
			}, function() {
				 window.location = 'index.php';
			});
		</script>";
		die();
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
							 window.location = 'mapel.php?id=".$rest['IDMapel']."';
						});
					</script>";
		}else {
			echo	"<script>
						swal({
							title: 'Maaf!',
							text: 'Mata Pelajaran tidak berhasil dibuat.',
							type: 'error'
						}, function() {
							 window.location = 'mapel.php?id=".$rest['IDMapel']."';
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
						 window.location = 'index.php';
					});
				</script>";
	}

}

//---> Proses Update Pengaturan Kelas
if(isset($_POST['updateKelas'])){
	if ($hakKelas['status'] == 1) {
		$nama	= mysql_escape_string($_POST['namakelasupdate']);
		$post	= htmlentities($_POST['tentang']);
		$tkb	= $_POST['tkb'];
		$rest	= $kelasClass->updateKelas($nama, $post, $tkb, $_GET['id']);

		echo	"<script>
					swal({
						title: '$rest[judul]',
						text: '$rest[message]',
						type: '$rest[status]'
					}, function() {
						 window.location = 'kelas.php?id=$rest[IDKelas]';
					});
				</script>";
	}else {
		echo	"<script>
					swal({
						title: 'Maaf!',
						text: 'Anda tidak memiliki kewenangan dalam merubah Pengaturan kelas.',
						type: 'error'
					}, function() {
						 window.location = 'index.php';
					});
				</script>";
	}
}

?>
<link rel="stylesheet" href="assets/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="assets/css/separate/vendor/datatables-net.min.css">
<link rel="stylesheet" href="assets/css/separate/elements/tags-input.css">
<style media="screen">
	tr:last-child td {
		border-bottom: 1px solid #d8e2e7;
	}
	.user-name{
		font-size: 1.1em;
		font-weight: bold;
	}
	.table a{
		border: none;
	}
	@media (max-width: 470px) {
		.tb-lg {
			display: none;
		}
	}
	@media (min-width: 470px) {
		.tb-sm {
			display: none;
		}
	}
</style>
<link rel="stylesheet" href="assets/css/separate/elements/tags-input.css">
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
						<label for="tentang" class="col-md-3 form-control-label">Tentang Kelas</label>
						<div class="col-md-9">
							<textarea class="form-control" name="tentang" id="tentang" placeholder="Deskripsikan tentang kelas anda - Maksimal 260 karakter" maxlength="260"><?=$infoKelas['tentang']?></textarea>
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
												<p class="title"><?=$infoKelas['nama']?></p>
												<p>Anggota Kelas</p>
											</div>
											<div class="tbl-cell tbl-cell-stat">
												<div class="inline-block">
													<p class="title"><?=$infoKelas['member']?></p>
													<p>Anggota Kelas</p>
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
		                                <a class="dropdown-item" href="#"><span class="font-icon font-icon-pencil"></span>Kelola Mata Pelajaran</a>
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
					<section class="widget widget-tasks card-default">
						<header class="card-header">
							Daftar Anggota kelas
						</header>
						<div class="widget-tasks-item">
							<table id="example" class="display table table-striped" cellspacing="0" width="100%">
								<thead style="display:none;">
									<tr>
										<th class="tb-lg">Foto</th>
										<th>Nama dan Sekolah</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
							<?php
							if ($infoKelas['member'] > 0) {
								$m	= 1;
								foreach (array_values($infoKelas['list_member']) as $data) {
									$menu		= '';
									$infoUser	= $userClass->GetData($data);
									$infoHak	= $kelasClass->getKeanggotaan($_GET['id'], "$infoUser[_id]");
									switch ($infoHak['status']) {
									    case 1:
									        $posisi	= "Wali Kelas";
									        break;
									    case 2:
									        $posisi	= "Guru Mata Pelajaran";
									        break;
									    case 3:
									        $posisi	= "Tutor";
									        break;
										default:
											$posisi	= "Anggota";
											break;
									}

									if ($infoUser['status'] == 'guru') {
										if ($infoHak['status'] == 2) {
											$menu		= '<div class="btn-group" style="float: right;">
																<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	Aksi
																</button>
																<div class="dropdown-menu dropdown-menu-right">
																	<div class="radio">
																		<a class="dropdown-item" href="#">
																			<input type="radio" name="statusGuru'.$m.'" id="statusGuru2" onclick="cPriv(\''.$infoUser['_id'].'\', \'status'.$m.'\', 2)" value="2" checked >
																			<label for="statusGuru2">Guru Mata Pelajaran </label>
																		</a>
																		<a class="dropdown-item" href="#">
																			<input type="radio" name="statusGuru'.$m.'" id="statusGuru3" onclick="cPriv(\''.$infoUser['_id'].'\', \'status'.$m.'\', 3)" value="3" >
																			<label for="statusGuru3">Tutor </label>
																		</a>
																	</div>
																	<div class="dropdown-divider"></div>
																	<a class="dropdown-item" onclick="remove(\''.$infoUser['_id'].'\')">Hapus Anggota</a>
																</div>
															</div>';
										}elseif ($infoHak['status'] == 3) {
											$menu		= '<div class="btn-group" style="float: right;">
																<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	<span class="tb-lg">Aksi</span>
																	<span class="tb-sm"><i class="fa fa-pencil"></i></span>
																</button>
																<div class="dropdown-menu dropdown-menu-right">
																	<div class="radio">
																		<a class="dropdown-item" href="#">
																			<input type="radio" name="statusGuru'.$m.'" id="statusGuru2" onclick="cPriv(\''.$infoUser['_id'].'\', \'status'.$m.'\', 2)" value="2" >
																			<label for="statusGuru2">Guru Mata Pelajaran </label>
																		</a>
																		<a class="dropdown-item" href="#">
																			<input type="radio" name="statusGuru'.$m.'" id="statusGuru3" onclick="cPriv(\''.$infoUser['_id'].'\', \'status'.$m.'\', 3)" value="3" checked >
																			<label for="statusGuru3">Tutor </label>
																		</a>
																	</div>
																	<div class="dropdown-divider"></div>
																	<a class="dropdown-item" onclick="remove(\''.$infoUser['_id'].'\')">Hapus Anggota</a>
																</div>
															</div>';
										}
									}elseif ($infoUser['status'] == 'siswa') {
										$menu		= '<div class="btn-group" style="float: right;">
															<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																<span class="tb-lg">Aksi</span>
																<span class="tb-sm"><i class="fa fa-pencil"></i></span>
															</button>
															<div class="dropdown-menu dropdown-menu-right">
																<a class="dropdown-item" onclick="remove(\''.$infoUser['_id'].'\')">Hapus Anggota</a>
															</div>
														</div>';
									}
									$menuAnggota = '';
									if ($_SESSION['lms_id'] == $infoUser['_id']) {
										if ($infoHak['status'] == 3 || $infoHak['status'] == 4) {
											$menuAnggota	= '<div class="btn-group" style="float: right;">
																<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	<span class="tb-lg">Aksi</span>
																	<span class="tb-sm"><i class="fa fa-pencil"></i></span>
																</button>
																<div class="dropdown-menu dropdown-menu-right">';
																if ($infoKelas['tkb'] != '') {
																	$no = 1;
																	$datatkb	=  explode(',', $infoKelas['tkb']);
											$menuAnggota	.= '	<div class="radio">';
																	foreach ($datatkb as $tkb) {
											$menuAnggota	.= '		<a class="dropdown-item" onclick="cTKB(\''.$infoUser['_id'].'\', \''.$tkb.'\', \'namaTKB'.$no.'\', \'member'.$m.'\')" >
																			<input type="radio" name="namaTKB" id="namaTKB'.$no.'" value="'.$tkb.'" '.(isset($infoHak['tkb']) && $infoHak['tkb'] == $tkb ? "checked" : "").' >
																			<label for="statusGuru3">'.$tkb.' </label>
																		</a>';
																	$no++;
																	}
											$menuAnggota	.= '	</div>
																	<div class="dropdown-divider"></div>';
																}

											$menuAnggota	.= '	<a class="dropdown-item" onclick="out(\''.$infoUser['_id'].'\')">Keluar Kelas</a>
																</div>
															</div>';
										}else {
											$menuAnggota	= '<div class="btn-group" style="float: right;">
																<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	<span class="tb-lg">Aksi</span>
																	<span class="tb-sm"><i class="fa fa-pencil"></i></span>
																</button>
																<div class="dropdown-menu dropdown-menu-right">
																	<a class="dropdown-item" onclick="out(\''.$infoUser['_id'].'\')">Keluar Kelas</a>
																</div>
															</div>';
										}

									}

									$image		= empty($infoUser['foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='media/Assets/foto/".$infoUser['foto']."' style='max-width: 75px; max-height: 75px;' />" ;
									echo "	<tr>
												<td  class='tb-lg' width='80px;'>".$image."</td>
												<td>
													<span class='user-name'>$infoUser[nama]</span> <br>
													<span style='font-size: 0.9em;'>
														$infoUser[sekolah]
													</span><br>
													<span style='font-size: 0.9em;' id='status$m'>
														".ucfirst($infoUser['status'])." (".$posisi.")
													</span><br>
													<span style='font-size: 0.9em;' id='member$m'>
														<b>".@$infoHak['tkb']."</b>
													</span>
												</td>
												<td width='70px;' class='shared'>".($hakKelas['status'] == 1 ? $menu : $menuAnggota)."</td>
											</tr>";
									$m++;
								}
							}
							?>
								</tbody>
							</table>
						</div>
					</section><!--.widget-tasks-->
				</div>
			</div><!--.row-->
		</div><!--.container-fluid-->
	</div><!--.page-content-->

<?php
	require('includes/footer-top.php');
?>
<script src="assets/js/lib/datatables-net/datatables.min.js"></script>
<script src="assets/js/lib/autoresize/autoresize-textarea.js"></script>
<script src="assets/js/lib/tags-input/tags-input.js"></script>

<script>
	var table;

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

		function remove(ID){
      		swal({
      		  title: "Apakah anda yakin?",
      		  text: "Menghapus anggota dari kelas ini.",
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
      				data: {"action": "removeAnggota", "ID": ID, "kelas": "<?=$_GET['id']?>"},
      				success: function(res) {
      					swal(res.response, res.message, res.icon);
      				},
      				error: function () {
      					swal("Gagal!", "Data tidak terhapus!", "error");
      				}
      			});
      		});
      	}

		function out(ID){
      		swal({
      		  title: "Apakah anda yakin?",
      		  text: "Keluar dari kelas ini.",
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
      				data: {"action": "removeAnggota", "ID": ID, "kelas": "<?=$_GET['id']?>"},
      				success: function(res) {
						swal({
				            title: res.response,
				            text: res.message,
				            type: res.icon
				        }, function() {
				            window.location='<?=base_url?>';
				        });
      				},
      				error: function () {
      					swal("Gagal!", "Data tidak terhapus!", "error");
      				}
      			});
      		});
      	}

	function cPriv(ID, Text, Priv){
		$.ajax({
			type: 'POST',
			url: 'url-API/Kelas/',
			data: {"action": "cPriv", "ID": ID, "hak_akses" : Priv, "kelas": "<?=$_GET['id']?>"},
			success: function(res) {
				var jabatan = Priv == 2 ? 'Guru (Guru Mata Pelajaran)' : 'Guru (Tutor)';
				swal(res.status, res.message, res.icon);
				$('#'+Text).html(jabatan)
			},
			error: function () {
				swal("Gagal!", "Data tidak berubah!", "error");
			}
		});
  	}

	function cTKB(ID, TKB, IDTKB, Text){
		$.ajax({
			type: 'POST',
			url: 'url-API/Kelas/',
			data: {"action": "cTKB", "ID": ID, "tkb" : TKB, "kelas": "<?=$_GET['id']?>"},
			success: function(res) {
				$('input[type="radio"]').removeAttr('checked', '');
				swal(res.status, res.message, res.icon);
				$('#'+IDTKB).prop('checked','checked');
				$('#'+Text).html('<b>'+TKB+'</b>');
			},
			error: function () {
				swal("Gagal!", "Data tidak berubah!", "error");
			}
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
							 window.location = "kelas.php?id=<?=$_GET['id']?>";
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

	$(document).ready(function() {
		table = $('#example').DataTable({
			"order": [[ 1, "asc" ]],
			'responsive' : true,
			'bInfo' : false,
			'bLengthChange' : false,
			'pagingType' : 'simple',
			"lengthMenu": [[20, 50, -1], [20, 50, "All"]]
		});

		$.ajax({
			type: 'POST',
			url: 'url-API/Kelas/Mapel/',
			data: {"action": "showList", "ID": "<?=$_GET['id']?>"},
			success: function(res) {
				$('#listMapel').html('');
				$('#jumlahMapel').html(res.data.length);
				if(res.data.length > 0){
					for(i=0; i<=res.data.length; i++){
						$('#listMapel').append('<p class="line-with-icon">'+
								'<i class="font-icon font-icon-folder"></i>'+
								'<a href="mapel.php?id='+res.data[i]._id.$id+'">'+res.data[i].nama+'</a>'+
							'</p>');
					}
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

	});

	tagsInput(document.querySelector('input[type="tags"]'));
</script>

<script src="assets/js/app.js"></script>

<?php
	require('includes/footer-bottom.php');
?>
