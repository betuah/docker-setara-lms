<?php
    require("includes/header-top.php");
    require("includes/header-menu.php");

    $userClass	= new User();
    $userProfil	= $userClass->GetData($_SESSION['lms_id']);
    $classProfile = new Profile();
    $FuncProfile = $classProfile->GetData($_SESSION['lms_id']);
    $ProvkotClass = new Provkot();
    $listProvinsi = $ProvkotClass->getListProv();
    $sekolahClass = new Sekolah();
    if (isset($FuncProfile['kota']) && !empty($FuncProfile['kota'])) {
        $getKota = $ProvkotClass->getKota((int)$FuncProfile['kota']);
        $asalKota = $getKota['nama_kab_kot'];
    }

	if (isset($_POST['simpan_profil'])) {
		# code...

		$id_profile 	= $_SESSION['lms_id'];
		$username 		= $FuncProfile['username'];
		$password 		= $FuncProfile['password'];
		// $username 		= "fai19";
		$nama 			= $_POST['nama'];
		$email 			= $_POST['email'];
		$jenis_kelamin 	= $_POST['jenis_kelamin'];
		$sekolah 		= $_POST['instansi'];
		$status 		= $FuncProfile['status'];
		$prov 			= $_POST['provinsi'];
		$kota 			= $_POST['kota'];
		// $status 		= "guru";
		if ($_FILES['foto']['name'] != NULL) {
			# code...
			$foto 		= $_FILES['foto']['name'];
			$foto_size 	= $_FILES['foto']['size'];
			$foto_tmp 	= $_FILES['foto']['tmp_name'];
			$foto_ext	= pathinfo($foto,PATHINFO_EXTENSION);
			$foto_lama	= $FuncProfile['foto'];

			// echo '<script>alert("'.$foto_size.'");</script>';

			$classProfile->UpdateProfileFoto($id_profile, $password,$username,$nama,$email,$jenis_kelamin,$sekolah,$status,$foto,$foto_size,$foto_tmp,$foto_ext,$foto_lama,$prov,$kota);

		}else{
			$foto = $FuncProfile['foto'];
			$classProfile->UpdateProfile($id_profile, $password,$username,$nama,$email,$jenis_kelamin,$sekolah,$status,$foto,$prov,$kota);
		}

	}

	if (isset($_POST['simpan_sosmed'])) {
		# code...
		$id_profile 	= $_SESSION['lms_id'];
		$website 		= $_POST['website'];
		$facebook		= $_POST['facebook'];
		$linkedin 		= $_POST['linkedin'];
		$twitter 		= $_POST['twitter'];

		$classProfile->UpdateSosmed($id_profile, $userProfil['sekolah'], $website,$facebook,$linkedin,$twitter);
	}

	if (isset($_POST['simpan_password'])) {
		# code...
		$id_profile 	= $_SESSION['lms_id'];
		$username 		= $FuncProfile['username'];
		$password 		= $_POST['p_lama'];
		$password_baru 	= $_POST['p_baru'];
		$password_confirm= $_POST['p_baru2'];


		$nama 			= $FuncProfile['nama'];
		$email 			= $FuncProfile['email'];
		$jenis_kelamin 	= $FuncProfile['jk'];
		$sekolah 		= $FuncProfile['sekolah'];
		$status 		= $FuncProfile['status'];
		$foto 			= $FuncProfile['foto'];

		$classProfile->CheckPassword($id_profile, $password, $password_baru, $password_confirm,$username,$nama,$email,$jenis_kelamin,$sekolah,$status,$foto);
	}

