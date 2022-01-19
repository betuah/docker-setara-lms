<?php
require("includes/header-top.php");
?>
<!-- Style for html code -->
<link rel="stylesheet" href="./assets/tinymce4/css/prism.css" type="text/css" />
<link rel="stylesheet" href="./assets/css/separate/pages/others.min.css">
<link rel="stylesheet" href="./assets/css/lib/daterangepicker/daterangepicker.css">

<script type="text/javascript" src="./assets/tinymce4/js/tinymce/tinymce.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>

<?php
require("includes/header-menu.php");

$mapelClass 	= new Mapel();
$modulClass 	= new Modul();
$tugasClass 	= new Tugas();
$kelasClass		= new Kelas();
$userClass		= new User();

$tugas          = $tugasClass->getDetailTugas($_GET['id']);
$infoModul		= $modulClass->getInfoModul($tugas['id_modul']);
$infoMapel		= $mapelClass->getInfoMapel($infoModul['id_mapel']);
$infoTugas		= $tugasClass->getInfoTugas($tugas['id_modul']);
$listTugas		= $tugasClass->getListTugas($tugas['id_modul']);
$infoKelas		= $kelasClass->getInfoKelas($infoMapel['id_kelas']);

$submittedQuiz 	= $tugasClass->isSumbmitted((string)$_SESSION['lms_id'], (string)$tugas['_id']);
strtotime((new DateTime())->format('d M Y')) >= strtotime((new DateTime($tugas['begin']))->format('d M Y')) ? $begin = "1" : $begin = "0";
strtotime((new DateTime())->format('d M Y')) > strtotime((new DateTime($tugas['deadline']))->format('d M Y')) ? $deadline = "1" : $deadline = "0";

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


if(isset($_POST['addTugas']) || isset($_POST['updateTugas']) || isset($_POST['kumpulTugas']) || isset($_POST['updateTugasSiswa'])){

	if(isset($_POST['addTugas'])){
		// echo "<script>alert('Add Tugas');</script>";
		$rest 	= $tugasClass->addTugas($infoKelas['_id'], $tugas['id_modul'], $_POST['nama'], $_POST['jenis'], $_POST['durasi'], $_POST['deskripsi'], $_POST['deadline'], $_SESSION['lms_id'], $FuncProfile['sekolah'], $_FILES['file_upload']);
	}elseif(isset($_POST['updateTugas'])){
		// echo "<script>alert('Update Tugas');</script>";
		$rest 	= $tugasClass->updateTugas($infoKelas['_id'], $_POST['ID'], $_POST['nama'], $_POST['jenis'], $_POST['durasi'], $_POST['kode'], $_POST['deskripsi'], $_POST['deadline'], $_SESSION['lms_id'], $FuncProfile['sekolah'], $_FILES['file_upload']);
	}elseif(isset($_POST['kumpulTugas'])){
		// echo "<script>alert('Submit Tugas');</script>";
		$rest 	= $tugasClass->submitTugas($_SESSION['lms_id'], $FuncProfile['sekolah'], $_POST['ID'], $_POST['deskripsi'], $_FILES['file_upload'], $infoMapel['id_kelas']);
	}else{
		$rest 	= $tugasClass->updateTugasSiswa($_SESSION['lms_id'], $FuncProfile['sekolah'], $_POST['ID'], $_POST['deskripsi'], $_FILES['file_upload'], $infoMapel['id_kelas']);
	}

	if ($rest['status'] == "Success") {
		echo "<script>alert('Tersimpan!'); document.location='tugas?modul=".$tugas['id_modul']."'</script>";
	}else{
		echo "<script>alert('Gagal Update')</script>";
	}
}
$logIDKelas = $infoMapel['id_kelas'];

