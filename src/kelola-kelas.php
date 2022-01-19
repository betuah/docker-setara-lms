<?php
require("includes/header-top.php");
require("includes/header-menu.php");

$userClass	= new User();
$kelasClass	= new Kelas();
$profilClass = new Profile();
$ProvkotClass = new Provkot();


$listKelas	= $kelasClass->getListKelas($_SESSION['lms_id']);
// echo "ini loh - ".$listKelas;
// echo "ini loh - ";
// print_r($listKelas);


$userProfil	= $userClass->GetData($_SESSION['lms_id']);
if (isset($userProfil['kota']) || !empty($userProfil['kota'])) {
	$getKota = $ProvkotClass->getKota((int)$userProfil['kota']);
	$asalKota = $getKota['nama_kab_kot'];
}

?>
<script type="text/javascript">document.title = "Kelola Daftar Kelas - seTARA daring";</script>
<link rel="stylesheet" href="assets/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="assets/css/separate/vendor/datatables-net.min.css">
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
</style>

	<div class="page-content">
		<div class="profile-header-photo">
			<div class="profile-header-photo-in">
				<div class="tbl-cell">
					<div class="info-block">
						<div class="container-fluid">
							<div class="row">
								<div class="offset-md-3 col-md-9">
									<div class="tbl info-tbl">
										<div class="tbl-row">
											<div class="tbl-cell">
												<p class="title"><?=$_SESSION['lms_name']?> (<?=$_SESSION['lms_username']?>)</p>
												<p><?=ucfirst($_SESSION['lms_status'])?></p>
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
					<aside id="menu-fixed2" class="profile-side">
						<section class="box-typical profile-side-user">
							<button type="button" class="avatar-preview avatar-preview-128">
								<img src="media/Assets/foto/<?php if ($FuncProfile['foto'] != NULL) {echo $FuncProfile['foto'];}else{echo "no_picture.png";} ?>" alt=""/>
							</button>
							<button type="button" id="ohyeah" class="btn btn-rounded"><span data-toggle="modal" data-target="#joinKelas"><i class="font-icon font-icon-user"></i> Gabung Kelas</span></button>

						</section>
						<?php
						if(strtolower($_SESSION['lms_status']) == 'siswa'){
						echo '
							<section class="box-typical profile-side-stat">
								<div class="tbl">
									<div class="tbl-row">
										<div class="tbl-cell">
											<span class="number" id="jmlKelas">0</span>
											kelas yang diikuti
										</div>
									</div>
								</div>
							</section>';
						}elseif (strtolower($_SESSION['lms_status']) == 'guru') {
							echo '<section class="box-typical profile-side-stat">
								<header class="box-typical-header-sm bordered">Mengampu</header>
								<div class="tbl">
									<div class="tbl-row">
										<div class="tbl-cell">
											<span class="number" id="jmlKelas">0</span>
											Kelas
										</div>
									</div>
								</div>
							</section>';
						}
						?>

						<!-- <section class="box-typical">
							<header class="box-typical-header-sm bordered">Tentang Saya</header>
							<div class="box-typical-inner">
								<p>
									<ul style="list-style-type: circle;margin-left: 20px;">
										<li>Simple</li>
										<li>Pekerja Keras</li>
										<li>Periang</li>
										<li>Rajin Olahraga</li>
									</ul>
								</p>
							</div>
						</section> -->

						<section class="box-typical">
							<header class="box-typical-header-sm bordered">Info</header>
							<div class="box-typical-inner">
								<?php echo (isset($userProfil['kota']) && !empty($userProfil['kota'])) ? '
								<p class="line-with-icon">
									<i class="font-icon font-icon-pin-2"></i>
									<a href="#">'.$asalKota.'</a>
								</p>' : '';
								?>
								<?php echo (isset($userProfil['sekolah']) && !empty($userProfil['sekolah'])) ? '
								<p class="line-with-icon">
									<i class="font-icon font-icon-users-two"></i>
									<a href="#"> '.$userProfil['sekolah'].'</a>
								</p>' : '';
								?>
								<p class="line-with-icon">
									<i class="font-icon font-icon-user"></i>
									<?=ucfirst($_SESSION['lms_status']) == 'Guru' ? 'Tutor' : ucfirst($_SESSION['lms_status'])?>
								</p>
								<?php echo (isset($userProfil['sosmed']['facebook']) && !empty($userProfil['sosmed']['facebook'])) ? '
								<p class="line-with-icon">
									<i class="font-icon font-icon-facebook"></i>
									<a href="#"> '.$userProfil['sosmed']['facebook'].'</a>
								</p>' : '';
								?>
								<?php echo (isset($userProfil['sosmed']['website']) && !empty($userProfil['sosmed']['website'])) ? '
								<p class="line-with-icon">
									<i class="font-icon font-icon-earth"></i>
									<a href="#"> '.$userProfil['sosmed']['website'].'</a>
								</p>' : '';
								?>
								<p class="line-with-icon">
									<i class="font-icon font-icon-calend"></i>
									Bergabung <?=selisih_waktu($userProfil['date_created'])?>
								</p>
							</div>
						</section>

						<section class="box-typical">
							<header class="box-typical-header-sm bordered">
								Daftar Kelas
								<div class="btn-group" style='float: right;'>
									<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Aksi
									</button>
									<div class="dropdown-menu" style="margin-left: -100px">
										<?php
										if (strtolower($_SESSION['lms_status']) == 'guru') {
											echo '
												<a class="dropdown-item" href="#" data-toggle="modal" data-target="#addKelas"><span class="font-icon font-icon-plus"></span>Tambah Kelas</a>
				                                <a class="dropdown-item" href="kelola-kelas"><span class="font-icon font-icon-pencil"></span>Kelola Kelas</a>';
										}
										?>
		                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#joinKelas"><span class="font-icon font-icon-user"></span>Gabung Kelas</a>
									</div>
								</div>
							</header>
							<div class="box-typical-inner" id="listKelas">
								<p style="text-align: center;">
									Menunggu..
								</p>
							</div>
						</section>

					</aside><!--.profile-side-->
				</div>

				<div class="col-xl-9 col-lg-8">
					<section class="widget widget-tasks card-reverse">
						<header class="card-header">
							Daftar Kelas

						</header>
						<div class="widget-tasks-item">
							<table id="example" class="display table table-striped" cellspacing="0" width="100%">
								<thead style="display:none;">
									<tr>
										<th>Nama Kelas</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (count($listKelas) > 0) {
										foreach ($listKelas as $data) {
											if (isset($data['_id'])) {
												$menu	= '<div class="btn-group" style="float: right;">
																<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	Aksi
																</button>
																<div class="dropdown-menu dropdown-menu-right">
																	<a class="dropdown-item" onclick="removeCl(\''.$data['_id'].'\')">Hapus Kelas</a>
																</div>
															</div>';

												$menu1	= '<div class="btn-group" style="float: right;">
																<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	Aksi
																</button>
																<div class="dropdown-menu dropdown-menu-right">
																	<a class="dropdown-item" onclick="out(\''.$data['_id'].'\')">Keluar Kelas</a>
																</div>
															</div>';

												echo "	<tr>
															<td><a href='kelas?id=$data[_id]' title='$data[nama]' data-toggle='popover' data-placement='bottom' data-trigger='hover' data-content='Klik tautan diatas untuk melihat kelas tersebut.'><span class='user-name'>$data[nama]</span></a></td>
															<td width='70px;' class='shared'>".($data['hak'] == 1 ? $menu : $menu1)."</td>
														</tr>";
											}
										}
										// echo "<pre>";
										// print_r($listKelas);
										// echo "</pre>";
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

	<script>
		function out(ID){
      		swal({
      		  title: "Apakah anda yakin?",
      		  text: "Anda akan keluar dari kelas ini",
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
      				data: {"action": "removeAnggota", "ID": "<?=$_SESSION['lms_id']?>" , "kelas": ID},
      				success: function(res) {
						swal({
				            title: res.response,
				            text: res.message,
				            type: res.icon
				        }, function() {
				            window.location='./';
				        });
      				},
      				error: function () {
      					swal("Gagal!", "Data tidak terhapus!", "error");
      				}
      			});
      		});
      	}

		function removeCl(ID){
			swal({
			  title: "Apakah anda yakin?",
			  text: "Semua data akan terhapus dan tidak dapat dikembalikan lagi!",
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
					data: {"action": "rmv", "ID": ID, "u":"<?=$_SESSION['lms_id']?>"},
					success: function(res) {
						swal({
							title: res.response,
							text: res.message,
							type: res.icon
						}, function() {
							 // window.location = './';
							location.reload();
						});
					},
					error: function () {
						swal("Gagal!", "Data tidak terhapus!", "error");
					}
				});
			});
		}

		function removeKelas(ID){
      		swal({
      		  title: "Apakah anda yakin?",
      		  text: "Semua data yang ada akan hilang dan tidak dapat dikembalikan lagi!",
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
      				data: {"action": "removeKelas", "ID": "<?=$_SESSION['lms_id']?>" , "kelas": ID},
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
				url: 'url-API/Kelas/',
				data: {"action": "showList", "ID": "<?=$_SESSION['lms_id']?>"},
				success: function(res) {
					$('#listKelas').html('');
					$('#jmlKelas').html(res.data.length);
					if(res.data.length > 0){
						res.data.forEach(function(entry) {
						// for(i=0; i<=res.data.length; i++){
							// $('#listKelas').append('<p class="line-with-icon">'+
							// 		'<i class="font-icon font-icon-folder"></i>'+
							// 		'<a title="'+entry.nama+' sebagai '+entry.hak+'" href="kelas?id='+entry._id.$id+'">'+entry.nama+' <b>'+entry.hak+'</b></a>'+
							// 	'</p>');
							var hak = 'Warga Belajar';
							if(entry.hak == ' (Administrator Kelas)'){
								hak = 'Administrator Kelas';
							}
							if(entry.hak == ' (Guru Mata Pelajaran)'){
								hak = 'Tutor (Pengampu Mata Pelajaran)';
							}
							if(entry.hak == ' (Tutor)'){
								hak = 'Tutor (Pendamping)';
							}
							$('#listKelas').append('<p class="line-with-icon">'+
									'<i class="font-icon font-icon-folder"></i>'+
									'<a title="'+entry.nama+' sebagai '+hak+'" href="kelas?id='+entry._id.$id+'">'+entry.nama+'</a><br><sup><b>-- '+hak+' --</b></sup>'+
								'</p>');
						// }
						});
					}else{
						$('#listKelas').append('<p style="text-align:center;">'+
									'Belum ada Kelas'+
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
		// error gara-gara  'sudo /edx/bin/update edx-platform master'
	</script>

	<script src="assets/js/app.js"></script>
<?php
	require('includes/footer-bottom.php');
?>