?>
<script type="text/javascript">document.title = "Pengaturan Profil - seTARA daring";</script>
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
				                                <a class="dropdown-item" href="#"><span class="font-icon font-icon-pencil"></span>Kelola Kelas</a>';
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

				<div <div class="col-lg-9 col-lg-pull-6 col-md-6">
				<!-- Mulai Buku Content -->
				<section class="tabs-section">
				<div class="tabs-section-nav tabs-section-nav-inline">
					<ul class="nav" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" href="#tab-1" role="tab" data-toggle="tab" aria-expanded="false">
								Profil
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#tab-2" role="tab" data-toggle="tab" aria-expanded="false">
								Sosial Media
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#tab-3" role="tab" data-toggle="tab" aria-expanded="false">
								Kata Sandi
							</a>
						</li>

					</ul>
				</div><!--.tabs-section-nav-->

				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="tab-1" aria-expanded="false">
						<h5 class="m-t-lg with-border">Profil Anda</h5>
						<form method="POST" action="" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-offset-3 col-md-6 col-md-offset-3">
									<fieldset class="form-group">
										<div id="image-preview" class="profile-card-photo" style="text-align: center;">
											<img id="image-upload" class="m-b" src="media/Assets/foto/<?php if ($FuncProfile['foto'] != NULL) {echo $FuncProfile['foto'];}else{echo "no_picture.png";} ?>" alt="" style="max-width: 250px; max-height: 250px;" />
										</div>
										<input type="file" name="foto" class="form-control" id="exampleFoto" placeholder="Nama Lengkap" >

									</fieldset>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									 <fieldset class="form-group">
										<label class="form-label semibold" for="exampleInput">Nama Lengkap</label>
										<input type="text" name="nama" class="form-control" id="exampleInput" placeholder="Nama Lengkap" value="<?php echo $FuncProfile['nama'];?>">
									</fieldset>
								</div>
								<div class="col-lg-6">
									<fieldset class="form-group">
										<label class="form-label semibold" for="exampleInputEmail1">Email</label>
										<input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" value="<?php echo $FuncProfile['email'];?>" >
										<small class="text-muted" id="emailNotif">Kita tidak akan menyebarkan surel anda kepada siapapun.</small>
									</fieldset>
								</div>

							</div>
							<div class="row">
								<div class="col-lg-6">
									<fieldset class="form-group">
										<label class="form-label semibold" for="exampleSelect">Jenis Kelamin</label>
										<select id="exampleSelect" name="jenis_kelamin" class="form-control select2">
											<option value="">Pilih Salah Satu </option>
											<option value="Laki-laki" <?php if ($FuncProfile['jk'] == "Laki-laki") { echo "selected";}?>>Laki-laki</option>
											<option value="Perempuan" <?php if ($FuncProfile['jk'] == "Perempuan") { echo "selected";}?>>Perempuan</option>
										</select>

									</fieldset>
								</div>
								<div class="col-lg-6">
									<fieldset class="form-group">
										<label class="form-label semibold" for="exampleInputInstansi">Instansi/Sekolah</label>
										<!-- <input type="text" id="autocomplete" autocomplete="off" name="instansi" class="form-control" value="<?php echo $FuncProfile['sekolah'];?>"  placeholder="Nama Instansi/Sekolah" > -->
                                        <select name="instansi" id="ajax-select" class="selectpicker with-ajax form-control" data-live-search="true" required>
                                            <?php
                                                if ($FuncProfile['sekolah'] != '') {
                                                    $infoSekolah = $sekolahClass->getInfoSekolah($FuncProfile['sekolah']);
                                                    echo "<option value='$infoSekolah[_id]' selected>$infoSekolah[nama_sekolah_induk]</option>";
                                                }
                                            ?>
                                        </select>
									</fieldset>
								</div>

							</div>
							<div class="row">
								<div class="col-lg-6">
									<fieldset class="form-group">
										<label class="form-label semibold" for="prov">Provinsi</label>
										<select id="prov" name="provinsi" class="form-control select2">
											<option value="">Pilih Provinsi</option>
											<?php
											foreach ($listProvinsi as $data) {?>
											<option value="<?=$data['id_provinsi']?>" <?php if (isset($FuncProfile['provinsi'])){ if ($data['id_provinsi'] == $FuncProfile['provinsi']) { echo "selected";} } ?>> <?=$data['nama_provinsi']?></option>
											<?php } ?>
										</select>

									</fieldset>
								</div>
								<div class="col-lg-6">
									<fieldset class="form-group">
										<label class="form-label semibold" for="kota">Kabupaten/Kota</label>
										<select id="kota" name="kota" class="form-control select2">
											<option value="">Pilih Provinsi dahulu</option>
											<?php
												if (isset($FuncProfile['kota'])) {
													$getKota = $ProvkotClass->getKota((int)$FuncProfile['kota']);
													echo '<option value="'.$getKota['id_kab_kot'].'" '; if (isset($FuncProfile['kota'])){ if ($getKota['id_kab_kot'] == $FuncProfile['kota']) { echo "selected";} } echo '>'.$getKota['nama_kab_kot'].'</option>';
												}
											?>
										</select>
									</fieldset>
								</div>

							</div>
							<button type="submit" name="simpan_profil" class="btn">Simpan</button>
						</form>
					</div><!--.tab-pane-->
					<div role="tabpanel" class="tab-pane fade" id="tab-2" aria-expanded="false">
					<h5 class="with-border m-t-lg">Sosial Media</h5>
					<form action="" method="POST">
						<div class="form-group">
							<label class="form-label" for="hide-show-password">Website</label>
							<div class="input-append input-group"><input type="text" name="website" class="form-control" value="<?php echo $FuncProfile['sosmed']['website'];?>" placeholder="www.example.com"></div>
						</div>
						<div class="form-group">
							<label class="form-label" for="hide-show-password">Facebook</label>
							<div class="input-append input-group"><input type="text" name="facebook" class="form-control" value="<?php echo $FuncProfile['sosmed']['facebook'];?>" placeholder="fb.me/example"></div>
						</div>
						<div class="form-group">
							<label class="form-label" for="hide-show-password">Linkedin</label>
							<div class="input-append input-group"><input type="text" name="linkedin" class="form-control" value="<?php echo $FuncProfile['sosmed']['linkedin'];?>" placeholder="linkedin.com/in/example"></div>
						</div>
						<div class="form-group">
							<label class="form-label" for="hide-show-password">Twitter</label>
							<div class="input-append input-group"><input type="text" name="twitter" class="form-control" value="<?php echo $FuncProfile['sosmed']['twitter'];?>" placeholder="twitter.com/example"></div>
						</div>
						<button type="submit" name="simpan_sosmed" class="btn">Simpan</button>
					</form>
					</div><!--.tab-Ubah Password-->
					<div role="tabpanel" class="tab-pane fade" id="tab-3" aria-expanded="false">
					<form action="" method="POST">
						<h5 class="with-border m-t-lg">Ubah Kata Sandi</h5>
						<div class="form-group">
							<label class="form-label" for="hide-show-password">Kata sandi Lama</label>
							<div class="input-append input-group"><input type="password" name="p_lama" class="form-control" placeholder="Kata Sandi Lama"></div>
						</div>
						<div class="form-group">
							<label class="form-label" for="hide-show-password">Kata Sandi Baru</label>
							<div class="input-append input-group"><input type="password" name="p_baru" class="form-control" placeholder="Kata Sandi Baru"></div>
						</div>
						<div class="form-group">
							<label class="form-label" for="hide-show-password">Ulangi Kata Sandi Baru</label>
							<div class="input-append input-group"><input type="password" name="p_baru2" class="form-control" placeholder="Ulangi Kata Sandi Baru"></div>
						</div>
						<button type="submit" name="simpan_password" class="btn">Simpan</button>
					</form>
					</div><!--.tab-pane-->
				</div><!--.tab-content-->
			</section>
				<!-- Selesai Buku Content -->
				</div>
			</div><!--.row-->
		</div><!--.container-fluid-->
	</div><!--.page-content-->

