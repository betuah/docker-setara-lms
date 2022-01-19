<?php
require("assets/thumbs/system/recovery");
require("includes/header-top.php");
require("includes/header-menu.php");

$userClass	= new User();
$profilClass = new Profile();
$sekolahClass = new Sekolah();
$ProvkotClass = new Provkot();

if (isset($_SESSION['lms_id'])) {
	$userProfil	= $userClass->GetData("$_SESSION[lms_id]");
    $infoSekolah = $sekolahClass->getInfoSekolah($userProfil['sekolah']);
	if (isset($userProfil['kota']) || !empty($userProfil['kota'])) {
		$getKota = $ProvkotClass->getKota((int)$userProfil['kota']);
		$asalKota = $getKota['nama_kab_kot'];
	}
}

if(isset($_POST['inputSekolah'])){
	$sekolah = $_POST['update-sekolah'];
	$email 	 = $_POST['update-email'];
	$rest = $profilClass->updateInfo($sekolah, $email, $_SESSION['lms_id']);
	if ($rest['status'] == "Success") {
		echo	"<script>
					swal({
						title: 'Berhasil!',
						text: '$rest[message]',
						type: 'success'
					}, function() {
						 window.location = './';
					});
				</script>";
		// echo "<script>alert('".$rest['message']."'); document.location='kelas.php?id=".$rest['IDKelas']."'</script>";
	}else{
		echo	"<script>
					swal({
						title: 'Maaf!',
						text: '$rest[message]',
						type: 'error'
					}, function() {
						 location.reload();
					});
				</script>";
	}
}

if(isset($_POST['updateSpnf'])){
	$sekolah = $_POST['spnf'];
	$rest = $profilClass->updateInfoSekolah($sekolah, $_SESSION['lms_id']);
	if ($rest['status'] == "Success") {
		echo	"<script>
					swal({
						title: 'Berhasil!',
						text: '$rest[message]',
						type: 'success'
					}, function() {
						 window.location = './';
					});
				</script>";
		// echo "<script>alert('".$rest['message']."'); document.location='kelas.php?id=".$rest['IDKelas']."'</script>";
	}else{
		echo	"<script>
					swal({
						title: 'Maaf!',
						text: '$rest[message]',
						type: 'error'
					}, function() {
						 location.reload();
					});
				</script>";
	}
}

