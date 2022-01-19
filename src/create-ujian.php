<?php
require("includes/header-top.php");
?>
<!-- Style for html code -->
<link rel="stylesheet" href="./assets/css/separate/pages/others.min.css">
<link rel="stylesheet" href="./assets/css/lib/daterangepicker/daterangepicker.css">
<?php
require("includes/header-menu.php");

$userClass	    = new User();
$profilClass    = new Profile();
$sekolahClass   = new Sekolah();
$ProvkotClass   = new Provkot();
$mapelClass = new Mapel(); 
$modulClass = new Modul();
$quizClass  = new Quiz();
$soalClass  = new Soal();
$kelasClass = new Kelas();

if (isset($_SESSION['lms_id'])) {
	$userProfil	= $userClass->GetData("$_SESSION[lms_id]");
    $infoSekolah = $sekolahClass->getInfoSekolah($userProfil['sekolah']);
	if (isset($userProfil['kota']) || !empty($userProfil['kota'])) {
		$getKota = $ProvkotClass->getKota((int)$userProfil['kota']);
		$asalKota = $getKota['nama_kab_kot'];
	}
}

$infoQuiz   = $quizClass->getInfoQuiz($_GET['id']);
$infoModul	= $modulClass->getInfoModul($infoQuiz['id_modul']);
$infoMapel	= $mapelClass->getInfoMapel($infoModul['id_mapel']);
$infoKelas  = $kelasClass->getInfoKelas($infoMapel['id_kelas']);
$hakKelas	= $kelasClass->getKeanggotaan($infoMapel['id_kelas'], $_SESSION['lms_id']);

$submittedQuiz 	= $quizClass->isSumbmitted((string)$_SESSION['lms_id'], (string)$infoQuiz['_id']);
$bentukQuiz		= $infoQuiz['jenis'];
$jumlah_soal    = $soalClass->getNumberbyQuiz($infoQuiz['id_paket']);

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

$logIDKelas = $infoMapel['id_kelas'];