<?php
	require('includes/footer-top.php');
?>
	<script type="text/javascript" src="assets/js/lib/jquery-autocomplete/typeahead.js"></script>
    <script type="text/javascript" src="assets/js/lib/bootstrap-select/ajax-bootstrap-select.js"></script>
	<script>
        $(document).ready(function() {
            $("#prov").change(function( event ) {
                // alert( ""+ $(this).val());
                $.ajax({
                    type: "POST",
                    url: "includes/option-kota.php",
                    data: { id: $(this).val() }
                }).done(function( dt ) {
                    $( "#kota" ).html( dt );
                });
            });

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
        });

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

		function readURL(input) {
		  	if (input.files && input.files[0]) {
		    	var reader = new FileReader();

		    	reader.onload = function(e) {
		      		$('#image-upload').attr('src', e.target.result);
		    	}

		    	reader.readAsDataURL(input.files[0]); // convert to base64 string
		  	}
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

		$(document).ready(function() {
			$("#exampleFoto").change(function() {
			  	readURL(this);
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
							// 		'<a title="'+entry.nama+' sebagai '+entry.hak+'" href="kelas.php?id='+entry._id.$id+'">'+entry.nama+'<b>'+entry.hak+'</b></a>'+
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
		var _changeInterval = null;
		$("#exampleInputEmail1").keyup(function() {
		  	clearInterval(_changeInterval)
		  	_changeInterval = setInterval(function() {
				valEmail($("#exampleInputEmail1").val());
		    // Typing finished, now you can Do whatever after 2 sec
		    clearInterval(_changeInterval)
		  }, 1000);

		});

		$("#exampleInputEmail1").keypress(function() {
		  	clearInterval(_changeInterval)
		  	_changeInterval = setInterval(function() {
				valEmail($("#exampleInputEmail1").val());
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
                		$("button[name=simpan_profil]").prop("disabled", true);
                	}else{
                		$("button[name=simpan_profil]").prop("disabled", false);
                		$("#emailNotif").html('Kita tidak akan menyebarkan surel anda kepada siapapun.');
                	}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					alert(textStatus);
				}
            });
		}
	</script>

	<script src="assets/js/app.js"></script>
<?php
	require('includes/footer-bottom.php');
?>