?>
<script type="text/javascript">document.title = "Halaman Utama - seTARA daring";</script>
<link rel="stylesheet" href="assets/css/lib/bootstrap-select/ajax-bootstrap-select.css"/>

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
												<p><?=($_SESSION['lms_username']=='direktorat'?'Super Admin':($_SESSION['lms_status']=='guru'?'Tutor': ($_SESSION['lms_status']=='siswa'? 'Warga Belajar' : ($_SESSION['lms_status']=='kepsek'? 'Kepala Sekolah' : ucfirst($_SESSION['lms_status'])))))?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<button type="button" class="change-cover" style="display: none;">
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
							<button type="button" id="ohyeah" class="btn btn-rounded"><?=$_SESSION['lms_status'] == 'guru' ? '<span data-toggle="modal" data-target="#joinKelas"><i class="font-icon font-icon-user"></i> Gabung Kelas</span>' : '<span data-toggle="modal" data-target="#joinKelas"><i class="font-icon font-icon-user"></i> Gabung Kelas</span>'; ?></button>

						</section>
						<?php
						if(strtolower($_SESSION['lms_status']) == 'siswa'){
						echo '
							<section class="box-typical profile-side-stat">
								<div class="tbl">
									<div class="tbl-row">
										<div class="tbl-cell">
											<span class="number" id="jmlKelas">0</span>
											kelas yang diikuti										</div>
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
								<?php echo (isset($infoSekolah) && !empty($infoSekolah)) ? '
								<p class="line-with-icon">
									<i class="font-icon font-icon-learn"></i>
									<a href="#"> '.$infoSekolah['nama_sekolah_induk'].'</a>
								</p>' : '';
								?>
								<p class="line-with-icon">
									<i class="font-icon font-icon-user"></i>
									<?=($_SESSION['lms_username']=='direktorat'?'Super Admin':($_SESSION['lms_status']=='guru'?'Tutor': ($_SESSION['lms_status']=='siswa'? 'Warga Belajar' : ($_SESSION['lms_status']=='kepsek'? 'Kepala Sekolah' : ucfirst($_SESSION['lms_status'])))))?>
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
												<a class="dropdown-item" href="#" data-toggle="modal" data-target="#addKelas"><span class="font-icon font-icon-plus"></span>Tambah Kelas</a>';
										}
										?>
										<a class="dropdown-item" href="kelola-kelas"><span class="font-icon font-icon-pencil"></span>Kelola Kelas</a>
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
					<section class="tabs-section">

						<div class="tab-content no-styled profile-tabs">

							<?php
								$listPosting	= $kelasClass->postingSeluruh($_SESSION['lms_id'], isset($_GET['halaman']) ? $_GET['halaman'] : 1);

								if ($listPosting['count'] > 0) {
									foreach ($listPosting['data'] as $posting) {
										$image		= empty($posting['foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='media/Assets/foto/".$posting['foto']."' style='max-width: 75px; max-height: 75px;' />" ;
										echo '	<article class="box-typical profile-post">
													<div class="profile-post-header">
														<div class="user-card-row">
															<div class="tbl-row">
																<div class="tbl-cell tbl-cell-photo">
																	<a href="#">'.$image.'</a>
																</div>
																<div class="tbl-cell">
																	<div class="user-card-row-name"><a href="#"><b>'.$posting['user'].'</b></a>&nbsp; <i class="fa fa-caret-right"></i> &nbsp;<a href="kelas?id='.$posting['id_kelas'].'"><b>'.$posting['kelas'].'</b></a></div>
																	<div class="color-blue-grey-lighter"><small>'.$posting['user_akses'].' - '.selisih_waktu($posting['date_created']).'</small></div>
																</div>
															</div>
														</div>';
														if ("$_SESSION[lms_id]" == $posting['creator']) {
														echo '		<a class="shared" onclick="removePost(\''.$posting['_id'].'\')" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus Kiriman yang sudah dibuat.">
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
								}else {
									echo '	<article class="box-typical profile-post">
												<div class="profile-post-content">
													<p align="center">
													 Belum ada Postingan saat ini.
													</p>
												</div>
											</article>';
								}
							?>

						</div><!--.tab-content-->
					</section><!--.tabs-section-->
				</div>
			</div><!--.row-->
		</div><!--.container-fluid-->
	</div><!--.page-content-->

	<div class="modal fade"
		 id="inputSekolah"
		 role="dialog"
		 aria-labelledby="inputSekolahLabel"
		 aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form method="POST">
				<div class="modal-header">
					<h4 class="modal-title" id="inputSekolahLabel">Pengaturan</h4>
				</div>
				<div class="modal-body">
					<p>
						Untuk melanjutkan, silahkan lengkapi isian dibawah ini!
					</p>
					<div class="form-group row">
						<label for="ajax-select" class="col-md-3 form-control-label">Sekolah</label>
						<div class="col-md-9">
							<!-- <select name="update-sekolah" id="ajax-select" class="selectpicker with-ajax form-control" data-live-search="true" required></select> -->
							<input type="text" name="update-sekolah" class="form-control" autocomplete="on" id="autocomplete"/>
						</div>
					</div>
					<div class="form-group row">
						<label for="email" class="col-md-3 form-control-label">Email Aktif</label>
						<div class="col-md-9">
							<input name="update-email" type="email" id="update-email" class="form-control" value="<?=$userProfil['email']?>" required>
							<small class="text-muted" id="emailNotif">Silahkan gunakan email yang aktif.</small>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="inputSekolah" value="send" class="btn btn-rounded btn-primary">Simpan</button>
				</div>
				</form>
			</div>
		</div>
	</div><!--.modal-->

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
					<h4 class="modal-title" id="updateSekolahLabel">Pengaturan Profil</h4>
				</div>
				<div class="modal-body">
					<p>
                        Untuk melanjutkan, silahkan lengkapi isian dibawah ini!
					</p>
					<div class="form-group row">
						<label for="tentang" class="col-md-3 form-control-label">Sekolah/Instansi</label>
						<div class="col-md-9">
							<select name="spnf" id="ajax-select" class="selectpicker with-ajax form-control" data-live-search="true" required>
								<?php
									if (!empty($infoSekolah)) {
										echo "<option value='$infoSekolah[_id]'>$infoSekolah[nama_sekolah_induk]</option>";
									}
								?>
							</select>
							<small class="text-muted" id="emailNotif">Jika nama satuan pendidikan anda belum terdaftar, silakan Kepala atau Perwakilan Satuan Pendidikan untuk mengisi form pengajuan melalui <a href="http://bit.ly/pengajuan_daring" target="_blank">http://bit.ly/pengajuan_daring</a>.</small>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-rounded btn-primary" name="updateSpnf" value="send" >Simpan</button>
				</div>
				</form>
			</div>
		</div>
	</div><!--.modal-->

<?php
	require('includes/footer-top.php');
?>
	<script type="text/javascript" src="assets/js/lib/jquery-autocomplete/typeahead.js"></script>
	<script type="text/javascript" src="assets/js/lib/bootstrap-select/ajax-bootstrap-select.js"></script>
	<script type="text/javascript">
		$('#autocomplete').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "includes/option-sekolah.php",
					data: 'q=' + query,
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							return item;
  						}));
                    }
                });
            }
        });

		var _changeInterval = null;
		$("#update-email").keyup(function() {
		  	clearInterval(_changeInterval)
		  	_changeInterval = setInterval(function() {
				valEmail($("#update-email").val());
		    // Typing finished, now you can Do whatever after 2 sec
		    clearInterval(_changeInterval)
		  }, 1000);

		});

		$("#update-email").keypress(function() {
		  	clearInterval(_changeInterval)
		  	_changeInterval = setInterval(function() {
				valEmail($("#update-email").val());
		    // Typing finished, now you can Do whatever after 2 sec
		    clearInterval(_changeInterval)
		  }, 1000);

		});

		function valEmail(email){
            $.ajax({
                type: "POST",
                url: "url-API/Pengguna/",
                data: { action: 'valEmail', e: email, u: "<?=$_SESSION['lms_id']?>" },
                success: function(res) {
                	if (res.count > 0){
                		$("#emailNotif").html('<font color="red"><b>Email ini sudah digunakan pada akun lain!</b></font>');
                		$("button[name=inputSekolah]").prop("disabled", true);
                	}else{
                		$("button[name=inputSekolah]").prop("disabled", false);
                		$("#emailNotif").html('Email ini dapat digunakan.');
                	}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					alert(textStatus);
				}
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
	            emptyTitle: 'Pilih Asal Satuan Pendidikan'
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

		$(document).ready(function() {
			<?php
				if ($_SESSION['lms_update'] != "2021.1") {
			?>
					$('#updateSekolah').modal({
						backdrop: 'static',
						keyboard: false
					});
			<?php
				}
			?>

			if ($("#update-email").val() == '') {
				$("button[name=inputSekolah]").prop("disabled", true);
			}

			$.ajax({
				type: 'POST',
				url: 'url-API/Kelas/',
				data: {"action": "showList", "ID": "<?=$_SESSION['lms_id']?>"},
				success: function(res) {
					$('#listKelas').html('');
					$('#jmlKelas').html(res.data.length);
					if(res.data.length > 0){
						res.data.forEach(function(entry) {
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