?>
<script type="text/javascript">document.title = "Ruang Ujian <?=$infoQuiz['nama']?> - seTARA daring";</script>
<link rel="stylesheet" href="assets/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="assets/css/separate/vendor/datatables-net.min.css">

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
			<!-- <button type="button" class="change-cover" onclick="update()">
				<i class="font-icon font-icon-pencil"></i>
				Pengaturan Mata Pelajaran
			</button> -->
		</div><!--.profile-header-photo-->
        <div class="container-fluid">
			<div class="row">
				<div class="col-xl-3 col-lg-4">
					<aside id="menu-fixed2" class="profile-side">
						<section class="box-typical profile-side-user">
							<button type="button" class="avatar-preview avatar-preview-128">
								<img src="media/Assets/foto/<?php if ($FuncProfile['foto'] != NULL) {echo $FuncProfile['foto'];}else{echo "no_picture.png";} ?>" alt=""/>
							</button>
                            <?php 
                                if($_SESSION['lms_status'] == 'siswa'){
                                    if( strtotime(date('Y-m-d')) >= strtotime($infoQuiz["start_date"]) ){
                                        if( strtotime(date('Y-m-d')) <= strtotime($infoQuiz["end_date"]) ){
                                            if(!$submittedQuiz){
                                        ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a href="ujian-start?id=<?=$infoQuiz['_id']?>&paket=<?=$infoQuiz['id_paket']?>" class="btn btn-rounded btn-primary" title="Ujian" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Tombol untuk mulai mengerjakan ujian." style="right: 35px">
                                                        <i class="font-icon font-icon-play"></i> Mulai Ujian
                                                    </a>
                                                </div>
                                            </div>
                                        <?php
                                            }else{
                                                ?>
                                                <a class="btn btn-rounded btn-default" title="Keterangan Ujian" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Anda telah menyelesaikan ujian.">
                                                    <font style="color: white; font-size:15px;">Ujian Telah Dikerjakan</font>
                                                </a>
                                            <?php
                                            }
                                        }else{
                                        ?>
                                            <a class="btn btn-rounded btn-default" title="Keterangan Ujian" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Ujian tidak dapat dikerjakan, karena sudah melewati Tenggat Waktu.">
                                                <font style="color: white; font-size:13px;">Tidak dapat Mengerjakan</font>
                                            </a>
                                        <?php
                                        }
                                    }else{
                                        ?>
                                            <a class="btn btn-rounded btn-default" title="Keterangan Ujian" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Ujian belum dapat dikerjakan, karena waktu mulai pengerjaan baru dibuka pada <?=date('d F Y', strtotime($infoQuiz["start_date"]))?>" style="right: 35px">
                                            <font style="color: white; font-size:13px;">Belum dapat Mengerjakan</font>
                                            </a>
                                        <?php
                                    }
                                }
                            ?>
                            
						</section>
                        <section class="widget widget-time">
                            <header class="widget-header-dark">
                                <i class="fa fa-clock-o"></i> Waktu Pengerjaan
                            </header>
                            <div class="widget-time-content">
                                <div class="count-item">
                                    <div class="count-item-number"><?=$infoQuiz["durasi"]?></div>
                                    <div class="count-item-caption">min</div>
                                </div>
                                <div class="count-item divider">:</div>
                                <div class="count-item">
                                    <div class="count-item-number">00</div>
                                    <div class="count-item-caption">sec</div>
                                </div>
                            </div>
                        </section>
                        <section class="widget widget-simple-sm-fill red">
                            <div class="widget-simple-sm-icon">
                                <i class="font-icon font-icon-notebook"></i>
                            </div>
                            <div class="widget-simple-sm-fill-caption"><?=$jumlah_soal?> Soal</div>
						</section>
					</aside><!--.profile-side-->
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
										<th class="text-center tb-lg" style="width: 70px;">Foto</th>
										<th class="text-center">Hasil Penilaian</th>
										<th class="text-center" style="width: 70px;">Aksi</th>
									</tr>
								</thead>
                                <tbody>
                                </tbody>
							</table>
						</div>
					</section><!--.widget-tasks-->

					

						<div class="card-block" id="accordion">
							<?php
					if (isset($infoQuiz)) {
							?>
								<article class="box-typical profile-post panel">
									<div class="profile-post-header">
										<div class="user-card-row">
											<div class="tbl-row">
												<div class="tbl-cell tbl-cell-photo">
                                                    <?php
                                                        if($submittedQuiz){
                                                    ?>
                                                        <img src="assets/img/quiz.png" alt="">
                                                    <?php }else{ ?>
                                                        <img src="assets/img/quiz-empty.png" alt="">
                                                    <?php } ?>
												</div>
												<div class="tbl-cell">
													<div class="user-card-row-name">
                                                        <?=$infoQuiz['nama']?>
													</div>
													<div class="color-blue-grey-lighter"><?=($infoQuiz['date_created'] == $infoQuiz['date_modified'] ? "Diterbitkan " : "Diperbarui ").selisih_waktu($infoQuiz['date_modified'])?></div>
												</div>
												<div class="tbl-cell" align="right">
												<?php if ($hakKelas['status'] == 1 || $_SESSION['lms_id'] == $infoMapel['creator']) {?>
														<a href="quiz-action?act=update&md=<?=$infoModul['_id']?>&qz=<?=$infoQuiz['_id']?>" class="shared" title="Edit" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk memperbarui isi dari Ujian yang sudah dibuat." style="right: 35px">
															<i class="font-icon font-icon-pencil"></i>
														</a>
														<a onclick="remove('<?=$infoQuiz['_id']?>')"   class="shared" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus Materi yang sudah dibuat.">
															<i class="font-icon font-icon-trash"></i>
														</a>
												<?php
													}
												?>
												</div>
											</div>
										</div>
									</div>
									<div id="demo" class="profile-post-content">
										<?php
											if($submittedQuiz){
												$nilaiQuiz      = $quizClass->getNilaiQuiz((string)$infoQuiz['_id'], $_SESSION['lms_id']);
										?>
										<b>Petunjuk Pengerjaan Ujian :</b><br>
										<?=nl2br(@$infoQuiz["instruksi"])?>
										<br>
										<hr style="margin: 10px 0; ">
                                        <b><i class="fa fa-calendar"></i> Tenggat Waktu :</b> <?=date('d F Y', strtotime($infoQuiz["end_date"]))?> <br />
										<b><i class="fa fa-book"></i> Bentuk Soal :</b> Pilihan Ganda<br />
                                        <?=($infoQuiz["jenis"] == "1"? "<b><i class='fa fa-lock'></i> Kode Ujian :</b> ".(empty($infoQuiz['kode'])?"<span class='label label-pill label-danger'>Belum Tersedia</span>":"<u>".$infoQuiz['kode']."</u>"):"")?><br />
										<hr style="margin: 10px 0;">
										<?php
											$nilai_akhir = round((($nilaiQuiz['nilai']/$jumlah_soal)*100), 2);

											if($nilai_akhir > 100){
												$nilai_akhir = $nilaiQuiz['nilai'];
											}
										?>
										<b><i class="fa fa-calendar-check-o"></i> Nilai Hasil Ujian :</b> <span style="font-size: 1.5em; text-decoration: underline"><?=$nilai_akhir?></span> <br />
										<?php
											}else{
												$jumlah_soal    = $soalClass->getNumberbyQuiz($infoQuiz['id_paket']);
										?>
										<b>Petunjuk Pengerjaan Ujian :</b><br>
										<?=nl2br(@$infoQuiz["instruksi"])?>
										<br>
										<hr style="margin: 10px 0; ">
                                        <b><i class="fa fa-calendar"></i> Tenggat Waktu :</b> <?=date('d F Y', strtotime($infoQuiz["end_date"]))?> <br />
										<b><i class="fa fa-book"></i> Bentuk Soal :</b> Pilihan Ganda<br />
                                        <?=($infoQuiz["jenis"] == "1"? "<b><i class='fa fa-lock'></i> Kode Ujian :</b> ".(empty($infoQuiz['kode'])?"<span class='label label-pill label-danger'>Belum Tersedia</span>":"<u>".$infoQuiz['kode']."</u>"):"")?><br />
										<hr style="margin: 10px 0;">
										<?php
											}
											if($_SESSION['lms_status'] == 'siswa'){
													if( strtotime(date('Y-m-d')) >= strtotime($infoQuiz["start_date"]) ){
														if( strtotime(date('Y-m-d')) <= strtotime($infoQuiz["end_date"]) ){
															if(!$submittedQuiz){
														?>
															<div class="row">
																<div class="col-md-12">
																	<a href="ujian-start?id=<?=$infoQuiz['_id']?>&paket=<?=$infoQuiz['id_paket']?>" class="btn btn-rounded btn-primary pull-right" title="Ujian" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk mulai mengerjakan penilaian hasil belajar." style="right: 35px">
																		<i class="font-icon font-icon-play" aria-hidden="true"></i> Mulai Ujian
																	</a>
																</div>
															</div>
														<?php
															}else{
																if($bentukQuiz == "0"){
																	?>
																		<div class="row">
																			<div class="col-md-12">
																				<a onClick="confirm_quiz('<?=$infoQuiz['_id']?>', '<?=$infoQuiz['id_paket']?>'); return false;" class="btn btn-rounded btn-primary pull-right" title="Penilaian Hasil Belajar" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk mulai mengerjakan kembali penilaian hasil belajar." style="right: 35px">
																					<i class="fa fa-clock-o" aria-hidden="true"></i> Kerjakan Ulang
																				</a>
																			</div>
																		</div>
																	<?php
																}
															}
														}else{
														?>
															<div class="row">
																<div class="col-md-12">
																	<a class="btn btn-rounded btn-default pull-right" title="Keterangan Ujian" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Ujian tidak dapat dikerjakan, karena sudah melewati Tenggat Waktu." style="right: 35px">
																		<i class="fa fa-clock-o" aria-hidden="true"></i> Tidak dapat Mengerjakan
																	</a>
																</div>
															</div>
														<?php
														}
													}else{
														?>
														<div class="row">
															<div class="col-md-12">
																<a class="btn btn-rounded btn-default pull-right" title="Keterangan Ujian" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Ujian belum dapat dikerjakan, karena waktu mulai pengerjaan baru dibuka pada <?=date('d F Y', strtotime($infoQuiz["start_date"]))?>" style="right: 35px">
																	<i class="fa fa-clock-o" aria-hidden="true"></i> Belum dapat Mengerjakan
																</a>
															</div>
														</div>
														<?php
													}
											}else{
												if($hakKelas['status'] == 1 || $infoMapel['creator'] == $_SESSION['lms_id']){
													?>
													<div class="row">
														<div class="col-md-6">
															<a href="print-quiz?id=<?=$infoQuiz['_id']?>&paket=<?=$infoQuiz['id_paket']?>" class="btn btn-rounded btn-primary pull-left" title="Print" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk print penilaian hasil belajar." style="right: 60px" target="_blank">
																<i class="fa fa-print" aria-hidden="true"></i> Print Soal
															</a>
														</div>
														<div class="col-md-6">
															<a onclick="lihat_hasil_penilaian('<?=$infoQuiz[_id]?>','daftar-penilaian','daftar-hasil-penilaian','<?=$jumlah_soal?>')" class="btn btn-rounded btn-primary pull-right" title="Print" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk print penilaian hasil belajar." style="right: 60px" target="_blank">
																<i class="fa fa-table" aria-hidden="true"></i> Lihat Hasil Penilaian
															</a>
														</div>
													</div>
													<?php
												}else{
													?>
													<div class="row">
														<div class="col-md-12">
															<a href="print-quiz?id=<?=$infoQuiz['_id']?>&paket=<?=$infoQuiz['id_paket']?>" class="btn btn-rounded btn-primary pull-left" title="Print" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk print penilaian hasil belajar." style="right: 60px" target="_blank">
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
					}else {
						?>
							<article class="box-typical profile-post">
								<div class="add-customers-screen tbl">
									<div class="add-customers-screen-in">
										<div class="add-customers-screen-user">
											<i class="font-icon font-icon-notebook"></i>
										</div>
										<h2>Ujian Kosong</h2>
										<p class="lead color-blue-grey-lighter">Belum ada pelaksanaan Ujian</p>
									</div>
								</div>
							</article>
				<?php
					}
				?>
						</div>
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
    		                        { "mDataProp": "foto_user" },
    		                        { "mDataProp": "nilai_user"},
    		                        { "mDataProp": "aksi"}
    		                        ],
    		    "searchCols"    : [null, null, null],
    		    "order"         : [[1, 'desc']],
    		    "language"      : {
    		                            "infoFiltered"  : "",
    		                            "processing"	: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Memuat...</span>'
    		                        },
    		    "columnDefs": [
    		        {"className": "text-center", "targets": [0,2]}
    		    ],
    			"dom"			 : '<"row"<"col-sm-4"l><"col-sm-4"Br><"col-sm-4"f>>t<"row"<"col-sm-5"i><"col-sm-7"p>>',
    			"buttons"		 : ["excel", "pdf", "print"],
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
                        $('#'+id_user).html("<i class='fa fa-table' aria-hidden='true'></i> Nilai: -<br /><span class='label label-warning'>Belum mengerjakan</span>");
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
