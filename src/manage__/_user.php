<?php
require("includes/header-top.php");
require("includes/header-menu.php");
require("includes/sidebar-menu.php");
?>
<link rel="stylesheet" href="../Assets/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="../Assets/css/separate/vendor/datatables-net.min.css">
	<div class="modal fade"
		 id="addKelas"
		 tabindex="-1"
		 role="dialog"
		 aria-labelledby="addKelasLabel"
		 aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="addKelasLabel">Tambah Kelas Baru</h4>
				</div>
				<div class="modal-body">
					<form method="POST" onSubmit="return false">
						<div class="form-group row">
							<label for="namakelas" class="col-md-3 form-control-label">Nama Kelas</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="namakelas" placeholder="Nama Kelas baru" />
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-rounded btn-primary">Simpan</button>
					<button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div><!--.modal-->

	<div class="modal fade bd-example-modal-sm"
		 id="joinKelas"
		 tabindex="-1"
		 role="dialog"
		 aria-labelledby="joinKelasLabel"
		 aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="joinKelasLabel">Bergabung Kelas</h4>
				</div>
				<div class="modal-body">
					<form method="POST" onSubmit="return false">
						<div class="form-group row">
							<div class="col-md-12">
								<input type="text" class="form-control" name="kodekelas" id="kodekelas" placeholder="Kode Kelas" />
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-rounded btn-primary">Bergabung</button>
					<button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div><!--.modal-->

	<div class="page-content">
			<div class="container-fluid">
				<header class="section-header">
					<div class="row">
						<div class="col-md-9">
							<div class="tbl-cell">
								<h2>Pengguna</h2>
								<div class="subtitle">SIAJAR LMS </div>
							</div>
						</div>
						<div class="col-md-3" style="text-align: right;">
							<a class="btn btn-primary" href="?action=tambah">Tambah Pengguna</a>
						</div>
					</div>
				</header>
				<section class="card">
					<div class="card-block">
						<table id="datatable" class="stripe row-border order-column display table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
							<tr>
								<th>Username</th>
								<th>Nama</th>
								<th>E-mail</th>
								<th>Gender</th>
								<th>Status</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td>tiger999</td>
								<td>Tiger Woods</td>
								<td>t.nixon@datatables.net</td>
								<td>Pria</td>
								<td>Aktif</td>
							</tr>
							<tr>
								<td>kudaterbang</td>
								<td>Slamet Barokah</td>
								<td>barakah@datatables.net</td>
								<td>Pria</td>
								<td>Blokir</td>
							</tr>
							<tr>
								<td>balonhijau</td>
								<td>Nur Hidayah</td>
								<td>nurhidayah@gmail.com</td>
								<td>Wanita</td>
								<td>Aktif</td>
							</tr>
							<tr>
								<td>hellokitty</td>
								<td>Umy Damayanti</td>
								<td>umyumy@yahoo.com</td>
								<td>Wanita</td>
								<td>Aktif</td>
							</tr>
							<tr>
								<td>poniku</td>
								<td>Ani Handayani</td>
								<td>handayaniani@yahoo.com</td>
								<td>Wanita</td>
								<td>Aktif</td>
							</tr>
							</tbody>
						</table>
					</div>
				</section>
			</div>
		</div>

<?php
	require('includes/footer-top.php');
?>
	<script src="../assets/js/lib/fancybox/jquery.fancybox.pack.js"></script>

	<script>
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

		});
	</script>
<script src="../assets/js/app.js"></script>
<?php
	require('includes/footer-bottom.php');
?>
