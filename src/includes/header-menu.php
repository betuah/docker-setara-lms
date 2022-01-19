</head>
<?php
$kelasClass = new Kelas();
$classProfile = new Profile();
$quizClass = New Quiz();
$FuncProfile = $classProfile->GetData($_SESSION['lms_id']);

if(isset($_POST['addKelas'])){
	$nama = mysql_escape_string($_POST['namakelas']);
	$rest = $kelasClass->addKelas($nama, $_SESSION['lms_id'], $FuncProfile['sekolah']);
	if ($rest['status'] == "Success") {
		echo	"<script>
					swal({
						title: 'Berhasil!',
						text: '$rest[message]',
						type: 'success'
					}, function() {
						 window.location = 'kelas?id=".$rest['IDKelas']."';
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
                        window.location = window.location.href;
					});
				</script>";
	}
}

if(isset($_POST['joinKelas'])){
	$kode = mysql_escape_string($_POST['kodekelas']);
	$rest = $kelasClass->joinKelas($kode, $_SESSION['lms_id'], $FuncProfile['sekolah'], $_SESSION['lms_status']);
	if ($rest['status'] == "Success") {
		echo	"<script>
					swal({
						title: 'Berhasil!',
						text: '".$rest['message']."',
						type: 'success'
					}, function() {
						 window.location = 'kelas?id=".$rest['IDKelas']."';
					});
				</script>";
	}else{
		echo	"<script>
					swal({
						title: 'Maaf!',
						text: '".$rest['message']."',
						type: 'error'
					}, function() {
                        window.location = window.location.href;
					});
				</script>";
	}
}