?>
<script type="text/javascript">document.title = "Ruang Uji <?=$infoModul['nama']?> - seTARA daring";</script>
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

	<div class="modal fade"
		 id="addPenilaian"
		 tabindex="-1"
		 role="dialog"
		 aria-labelledby="addPenilaianLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="POST" id="addPenilaianForm">
				<div class="modal-header">
					<button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
						<i class="font-icon-close-2"></i>
					</button>
					<h4 class="modal-title" id="addPenilaianLabel">Penilaian</h4>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label for="nilaiTugas" class="col-md-3 form-control-label">Nilai Tugas</label>
						<div class="col-md-9">
							<input type="number" min="0" max="100" class="form-control" name="nilaiTugas" id="nilaiTugas" placeholder="0 - 100" title="Nilai Tugas" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-content="Silahkan isikan nilai tugas antara 0 sampai 100!" required/>
						</div>
					</div>
					<div class="form-group row">
						<label for="nilaiTugas" class="col-md-3 form-control-label">Catatan Tugas</label>
						<div class="col-md-9">
							<textarea name="catatanTugas" id="catatanTugas" class="form-control" rows="4" placeholder="Catatan tugas jika ada" title="Catatan Tugas" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-content="Silahkan isikan catatan tugas jika ada!"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="btn-submit-penilaian" name="addPenilaian" value="send" class="btn btn-rounded btn-primary">Simpan</button>
					<button type="button" id="btn-cancel-form" class="btn btn-rounded btn-default" data-dismiss="modal">Batal</button>
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
                                    
                                    if( strtotime(date('Y-m-d')) <= strtotime($tugas["deadline"]) ){
                                        if(!$submittedQuiz){
                                            if($begin == "1"){
                                    ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a href="uji-start?id=<?=$tugas['_id']?>" class="btn btn-rounded btn-primary" title="Ujian" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Tombol untuk mulai mengerjakan ujian." style="right: 35px">
                                                            <i class="font-icon font-icon-play"></i> Mulai Ujian
                                                        </a>
                                                    </div>
                                                </div>
                                    <?php
                                            }else{
                                            ?>
                                            
                                                <a class="btn btn-rounded btn-default" title="Keterangan Ujian" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Belum dapat dikerjakan, karena waktu mulai pengerjaan baru dibuka pada <?=date('d F Y', strtotime($tugas['begin']))?>">
                                                    <font style="color: white; font-size:13px;">Belum dapat Mengerjakan</font>
                                                </a>
                                            <?php  
                                            }
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
                                }
                            ?>
                            
						</section>
                        <section class="widget widget-time">
                            <header class="widget-header-dark">
                                <i class="fa fa-clock-o"></i> Waktu Pengerjaan
                            </header>
                            <div class="widget-time-content">
                                <div class="count-item">
                                    <div class="count-item-number"><?=$tugas["durasi"]?></div>
                                    <div class="count-item-caption">min</div>
                                </div>
                                <div class="count-item divider">:</div>
                                <div class="count-item">
                                    <div class="count-item-number">00</div>
                                    <div class="count-item-caption">sec</div>
                                </div>
                            </div>
                        </section>
					</aside><!--.profile-side-->
				</div>

				<div class="col-xl-9 col-lg-8" style="position: static;">
					<article id="tugas-detil" class="box-typical profile-post" style="display: none;">
						<div class="profile-post-header">
							<div class="user-card-row">
								<div class="tbl-row">
									<div class="tbl-cell tbl-cell-photo" id="foto-creator-tugas">
											<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />
									</div>
									<div class="tbl-cell">
										<div class="user-card-row-name"><text id="nama-creator-tugas">Creator Tugas </text>&nbsp; <i class="fa fa-play" style="font-size: 70%; display: inline-block;"></i> &nbsp; <text id="judul-tugas">Judul Tugas</text>
										</div>
										<div class="color-blue-grey-lighter" id="waktu-tugas"></div>
									</div>
									<div class="btn-group" style="float: right;">
										<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Kembali
										</button>
										<div class="dropdown-menu dropdown-menu-right">
										<?php echo ($hakKelas['status'] != "4" ? '
											<a class="dropdown-item" onclick="lihat_tugas_siswa(null, \'tugas-detil\')" title="Tugas" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk kembali ke tugas siswa."><i class="font-icon font-icon-doc"></i> Kembali ke tugas siswa</a>' : '');?>
											<a class="dropdown-item" onclick="lihat_daftar_tugas('tugas-detil')" title="Tugas" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk kembali ke daftar tugas."><i class="font-icon font-icon-burger"></i> Kembali ke daftar tugas</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="profile-post-content">
							<p id="deskripsi-tugas">
							</p>
							<a id="lampiran-tugas" target="_blank" href="" style="display: none;"><span class="font-icon font-icon-clip"></span> File Lampiran</a>
						</div>
						<div class="box-typical-footer profile-post-meta">
							<div id="btn-berikan-penilaian" class="btn-group" style="float: right;">
							</div>
							<a href="#jawaban-tugas" data-toggle="collapse" data-parent="#accordion" class="meta-item">
								<i class="font-icon font-icon-comment"></i>
								<?php echo ($hakKelas['status'] == "4" ? "Jawaban Anda" : "Jawaban Siswa");?>
							</a>
						</div>
						<div class="comment-rows-container" style="position: static;background-color: #ecf2f5; max-height: none;">
							<div id="jawaban-tugas" class="collapse">
								<div class="comment-row-item">
									<div class="tbl-row">
										<div class="avatar-preview avatar-preview-32" id="foto-user">
											<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />
										</div>
										<div class="tbl-cell comment-row-item-header">
											<div class="user-card-row-name" style="font-weight: 600" id="nama-user"></div>
											<div class="color-blue-grey-lighter" style="font-size: .875rem" id="waktu-kumpul-tugas"></div>
											<input type='hidden' class='form-control' id='IDPengumpulTugas' placeholder='ID Pengumpul Tugas' />
										</div>
									</div>
									<div class="comment-row-item-content" style="margin-top: 5px;">
										<p id="deskripsi-user"></p>
										<a id="lampiran-user" target="_blank" href="" style="display: none;"><span class="font-icon font-icon-clip"></span> File Lampiran</a>
									</div>
								</div><!--.comment-row-item-->
							</div>
						</div><!--.comment-rows-container-->
					</article>

					<section class="widget widget-tasks card-default" id="tugas-siswa" style="display: none;">
						<header class="card-header">
							Tugas Siswa
							<div class="btn-group" style="float: right;">
                                <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Kembali
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" onclick="lihat_daftar_tugas('tugas-siswa')" title="Tugas" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk kembali ke daftar tugas."><i class="font-icon font-icon-burger"></i> Kembali ke daftar tugas</a>
                                </div>
                            </div>
						</header>
						<div class="widget-tasks-item">
							<table id="tabel-kumpul-tugas" class="display table table-striped" cellspacing="0" width="100%">
								<thead>
									<tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Warga Belajar</th>
                                        <th class="text-center">Nilai</th>
                                        <th class="text-center">Waktu Kumpul</th>
										<th class="text-center" style="width: 50px;">Foto</th>
										<th class="text-center">Daftar Kumpul Tugas</th>
										<th class="text-center" style="width: 50px;">Aksi</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</section><!--.widget-tasks-->

                    <div class="card-block" id="accordion" id="tugas-preview">
                        <?php
                            $tugasKumpul = $tugasClass->getStatusTugas($tugas['_id'], $_SESSION['lms_id']);
                            if(!$tugasKumpul){
                                $statusTugas = "0";
                            }else{
                                $statusTugas = "1";
                            }
                            
                            // if ($_SESSION['lms_id'] == $tugas['creator']) {
                            //if ($hakKelas['status'] == 1 || $_SESSION['lms_id'] == $tugas['creator']) {
                            if ($hakKelas['status'] == 1 || $_SESSION['lms_id'] == $infoMapel['creator']) {
                                echo '<article class="box-typical profile-post panel">
                                        <div class="profile-post-header">
                                            <div class="user-card-row">
                                                <div class="tbl-row">
                                                    <div class="tbl-cell tbl-cell-photo">
                                                        <img src="assets/img/assignment.png" alt="">
                                                    </div>
                                                    <div class="tbl-cell">
                                                        <div class="user-card-row-name">'.$tugas['nama'].'</div>
                                                        <div class="color-blue-grey-lighter">'.($tugas['date_created'] == $tugas['date_modified'] ? "" : "Diperbarui ").selisih_waktu($tugas['date_modified']).'</div>
                                                    </div>
                                                    <div class="tbl-cell" align="right">
                                                        <a onclick="edit(\''.$tugas['_id'].'\')" class="shared" id="btn-edit" title="Edit" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk memperbarui Tugas yang sudah dibuat." style="right: 35px">
                                                            <i class="font-icon font-icon-pencil")"></i>
                                                        </a>
                                                        <a onclick="remove(\''.$tugas['_id'].'\')"   class="shared" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus Tugas yang sudah dibuat.">
                                                            <i class="font-icon font-icon-trash")"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="demo">
                                            <div class="profile-post-content">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span><b><i class="fa fa-book"></i> Bentuk Pengerjaan</b> &nbsp; : '.($tugas['jenis']=='1'?'Ujian':'Penugasan').'
                                                        <br><span><b><i class="fa fa-calendar"></i> Tenggat Waktu</b> &nbsp; : <u>'.date("d F Y", strtotime($tugas["deadline"])).'</u></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        '.($tugas['jenis']=='1'?'<span><b><i class="fa fa-clock-o"></i> Durasi</b> &nbsp; : <u>'.$tugas['durasi'].' menit</u>':'').'
                                                        '.($tugas['jenis']=='1'?'<br><span><b><i class="fa fa-lock"></i> Kode Ujian</b> &nbsp; : <u>'.$tugas['kode'].'</u>':'').'
                                                    </div>
                                                </div>
                                                <hr style="margin: 10px 0;">
                                                '.$tugas["deskripsi"].'
                                                '.(isset($tugas["file"]) && !empty($tugas["file"]) ? '<a target="_blank" href="assets/dokumen/'.$tugas["file"].'"><span class="font-icon font-icon-clip"></span> File Lampiran</a>' : '').'
                                            </div>
                                            <div class="box-typical-footer">
                                                <div class="tbl">
                                                    <div class="tbl-row">
                                                        <div class="tbl-cell tbl-cell-action">
                                                            <a onclick="lihat_tugas_siswa(\''.$tugas['_id'].'\', \'tugas-preview\')" title="Tugas" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk melihat tugas siswa." class="btn btn-rounded btn-primary pull-right">Lihat Tugas Siswa</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </article>';
                            } else {
                                echo '<article id="article-'.$tugas['_id'].'" class="box-typical profile-post panel article-siswa">
                                        <div class="profile-post-header">
                                            <div class="user-card-row">
                                                <div class="tbl-row">
                                                    <div class="tbl-cell tbl-cell-photo">
                                                        <img src="assets/img/assignment.png" alt="">
                                                    </div>
                                                    <div class="tbl-cell">
                                                    <div class="user-card-row-name"><a href="#demo'.$no.'" data-toggle="collapse" data-parent="#accordion">'.$tugas['nama'].'</a>'.($hakKelas['status'] == "4"? ($begin == "0"? '<span class="label label-default pull-right">Belum Dapat Dikerjakan</span>':($statusTugas == "1" ? '<span class="label label-success pull-right">Sudah Dikerjakan</span>': ($deadline == "1" ? '<span class="label label-danger pull-right">Tidak Dikerjakan</span>' : '<span class="label label-warning pull-right">Belum Dikerjakan</span>'))): '').'</div>
                                                        <div class="color-blue-grey-lighter">'.($tugas['date_created'] == $tugas['date_modified'] ? "Diterbitkan " : "Diperbarui ").selisih_waktu($tugas['date_modified']).'</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="demo">
                                            <div class="profile-post-content">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span><b><i class="fa fa-book"></i> Bentuk Pengerjaan</b> &nbsp; : '.($tugas['jenis']=='1'?'Ujian':'Penugasan').'
                                                        <br><span><b><i class="fa fa-calendar"></i> Tenggat Waktu</b> &nbsp; : <u>'.date("d F Y", strtotime($tugas["deadline"])).'</u></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        '.($tugas['jenis']=='1'?'<span><b><i class="fa fa-clock-o"></i> Durasi</b> &nbsp; : <u>'.$tugas['durasi'].' menit</u>':'').'
                                                        '.($tugas['jenis']=='1'?'<br><span><b><i class="fa fa-lock"></i> Kode Ujian</b> &nbsp; : <u>'.$tugas['kode'].'</u>':'').'
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="box-typical-footer">
                                                <div class="tbl">
                                                    <div class="tbl-row">
                                                        <div class="tbl-cell">'.
                                                        ($hakKelas['status'] == "4" ?
                                                            ($statusTugas == "1" ? 
                                                                '<b><i class="fa fa-calendar-check-o"></i> Nilai Hasil Ujian :</b> <span style="font-size: 1.5em; text-decoration: underline">'.($tugasKumpul['nilai']?$tugasKumpul['nilai']:'<span class="label label-warning">Dalam proses penilaian</span>').'</span> <br />' :
                                                                ($deadline == "1" ? 
                                                                    '<a class="btn btn-rounded btn-default pull-right" title="Keterangan Ujian" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Ujian tidak dapat dikerjakan, karena sudah melewati Tenggat Waktu."><font style="color: white;">Tidak dapat Mengerjakan</font></a>' :
                                                                    ($begin == "0"?
                                                                        '<a class="btn btn-rounded btn-default pull-right" title="Keterangan Ujian" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Belum dapat dikerjakan, karena waktu mulai pengerjaan baru dibuka pada '.date('d F Y', strtotime($tugas['begin'])).'" style="right: 35px"><i class="fa fa-clock-o" aria-hidden="true"></i> Belum dapat Mengerjakan</a>':
                                                                        '<a href="uji-start.php?id='.$tugas['_id'].'" title="Ujian" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk mengerjakan ujian." class="btn btn-rounded btn-primary pull-right">Mulai Ujian</a>'
                                                                    )
                                                                )
                                                            ):
                                                            '<a onclick="lihat_tugas_siswa(\''.$tugas['_id'].'\', \'tugas-preview\')" title="Ujian" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk melihat Ujian siswa." class="btn btn-rounded btn-primary pull-right">Lembar Ujian Siswa</a>'
                                                        )
                                                        .'
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </article>';
                            }
                        ?>
                    </div>

                    <section class="card card-default" id="tugas-editor" style="display: none;">
                        <div class="card-block">
                            <h5 class="with-border" id="judul-editor">Pembuatan Tugas</h5>

                            <form id="form_tambah" method="POST" enctype="multipart/form-data">
                                <div class="form-group row" id="row-judul">
                                    <label class="col-md-2 form-control-label">Judul</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Judul Tugas" />
                                        <input type="hidden" class="form-control" id="kode" name="kode" placeholder="Kode Tugas" />
                                    </div>
                                </div>
                                <div class="form-group row" id="div-bentuk-pengerjaan">
                                    <label class="col-md-2 form-control-label">Bentuk Pengerjaan</label>
                                    <div class="col-md-8">
                                        <select class="select2" id="jenis" name="jenis">
                                            <option value="0" selected>Penugasan</option>
                                            <option value="1">Ujian</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="div-durasi" style="display: none;">
                                    <label class="col-md-2 form-control-label" for="exampleInput">Durasi</label>
                                    <div class="col-md-8">
                                            <input type="number" class="form-control" name="durasi" id="durasi" placeholder="0" min="0" maxlength="3">
                                            <small class="text-muted">Lama Pengerjaan dalam satuan menit.</small>
                                    </div>
                                </div>
                                <div class="form-group row" id="row-deadline">
                                    <label class="col-md-2 form-control-label">Tenggat Waktu Pengumpulan</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="deadline" name="deadline" placeholder="Tengat Waktu Tugas" required/>
                                        <input type="hidden" class="form-control" id="IDTugas" name="ID" placeholder="ID Tugas" />
                                    </div>
                                </div>
                                <div class="form-group row" id="row-file-upload" style="display: none;">
                                    <label class="col-md-2 form-control-label">Upload File Tugas</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="file_upload_view" name="file_upload_view" placeholder="File Upload" readonly/>
                                        <input type="file" class="form-control" id="file_upload" name="file_upload" placeholder="File Upload" style="display: none" />
                                    </div>
                                    <div class="col-md-2">
                                        <button id="btn-upload" class="btn btn-rounded btn-inline btn-primary pull-right" type="button" name="button">Upload File</button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label">Isi Tugas</label>
                                    <div class="col-md-9">
                                        <div id="editorContainer">
                                            <div id="toolbarLocation"></div>
                                            <textarea id="editormce" class="form-control wrs_div_box" contenteditable="true" tabindex="0" spellcheck="false" aria-label="Rich Text Editor, example"></textarea>
                                            <input id="editor" type="text" name="deskripsi" style="display: none;" />
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group pull-right">
                                    <button type="submit" id="btn-submit" name="addTugas" class="btn">Simpan</button>
                                    <button type="button" class="btn btn-default" id="btn-cancel">Batal</button>
                                    <button type="button" class="btn btn-default" id="btn-cancel-tugas-siswa" style="display: none;">Batal</button>
                                </div>
                            </form>
                        </div>
                    </section>
				</div>
			</div><!--.row-->

		</div><!--.container-fluid-->
	</div><!--.page-content-->

