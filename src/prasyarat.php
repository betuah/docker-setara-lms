<?php
require("includes/header-top.php");
?>
<link rel="stylesheet" href="assets/css/separate/pages/files.min.css">
<?php
require("includes/header-menu.php");

$mapelClass = new Mapel();
$modulClass = new Modul();

$infoModul	= $modulClass->getInfoModul($_GET['modul']);
$infoMapel	= $mapelClass->getInfoMapel($infoModul['id_mapel']);
$menuModul	= 1;
?>
	<div class="modal fade"
		 id="addModulPrasyarat"
		 tabindex="-1"
		 role="dialog"
		 aria-labelledby="addModulLabel"
		 aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<form method="POST">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="addModulLabel">Silakan Pilih Modul</h4>
				</div>
				<div class="modal-body">
					<div class="files-manager-content-in" style="margin-right:0px; border-right: 0px;">
						<div class="fm-file-grid">
							<div class="fm-file" style="height: 150px;">
								<div class="checkbox-bird">
									<input type="checkbox" id="mess-1" />
									<label for="mess-1">
										<div class="fm-file-icon">
											<img src="assets/img/folder.png" alt="">
										</div>
										<div class="fm-file-name">Inspiration</div>
									</label>
								</div>
							</div>
							<div class="fm-file" style="height: 150px;">
								<div class="checkbox-bird">
									<input type="checkbox" id="mess-2" />
									<label for="mess-2">
										<div class="fm-file-icon">
											<img src="assets/img/folder.png" alt="">
										</div>
										<div class="fm-file-name">Inspiration</div>
									</label>
								</div>
							</div>
						</div>
					</div><!--.files-manager-content-in-->
				</div>
				<div class="modal-footer">
					<button type="submit" name="addModul" value="send" class="btn btn-rounded btn-primary">Simpan</button>
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
												<p class="title">Modul <?=$infoModul['nama']?></p>
												<p>Mata Pelajaran <?=$infoMapel['nama']?></p>
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
					<section class="widget widget-activity">
						<header class="widget-header">
							Modul Prasyarat
							<span class="label label-pill label-primary"><?=$infoMapel['modul']?></span>
							<div class="btn-group" style='float: right;'>
								<button type="button" class="btn btn-sm btn-rounded btn-inline" data-toggle="modal" data-target="#addModulPrasyarat">+ Pilih Modul Prasyarat</button>
							</div>
						</header>
						<div>
							<div class="files-manager-content-in" style="margin-right:0px; border-right: 0px;">
								<div class="fm-file-grid">
									<div class="fm-file">
										<div class="fm-file-icon">
											<img src="assets/img/folder.png" alt="">
										</div>
										<div class="fm-file-name">Inspiration</div>
									</div>
									<div class="fm-file">
										<div class="fm-file-icon">
											<img src="assets/img/folder.png" alt="">
										</div>
										<div class="fm-file-name">Inspiration</div>
									</div>
									<div class="fm-file">
										<div class="fm-file-icon">
											<img src="assets/img/folder.png" alt="">
										</div>
										<div class="fm-file-name">Inspiration</div>
									</div>
									<div class="fm-file">
										<div class="fm-file-icon">
											<img src="assets/img/folder.png" alt="">
										</div>
										<div class="fm-file-name">2014_projects.rar</div>
									</div>
									<div class="fm-file">
										<div class="fm-file-icon">
											<img src="assets/img/folder.png" alt="">
										</div>
										<div class="fm-file-name">Inspiration</div>
									</div>
									<div class="fm-file">
										<div class="fm-file-icon">
											<img src="assets/img/folder.png" alt="">
										</div>
										<div class="fm-file-name">Inspiration</div>
									</div>
									<div class="fm-file">
										<div class="fm-file-icon">
											<img src="assets/img/folder.png" alt="">
										</div>
										<div class="fm-file-name">Inspiration</div>
									</div>
									<div class="fm-file">
										<div class="fm-file-icon">
											<img src="assets/img/folder.png" alt="">
										</div>
										<div class="fm-file-name">Inspiration</div>
									</div>
								</div>
							</div><!--.files-manager-content-in-->
						</div>
					</section><!--.widget-tasks-->

				</div>
			</div><!--.row-->

		</div><!--.container-fluid-->
	</div><!--.page-content-->

<?php
	require('includes/footer-top.php');
?>

	<script>
		function clearText(elementID){
			$(elementID).html("");
		}

		$(document).ready(function() {
			$(".fancybox").fancybox({
				padding: 0,
				openEffect	: 'none',
				closeEffect	: 'none'
			});

			$("#range-slider-1").ionRangeSlider({
				min: 0,
				max: 100,
				from: 30,
				hide_min_max: true,
				hide_from_to: true
			});

			$("#range-slider-2").ionRangeSlider({
				min: 0,
				max: 100,
				from: 30,
				hide_min_max: true,
				hide_from_to: true
			});

			$("#range-slider-3").ionRangeSlider({
				min: 0,
				max: 100,
				from: 30,
				hide_min_max: true,
				hide_from_to: true
			});

			$("#range-slider-4").ionRangeSlider({
				min: 0,
				max: 100,
				from: 30,
				hide_min_max: true,
				hide_from_to: true
			});

			$("#example-vertical").steps({
				headerTag: "h3",
				bodyTag: "section",
				transitionEffect: "slideLeft",
				stepsOrientation: "vertical"
			});

		});
	</script>
	<script>
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