if(isset($_POST['joinUjian'])){
	$kode = mysql_escape_string($_POST['kodeUjian']);
	$rest = $quizClass->getKelasByQuiz($_SESSION['lms_id'], $kode);

	if ($rest['status'] == "Success") {
        if($rest['bentuk'] == 1){
            $ujian_link = "window.location = 'create-ujian?id=".$rest['ujian']."'";
        }else{
            $ujian_link = "window.location = 'create-uji?id=".$rest['ujian']."'";
        }

		echo	"<script>
					swal({
						title: 'Kode ujian ditemukan!',
						text: '".$rest['message']."',
						type: 'success'
					}, function() {
                        ".$ujian_link."
					});
				</script>";
	}else{
		echo	"<script>
					swal({
						title: 'Maaf!',
						text: '".$rest['message']."',
						type: 'error'
					}, function() {
                        window.location = window.location.href;
					});
				</script>";
	}
}
?>
<body>
	<header class="site-header">
	    <div class="container-fluid">

	        <a href="<?=base_url?>" class="site-logo">
	            <img class="hidden-md-down" src="assets/img/<?=(($_SERVER['SERVER_NAME'] == 'setara.kemdikbud.go.id' OR $_SERVER['SERVER_NAME'] == 'setara.seamolec.org' OR $_SERVER['SERVER_ADDR'] == '10.10.10.25') ? 'front/seTARAdaring.png':'front/logo.png')?>" alt="">
	            <img class="hidden-lg-up" src="assets/img/logo-small.png" alt="">
	        </a>

	        <div class="site-header-content">
	            <div class="site-header-content-in">
	                <div class="site-header-shown">
	                    <!-- <div class="dropdown dropdown-notification notif">
	                        <a href="#"
	                           class="header-alarm dropdown-toggle active"
	                           id="dd-notification"
	                           data-toggle="dropdown"
	                           aria-haspopup="true"
	                           aria-expanded="false">
	                            <i class="font-icon-alarm"></i>
	                        </a>
	                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-notif" aria-labelledby="dd-notification">
	                            <div class="dropdown-menu-notif-header">
	                                Notifications
	                                <span class="label label-pill label-danger">4</span>
	                            </div>
	                            <div class="dropdown-menu-notif-list">
	                                <div class="dropdown-menu-notif-item">
	                                    <div class="photo">
	                                        <img src="assets/img/photo-64-1.jpg" alt="">
	                                    </div>
	                                    <div class="dot"></div>
	                                    <a href="#">Morgan</a> was bothering about something
	                                    <div class="color-blue-grey-lighter">7 hours ago</div>
	                                </div>
	                                <div class="dropdown-menu-notif-item">
	                                    <div class="photo">
	                                        <img src="assets/img/photo-64-2.jpg" alt="">
	                                    </div>
	                                    <div class="dot"></div>
	                                    <a href="#">Lioneli</a> had commented on this <a href="#">Super Important Thing</a>
	                                    <div class="color-blue-grey-lighter">7 hours ago</div>
	                                </div>
	                                <div class="dropdown-menu-notif-item">
	                                    <div class="photo">
	                                        <img src="assets/img/photo-64-3.jpg" alt="">
	                                    </div>
	                                    <div class="dot"></div>
	                                    <a href="#">Xavier</a> had commented on the <a href="#">Movie title</a>
	                                    <div class="color-blue-grey-lighter">7 hours ago</div>
	                                </div>
	                                <div class="dropdown-menu-notif-item">
	                                    <div class="photo">
	                                        <img src="assets/img/photo-64-4.jpg" alt="">
	                                    </div>
	                                    <a href="#">Lionely</a> wants to go to <a href="#">Cinema</a> with you to see <a href="#">This Movie</a>
	                                    <div class="color-blue-grey-lighter">7 hours ago</div>
	                                </div>
	                            </div>
	                            <div class="dropdown-menu-notif-more">
	                                <a href="#">See more</a>
	                            </div>
	                        </div>
	                    </div> -->

	                    <!-- <div class="dropdown dropdown-notification messages">
	                        <a href="#"
	                           class="header-alarm dropdown-toggle active"
	                           id="dd-messages"
	                           data-toggle="dropdown"
	                           aria-haspopup="true"
	                           aria-expanded="false">
	                            <i class="font-icon-mail"></i>
	                        </a>
	                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-messages" aria-labelledby="dd-messages">
	                            <div class="dropdown-menu-messages-header">
	                                <ul class="nav" role="tablist">
	                                    <li class="nav-item">
	                                        <a class="nav-link active"
	                                           data-toggle="tab"
	                                           href="#tab-incoming"
	                                           role="tab">
	                                            Inbox
	                                            <span class="label label-pill label-danger">8</span>
	                                        </a>
	                                    </li>
	                                    <li class="nav-item">
	                                        <a class="nav-link"
	                                           data-toggle="tab"
	                                           href="#tab-outgoing"
	                                           role="tab">Outbox</a>
	                                    </li>
	                                </ul>
	                                <!--<button type="button" class="create">
	                                    <i class="font-icon font-icon-pen-square"></i>
	                                </button>--
	                            </div>
	                            <div class="tab-content">
	                                <div class="tab-pane active" id="tab-incoming" role="tabpanel">
	                                    <div class="dropdown-menu-messages-list">
	                                        <a href="#" class="mess-item">
	                                            <span class="avatar-preview avatar-preview-32"><img src="assets/img/photo-64-2.jpg" alt=""></span>
	                                            <span class="mess-item-name">Tim Collins</span>
	                                            <span class="mess-item-txt">Morgan was bothering about something!</span>
	                                        </a>
	                                        <a href="#" class="mess-item">
	                                            <span class="avatar-preview avatar-preview-32"><img src="assets/img/avatar-2-64.png" alt=""></span>
	                                            <span class="mess-item-name">Christian Burton</span>
	                                            <span class="mess-item-txt">Morgan was bothering about something! Morgan was bothering about something.</span>
	                                        </a>
	                                        <a href="#" class="mess-item">
	                                            <span class="avatar-preview avatar-preview-32"><img src="assets/img/photo-64-2.jpg" alt=""></span>
	                                            <span class="mess-item-name">Tim Collins</span>
	                                            <span class="mess-item-txt">Morgan was bothering about something!</span>
	                                        </a>
	                                        <a href="#" class="mess-item">
	                                            <span class="avatar-preview avatar-preview-32"><img src="assets/img/avatar-2-64.png" alt=""></span>
	                                            <span class="mess-item-name">Christian Burton</span>
	                                            <span class="mess-item-txt">Morgan was bothering about something...</span>
	                                        </a>
	                                    </div>
	                                </div>
	                                <div class="tab-pane" id="tab-outgoing" role="tabpanel">
	                                    <div class="dropdown-menu-messages-list">
	                                        <a href="#" class="mess-item">
	                                            <span class="avatar-preview avatar-preview-32"><img src="assets/img/avatar-2-64.png" alt=""></span>
	                                            <span class="mess-item-name">Christian Burton</span>
	                                            <span class="mess-item-txt">Morgan was bothering about something! Morgan was bothering about something...</span>
	                                        </a>
	                                        <a href="#" class="mess-item">
	                                            <span class="avatar-preview avatar-preview-32"><img src="assets/img/photo-64-2.jpg" alt=""></span>
	                                            <span class="mess-item-name">Tim Collins</span>
	                                            <span class="mess-item-txt">Morgan was bothering about something! Morgan was bothering about something.</span>
	                                        </a>
	                                        <a href="#" class="mess-item">
	                                            <span class="avatar-preview avatar-preview-32"><img src="assets/img/avatar-2-64.png" alt=""></span>
	                                            <span class="mess-item-name">Christian Burtons</span>
	                                            <span class="mess-item-txt">Morgan was bothering about something!</span>
	                                        </a>
	                                        <a href="#" class="mess-item">
	                                            <span class="avatar-preview avatar-preview-32"><img src="assets/img/photo-64-2.jpg" alt=""></span>
	                                            <span class="mess-item-name">Tim Collins</span>
	                                            <span class="mess-item-txt">Morgan was bothering about something!</span>
	                                        </a>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="dropdown-menu-notif-more">
	                                <a href="#">See more</a>
	                            </div>
	                        </div>
	                    </div> -->

	                    <div class="dropdown user-menu">
	                        <button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                            <img src="media/Assets/foto/<?php if ($FuncProfile['foto'] != NULL) {echo $FuncProfile['foto'];}else{echo "no_picture.png";} ?>" alt="">
	                        </button>
	                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
	                            <a class="dropdown-item" href="setting-profile"><span class="font-icon glyphicon glyphicon-cog"></span>Pengaturan</a>
	                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-question-sign"></span>Bantuan</a>
	                            <div class="dropdown-divider"></div>
	                            <a class="dropdown-item" onclick="signOut()"><span class="font-icon glyphicon glyphicon-log-out"></span>Keluar</a>
	                        </div>
	                    </div>

	                    <button type="button" class="burger-right">
	                        <i class="font-icon-menu-addl"></i>
	                    </button>
	                </div><!--.site-header-shown-->

	                <div class="mobile-menu-right-overlay"></div>
	                <div class="site-header-collapsed">
					<!-- HEADER MENU -->
	                    <div class="site-header-collapsed-in">
							<?php
							if($_SESSION['admin_status'] == 'superadmin' || $_SESSION['lms_status'] == 'superadmin' || $_SESSION['lms_status'] == 'admin' || $_SESSION['lms_status'] == 'pengelola' || $_SESSION['lms_status'] == 'pengawas' || $_SESSION['lms_status'] == 'kepsek'){
							 ?>
							 <div class="dropdown dropdown-typical">
								<a href="manage" target="_blank" class="dropdown-toggle no-arr">
									<span class="font-icon font-icon-speed"></span> Panel Admin
									<span class="lbl"></span>
								</a>
							</div>
							 <?php
							}
							?>
							<div class="dropdown dropdown-typical">
							   <a href="<?=base_url?>" class="dropdown-toggle no-arr">
								   <span class="font-icon font-icon-home"></span> Beranda
								   <span class="lbl"></span>
							   </a>
						   </div>
	                        <div class="dropdown dropdown-typical">
	                            <a class="dropdown-toggle" id="dd-header-marketing" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                                <span class="font-icon font-icon-users"></span> Kelas
	                                <span class="lbl"></span>
	                            </a>
								<?php
								if (isset($_SESSION['lms_status'])) {
									# code...
									if($_SESSION['lms_status'] == "guru" || $_SESSION['lms_status'] == 'admin' || $_SESSION['lms_status'] == 'pengelola'){
										echo '<div class="dropdown-menu" aria-labelledby="dd-header-marketing">
			                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addKelas"><span class="font-icon font-icon-plus"></span>Tambah Kelas</a>
			                                <a class="dropdown-item" href="kelola-kelas"><span class="font-icon font-icon-pencil"></span>Kelola Kelas</a>
			                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#joinKelas"><span class="font-icon font-icon-user"></span>Gabung Kelas</a>
			                            </div>';
									}else {
										echo '<div class="dropdown-menu" aria-labelledby="dd-header-marketing">
			                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#joinKelas"><span class="font-icon font-icon-user"></span>Gabung Kelas</a>
			                            </div>';
									}
								}
								?>

	                        </div>
                            <?php 
                                if (isset($_SESSION['lms_status'])) {
                                    if($_SESSION['lms_status'] == "guru" || $_SESSION['lms_status'] == 'admin' || $_SESSION['lms_status'] == 'pengelola' || $_SESSION['lms_status'] == 'pengelola'){
                            ?>
                                        <!-- <div class="dropdown dropdown-typical">
                                            <a class="dropdown-toggle" id="dd-header-marketing" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="font-icon font-icon-notebook"></span> Ujian
                                                <span class="lbl"></span>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dd-header-marketing">
                                                <a class="dropdown-item" href="kelola-ujian"><span class="font-icon font-icon-widget"></span>Kelola Ujian</a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#joinUjian"><span class="font-icon font-icon-post"></span>Masuk Ujian</a>
                                            </div>
                                        </div> -->
                            <?php
                                    }else{
                            ?>
                                        <div class="dropdown dropdown-typical">
                                            <a class="dropdown-toggle" id="dd-header-marketing" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="font-icon font-icon-notebook"></span> Ujian
                                                <span class="lbl"></span>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dd-header-marketing">
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#joinUjian"><span class="font-icon font-icon-post"></span>Masuk Ruang Uji</a>
                                            </div>
                                        </div>
                            <?php

                                    }
                                }
                            ?>
	                        <div class="dropdown dropdown-typical">

							   <!-- <a href="<?=base_url?>paket-kuis.php" class="dropdown-toggle no-arr">
								   <span class="font-icon font-icon-archive"></span> Paket Soal
								   <span class="lbl"></span>
							   </a> -->
						   	</div>
							<div class="dropdown dropdown-typical">
							   <a href="http://emodul.kemdikbud.go.id/" target="_blank" class="dropdown-toggle no-arr">
								   <span class="font-icon font-icon-doc"></span> E-Modul
								   <span class="lbl"></span>
							   </a>
						   </div>
	                    </div><!--.site-header-collapsed-in-->
	                </div><!--.site-header-collapsed-->
	            </div><!--site-header-content-in-->
	        </div><!--.site-header-content-->
	    </div><!--.container-fluid-->
	</header><!--.site-header-->

	<div class="modal fade"
		 id="addKelas"
		 tabindex="-1"
		 role="dialog"
		 aria-labelledby="addKelasLabel"
		 aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form method="POST">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="addKelasLabel">Tambah Kelas Baru</h4>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label for="namakelas" class="col-md-3 form-control-label">Nama Kelas</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="namakelas" id="namakelas" placeholder="Nama Kelas baru" title="Nama Kelas Baru" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-content="Silahkan isikan Nama Kelas yang akan dibuat!" required />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="addKelas" value="send" class="btn btn-rounded btn-primary">Simpan</button>
					<button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
				</div>
				</form>
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
				<form method="POST" >
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="joinKelasLabel">Bergabung Kelas</h4>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<div class="col-md-12">
						<input type="text" class="form-control" name="kodekelas" id="kodekelas" placeholder="Kode Kelas" title="Kode Kelas" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-content="Silahkan isikan Kode Kelas yang sudah diberikan oleh Tutor/Rekan anda!"  required />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="joinKelas" value="send" class="btn btn-rounded btn-primary">Bergabung</button>
					<button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
				</div>
				</form>
			</div>
		</div>
	</div><!--.modal-->

    <div class="modal fade bd-example-modal-sm"
		 id="joinUjian"
		 tabindex="-1"
		 role="dialog"
		 aria-labelledby="joinUjianLabel"
		 aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<form method="POST" >
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="joinUjianLabel">Ruang Uji</h4>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<div class="col-md-12">
						<input type="text" class="form-control" name="kodeUjian" id="kodeUjian" placeholder="Kode Ujian" title="Kode Ujian" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-content="Silahkan isikan Kode Ujian yang sudah diberikan oleh Tutor anda!"  required />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="joinUjian" value="send" class="btn btn-rounded btn-primary">Masuk Ujian</button>
					<button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Tutup</button>
				</div>
				</form>
			</div>
		</div>
	</div><!--.modal-->