<?php
	require('includes/footer-top.php');
?>
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="assets/js/lib/daterangepicker/daterangepicker.js"></script>
	<script type="text/javascript" src="assets/js/lib/datatables-net/datatables.min.js"></script>

	<script>

        var jumlah_siswa = <?=$infoKelas['jumlah_siswa']?>;

        if(jumlah_siswa > 10){
            lengthList = [ [10, jumlah_siswa], [10, "ALL"] ];
        }else{
            lengthList = [jumlah_siswa];
        }

		$("#form_tambah").submit(function(e){
			$("#editor").val(tinyMCE.get('editormce').getContent());
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
      				url: 'url-API/Kelas/Modul/Tugas/',
      				data: {"action": "remv", "ID":ID, "user":"<?=$_SESSION['lms_id']?>", "kelas":"<?=$infoKelas['_id']?>"},
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

		function edit(ID){
      		$('#judul-editor').text(
      		   $('#judul-editor').text().replace('Pembuatan', 'Perubahan')
      		).show();

      		$.ajax({
      			type: 'POST',
      			url: 'url-API/Kelas/Modul/Tugas/',
      			data: {"action": "show", "ID": ID},
      			success: function(res) {
  					$('#IDTugas').val(ID);
  					$('#deadline').val(res.data.deadline);
                    $('#kode').val(res.data.kode);
  					$('#nama').val(res.data.nama);
                    $('#durasi').val(res.data.durasi);
					$('#file_upload_view').val(res.data.file);
                    if(!res.data.jenis){
                        $('#jenis').val("0");
                    }else{
                        $('#jenis').val(res.data.jenis);
                    }
                    $("#jenis").select2('val', res.data.jenis);
                    $('#jenis').trigger('change');

					$('#btn-upload').html('Perbarui File');
					tinyMCE.activeEditor.setContent(res.data.deskripsi);
  					$('#btn-submit').attr('name', 'updateTugas');
      			},
      			error: function () {
      				swal("Error!", "Data tidak ditemukan!", "error");
      			}
      		});
      	}

		function kerjakan_tugas(ID){
			$('#IDTugas').val(ID);

            $('.article-siswa').hide();
            $('#article-'+ID).show();

            $('#tugas-editor').show();
			$('#row-judul').hide();
			$('#row-deadline').hide();
			$('#row-file-upload').hide();
            $('#div-bentuk-pengerjaan').hide();
			$("#deadline").attr("required", false);
			$('#btn-submit').attr('name', 'kumpulTugas');
			$('#judul-editor').text(
			   $('#judul-editor').text().replace('Pembuatan', 'Kumpul')
			).show();
		}

		function lihat_tugas(ID, hideElement, IDPengumpulTugas){
			$('#IDTugas').val(ID);
			$('#IDPengumpulTugas').val(IDPengumpulTugas);
			$('#'+hideElement).hide();
			$('#tugas-detil').show();

            $('#tabel-kumpul-tugas').DataTable().clear().destroy();
		}

		function lihat_daftar_tugas(hideElement){
			$('#tugas-preview').show();
			$('#'+hideElement).hide();
			clearText('#deskripsi-tugas');

            $('#tabel-kumpul-tugas').DataTable().clear().destroy();
		}

		function lihat_tugas_siswa(ID, hideElement){
			if(ID != null){
				$('#IDTugas').val(ID);
			}else{
				ID = $('#IDTugas').val();
			}

			$('#'+hideElement).hide();
			$('#tugas-siswa').show();

            $('#tabel-kumpul-tugas').DataTable().clear().destroy();

            $('#tabel-kumpul-tugas').DataTable( {
    		    "processing"    : true,
    		    "bServerSide"   : true,
    		    "sAjaxSource"   : "url-API/Kelas/Modul/Tugas?action=showDaftarTugasSiswaAjax&kelas=<?=$infoMapel['id_kelas']?>&ID="+ID,
    		    "deferRender"   : true,
    		    "aoColumns"     : [
                                    { "mDataProp": "no", "bVisible": false},
    		                        { "mDataProp": "nama", "bVisible": false},
                                    { "mDataProp": "nilai", "bVisible": false},
                                    { "mDataProp": "waktu_kumpul", "bVisible": false},
    		                        { "mDataProp": "foto_user" },
    		                        { "mDataProp": "nilai_user"},
    		                        { "mDataProp": "aksi"}
    		                        ],
    		    "searchCols"    : [null, null, null, null, null, null, null],
    		    "order"         : [[1, 'desc']],
    		    "language"      : {
    		                            "infoFiltered"  : "",
    		                            "processing"	: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Memuat...</span>'
    		                        },
    		    "columnDefs": [
    		        {"className": "text-center", "targets": [4,6]}
    		    ],
    			"dom"			 : '<"row"<"col-sm-4"l><"col-sm-4"Br><"col-sm-4"f>>t<"row"<"col-sm-5"i><"col-sm-7"p>>',
    			"buttons"		 : [                                        
                                        {
                                            extend: 'excel',
                                            exportOptions: {
                                                columns: [ 0, 1, 2, 3 ]
                                            }
                                        },
                                        {
                                            extend: 'pdf',
                                            exportOptions: {
                                                columns: [ 0, 1, 2, 3 ]
                                            }
                                        },
                                        {
                                            extend: 'print',
                                            exportOptions: {
                                                columns: [ 0, 1, 2, 3 ]
                                            }
                                        }
                                    ],
    		    "scrollX"        : true,
    		    "scrollCollapse" : true,
                "lengthMenu"     : lengthList
    		} );
		}

		function penilaian(){
			$.ajax({
				type: 'POST',
				url: 'url-API/Kelas/Modul/Tugas/',
				data: {"action": "getNilaiTugas", "id_tugas": $('#IDTugas').val(), "id_user": $('#IDPengumpulTugas').val()},
				success: function(res) {
					$('#nilaiTugas').val(res.data.nilai);
					$('#catatanTugas').val(res.data.catatan);
				},
				error: function () {
					swal("Gagal!", "Diskusi gagal ditambahkan!", "error");
				}
			});

			$('#addPenilaian').modal('show');
		}

		function peringatan(){
			swal({
				title: "Maaf",
				text: "Anda Tidak Mengerjakan Tugas!",
				type: "warning",
				confirmButtonColor: "#02adc6",
				cancelButtonClass: 'btn-default btn-md waves-effect',
				confirmButtonText: 'Ya',
			}, function () {
				return false;
			});
		}

		function edit_tugas_siswa(id){

			$.ajax({
				type: 'POST',
				url: 'url-API/Kelas/Modul/Tugas/',
				data: {"action": "showDetilTugasSiswa", "ID": id, "user": '<?=$_SESSION['lms_id']?>'},
				success: function(res) {
					$('#tugas-detil').hide();
					$('#tugas-editor').show();
					$('#judul-editor').text(
					   $('#judul-editor').text().replace('Pembuatan', 'Perbarui')
					).show();
					$('#deskripsi-tugas').html(res.data.deskripsi);

					$('#row-judul').hide();
					$('#row-deadline').hide();
					$('#row-file-upload').hide();
					$('#file_upload_view').val(res.data.file);

					$('#btn-cancel').hide();
					$('#btn-cancel-tugas-siswa').show();
					$('#btn-submit').attr('name', 'updateTugasSiswa');
					tinyMCE.activeEditor.setContent(res.data.deskripsi);

					loaded();
				},
				error: function () {
					swal("Error!", "Data tidak ditemukan!", "error");
				}
			});
		}

		// Destroy action.
		$('#btn-cancel').on('click', function () {
			$('#tugas-preview').show();
			$('#row-deadline').show();
			$('#row-judul').show();
			$('#tugas-editor').hide();
			$('#form_tambah').trigger("reset");
			$('#btn-submit').attr('name', 'addTugas');
			$("#deadline").attr("required", true);
			$('#btn-upload').html('Upload File');

            $('#judul-editor').text('Pembuatan Tugas');
            $('.article-siswa').show();
		});

		$('#btn-cancel-tugas-siswa').on('click', function () {
			$('#tugas-detil').show();
			$('#tugas-editor').hide();
		});

		// Initialize action.
		$('#btn-tambah, #btn-edit').on('click', function () {
			$('#form_tambah').trigger("reset");
            $("#jenis").select2('val', "0");
            $('#jenis').trigger('change');
			$('#tugas-editor').show();
			$('#tugas-preview').hide();
		});

		$(document).on('click', '#btn-lihat-tugas',function( event ) {

			ID = $('#IDTugas').val();
			IDPengumpulTugas = $('#IDPengumpulTugas').val();

			loading();

			$.ajax({
				type: 'POST',
				url: 'url-API/Kelas/Modul/Tugas/',
				data: {"action": "showDetil", "ID": ID, "user": IDPengumpulTugas, "hakKelas": '<?=$hakKelas['status']?>'},
				success: function(res) {
					$('#nama-creator-tugas').text(res.data.nama_guru);
					$('#judul-tugas').text(res.data.judul);
					$('#waktu-tugas').text(res.data.tugas_modified);
					$('#deskripsi-tugas').html(res.data.deskripsi);
					if(res.data.file_lampiran != ''){
						$('#lampiran-tugas').show();
						$('#lampiran-tugas').prop("href", "assets/dokumen/"+res.data.file_lampiran);
					}else{
						$('#lampiran-user').hide();
					}
					if(res.data.file != ''){
						$('#lampiran-user').show();
						$('#lampiran-user').prop("href", "assets/dokumen/"+res.data.file);
					}else{
						$('#lampiran-user').hide();
					}
					$('#nama-user').text(res.data.nama_siswa);
					$('#waktu-kumpul-tugas').text(res.data.kumpul_tugas_modified);
					$('#deskripsi-user').html(res.data.jawaban);
					$('#btn-berikan-penilaian').html(res.data.penilaian);

					MathJax.Hub.Queue(["Typeset",MathJax.Hub,'#deskripsi-user']);
					loaded();
				},
				error: function () {
					swal("Error!", "Data tidak ditemukan!", "error");
				}
			});
		});

		$("#btn-upload").click(function () {
			$("#file_upload").trigger('click');
		});

		$('#file_upload').on('change', function () {

	        var ext = $(this).val().split(".");
	        ext = ext[ext.length-1].toLowerCase();
	        var arrayExtensions = ["jpg" , "jpeg", "png", "bmp", "gif", "pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "zip", "rar"];

	        if (arrayExtensions.lastIndexOf(ext) == -1) {
	            swal({
	                title: "Maaf",
	                text: "Tipe dokumen tidak sesuai!",
	                type: "warning",
	                confirmButtonColor: "#02adc6",
	                cancelButtonClass: 'btn-default btn-md waves-effect',
	                confirmButtonText: 'Ya',
	            }, function () {
					$('#file_upload').val("");
	            });
	        }else{
	            if(this.files[0].size > 20000000){
	                swal({
	                    title: "Perhatian",
	                    text: "Ukuran File Maksimal 20 MB",
	                    type: "warning",
	                    confirmButtonColor: "#02adc6",
	                    cancelButtonClass: 'btn-default btn-md waves-effect',
	                    confirmButtonText: 'Ya',
	                }, function () {
	                    $('#file_upload').val("");
	                });
	            }else{
					$('#file_upload_view').val(this.files[0].name);
				}
	        }
	    });

		$('#btn-cancel-form').on('click', function(){
			$('#addPenilaianForm').trigger("reset");
		});

        $('#jenis').on('change', function () {
            if($(this).val() == "0"){
                $("#div-durasi").hide();
                $("#durasi").attr("required", false);
            }else{
                $("#div-durasi").show();
                $("#durasi").attr("required", true);
            }
        });

        function reset_tugas(ID, id_user){
			var result = confirm("Anda yakin akan mereset tugas anggota kelas ini?");

			loading();

			if(result){
				$.ajax({
					type: 'POST',
					url: 'url-API/Kelas/Modul/Tugas/',
					data: {"action": "resetTugas", "ID": ID, "user": id_user},
					success: function(res) {
						alert('Berhasil direset');
                        $('#tugas-'+id_user).html("<i class='fa fa-clock-o' aria-hidden='true'></i> -<br><span class='label label-warning'>Belum mengumpulkan</span>&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-table' aria-hidden='true'></i> Nilai: -");
						loaded();
					},
					error: function (res) {
						swal("Error!", "Data tidak ditemukan!", "error");
					}
				});
			}
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

			$('.note-statusbar').hide();

			$(".fancybox").fancybox({
				padding: 0,
				openEffect	: 'none',
				closeEffect	: 'none'
			});

			$('#addPenilaianForm').on('submit', function( event ) {

				loading();

				$.ajax({
					type: 'POST',
					url: 'url-API/Kelas/Modul/Tugas/',
					data: {"action": "insertTugas", "id_tugas": $('#IDTugas').val(), "id_user": $('#IDPengumpulTugas').val(), "sekolah":"<?=$FuncProfile['sekolah']?>", "nilai": $('#nilaiTugas').val(), "catatan": $('#catatanTugas').val(), "user": "<?=$_SESSION['lms_id']?>", "kelas":"<?=$infoMapel['id_kelas']?>"},
					success: function(res) {
						$('#addPenilaian').modal('hide');
						swal({
							title: "Berhasil",
							text: "Nilai tugas berhasil ditambahkan!",
							type: "success",
							confirmButtonColor: "#02adc6",
							cancelButtonClass: 'btn-default btn-md waves-effect',
							confirmButtonText: 'Ya',
						}, function () {
							loaded();
						});
					},
					error: function () {
						swal("Gagal!", "Nilai tugas gagal ditambahkan!", "error");
					}
				});

				event.preventDefault();
			});

			$('input[name="deadline"]').daterangepicker({
				singleDatePicker: true,
				showDropdowns: true,
				startDate: "2000-01-01",
				minDate: "<?=date('Y-m-d')?>",
				maxDate: "<?=date('Y')+10;?>-12-31",
				locale: {
					format: 'YYYY-MM-DD'
				}
			});
		});
	</script>

	<script src="assets/js/app.js"></script>
	<script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML">
		MathJax.Hub.Config({
		    extensions: ["mml2jax.js"],
		    jax: ["input/MathML","output/HTML-CSS"]
		});
	</script>
	<script type="text/javascript" src="./assets/tinymce4/js/wirislib.js"></script>
	<script type="text/javascript" src="./assets/tinymce4/js/prism.js"></script>

<?php
	require('includes/footer-bottom.php');
?>
