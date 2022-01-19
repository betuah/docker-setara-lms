<?php
require("includes/header-top.php");
require("includes/header-menu.php");

$mapelClass 	= new Mapel();
$modulClass 	= new Modul();
$materiClass 	= new Materi();
$kelasClass		= new Kelas();

$menuModul		= 2;
$infoModul		= $modulClass->getInfoModul($_GET['modul']);
$infoMapel		= $mapelClass->getInfoMapel($infoModul['id_mapel']);
$infoMateri		= $materiClass->getTotalMateri($_GET['modul']);

$hakKelas		= $kelasClass->getKeanggotaan($infoMapel['id_kelas'], $_SESSION['lms_id']);
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

if(isset($_POST['addMateri']) || isset($_POST['updateMateri'])){


	if(isset($_POST['addMateri'])){
		$rest 	= $materiClass->addMateri($_GET['modul'], $_POST['judul'], $_POST['isi'], $_SESSION['lms_id']);
	}else{
		$rest 	= $materiClass->updateMateri($_GET['modul'], $_POST['judul'], $_POST['isi']);
	}

	if ($rest['status'] == "Success") {
		echo "<script>alert('".$rest['status']."'); document.location='materi.php?modul=".$rest['IDModul']."'</script>";
	}else{
		echo "<script>alert('Gagal Update')</script>";
	}
}
?>

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
												<p class="title">Modul <?=$infoModul['nama']?></p>
												<p>Mata Pelajaran <?=$infoMapel['nama']?></p>
											</div>
											<div class="tbl-cell tbl-cell-stat">
												<div class="inline-block">
													<p class="title"><?=$infoMateri->count();?></p>
													<p>Materi</p>
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
			<button type="button" class="change-cover">
				<i class="font-icon font-icon-picture-double"></i>
				Ganti sampul
				<input type="file"/>
			</button>
		</div><!--.profile-header-photo-->

		<div class="container-fluid">
			<div class="row">
				<div class="col-xl-3 col-lg-4">
					<?php
						require("includes/modul-menu.php");
					?>
				</div>

				<div class="col-xl-9 col-lg-8">
					<section class="card card-inversed">
						<header class="card-header">
							Kumpulan Materi

							<?php
								if($infoModul['creator'] == $_SESSION['lms_id']){
									echo '<div class="btn-group" style="float: right;">
										<a href="materi-action.php?modul='.$infoModul['_id'].'" title="Tambah" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menambahkan materi baru." class="btn btn-sm btn-rounded">+ Tambah Materi</a>
									</div>';
								}
							?>

						</header>
						<div class="card-block" id="accordion">
				<?php
					$no	= 1;
					if ($infoMateri->count() > 0) {
						foreach ($infoMateri as $materi) {
							if ($_SESSION['lms_id'] == $materi['creator']) {
							echo '<article class="box-typical profile-post panel">
									<div class="profile-post-header">
										<div class="user-card-row">
											<div class="tbl-row">
												<div class="tbl-cell tbl-cell-photo">
													<a href="#demo'.$no.'" data-toggle="collapse" data-parent="#accordion">
														<img src="assets/img/folder.png" alt="">
													</a>
												</div>
												<div class="tbl-cell">
													<div class="user-card-row-name"><a href="#demo'.$no.'" data-toggle="collapse" data-parent="#accordion">'.$materi['judul'].'</a></div>
													<div class="color-blue-grey-lighter">'.($materi['date_created'] == $materi['date_modified'] ? "" : "Diperbarui ").selisih_waktu($materi['date_modified']).'</div>
												</div>
												<div class="tbl-cell" align="right">
													<span class="label label-'.($materi['status'] == "publish" ? "success" : "primary").'" style="margin-right: 20px">'.ucfirst($materi['status']).'</span>
													<a href="materi-action.php?act=update&modul='.$infoModul['_id'].'&materi='.$materi['_id'].'" class="shared" title="Edit" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk memperbarui isi dari Materi yang sudah dibuat." style="right: 35px">
														<i class="font-icon font-icon-pencil")"></i>
													</a>
													<a onclick="remove(\''.$materi['_id'].'\')"   class="shared" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus Materi yang sudah dibuat.">
														<i class="font-icon font-icon-trash")"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
									<div id="demo'.$no.'" class="profile-post-content collapse">
										'.$materi["isi"].'
									</div>
								</article>';
							}else {
								if($materi['status'] != 'draft'){
								echo '<article class="box-typical profile-post panel">
										<div class="profile-post-header">
											<div class="user-card-row">
												<div class="tbl-row">
													<div class="tbl-cell tbl-cell-photo">
														<a href="#demo'.$no.'" data-toggle="collapse" data-parent="#accordion">
															<img src="assets/img/folder.png" alt="">
														</a>
													</div>
													<div class="tbl-cell">
														<div class="user-card-row-name"><a href="#demo'.$no.'" data-toggle="collapse" data-parent="#accordion">'.$materi['judul'].'</a></div>
														<div class="color-blue-grey-lighter">'.($materi['date_created'] == $materi['date_modified'] ? "Diterbitkan " : "Diperbarui ").selisih_waktu($materi['date_modified']).'</div>
													</div>
												</div>
											</div>
										</div>
										<div id="demo'.$no.'" class="profile-post-content collapse">
											'.$materi["isi"].'
										</div>
									</article>';
								}
							}
							$no++;
						}
					}else {
						echo '	<article class="box-typical profile-post">
									<div class="profile-post-content" align="center">
										<span>
										 Belum ada Materi saat ini.
										</span>
									</div>
								</article>';
					}
				?>
						</div>
					</section>
				</div>
			</div><!--.row-->

		</div><!--.container-fluid-->
	</div><!--.page-content-->

<?php
	require('includes/footer-top.php');
?>
	<script>
		$(document).ready(function() {
			$('.note-statusbar').hide();
		});

		function clearText(elementID){
			$(elementID).html("");
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
      				url: 'url-API/Kelas/Modul/Materi/',
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
	</script>

	<script src="assets/js/app.js"></script>
	<script type="text/javascript" src="./assets/tinymce4/js/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image"></script>


<?php
	require('includes/footer-bottom.php');
?>
