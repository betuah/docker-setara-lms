<?php
require("includes/header-top.php");
?>
    <script type="text/javascript">
        function mousedwn(e){try{if(event.button==2||event.button==3)return false}catch(e){if(e.which==3)return false}}document.oncontextmenu=function(){return false};document.ondragstart=function(){return false};document.onmousedown=mousedwn
    </script>
    <script type="text/javascript">
        window.addEventListener("keydown",function(e){if(e.ctrlKey&&(e.which==65||e.which==66||e.which==67||e.which==73||e.which==80||e.which==83||e.which==85||e.which==86)){e.preventDefault()}});document.keypress=function(e){if(e.ctrlKey&&(e.which==65||e.which==66||e.which==67||e.which==73||e.which==80||e.which==83||e.which==85||e.which==86)){}return false}
    </script>
    <script type="text/javascript">
        document.onkeydown=function(e){e=e||window.event;if(e.keyCode==123||e.keyCode==18){return false}}
    </script>

<link rel="stylesheet" href="./assets/css/separate/vendor/blockui.min.css">
<link rel="stylesheet" href="assets/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="assets/css/separate/vendor/datatables-net.min.css">
<link rel="stylesheet" href="assets/js/lib/socketio/styles.css">

<link rel="stylesheet" href="./assets/tinymce4/css/prism.css" type="text/css" />
<link rel="stylesheet" href="./assets/css/separate/pages/others.min.css">

<script type="text/javascript" src="./assets/tinymce4/js/tinymce/tinymce.min.js"></script>

<?php
require("includes/header-menu.php");

$kelasClass		= new Kelas();
$mapelClass 	= new Mapel();
$modulClass 	= new Modul();
$quizClass 	    = new Quiz();
$tugasClass     = new Tugas();
$soalClass 	    = new Soal();

$tugas	    = $tugasClass->getDetailTugas($_GET['id']);
$infoModul	= $modulClass->getInfoModul($tugas['id_modul']);
$infoMapel	= $mapelClass->getInfoMapel($infoModul['id_mapel']);

$idModul    = $tugas['id_modul'];

$hakKelas   = $kelasClass->getKeanggotaan($infoMapel['id_kelas'], $_SESSION['lms_id']);

if(!isset($_SESSION["start_time"])){
    //header( "Location: create-quiz.php?modul=$idModul");
    echo "<script>window.location.href='create-uji?id=".$_GET['id']."';</script>";
}

if(isset($_POST['kumpulTugas'])){

    $rest 	= $tugasClass->submitTugas($_SESSION['lms_id'], $FuncProfile['sekolah'], $_POST['ID'], $_POST['deskripsi'], $_FILES['file_upload'], $infoMapel['id_kelas']);
    $quizClass->setInfoQuizPeserta($_GET['id'], (string)$_SESSION['lms_id'], $FuncProfile['sekolah'], "2", "end");
	
    if ($rest['status'] == "Success") {
        unset($_SESSION["start_time"]);
        unset($_SESSION["end_time"]);
        unset($_SESSION["duration"]);

		echo "<script>alert('Ujian berhasil disimpan!'); document.location='create-uji?id=".$tugas['_id']."'</script>";
	}else{
		echo "<script>alert('Gagal Update')</script>";
	}
}

?>
    <script type="text/javascript">document.title = "Uji <?=$infoQuiz['nama']?> - seTARA daring";</script>
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
												<p class="title"><?=$tugas['nama']?></p>
												<p><?=$_SESSION['lms_name']?> (<?=$_SESSION['lms_username']?>)</p>
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
                            <button id="btn-submit-2" class="btn btn-rounded btn-primary" title="Ujian" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Tombol untuk mulai mengerjakan ujian."><i class="font-icon font-icon-play"></i> Kumpulkan</button>
                            <button id="pbtnId" type class="btn btn-rounded btn-primary" style="display: none;">Pause</button>
						</section>
                        <section class="widget widget-time">
                            <header class="widget-header-dark">
                                <i class="fa fa-clock-o"></i> Sisa Waktu
                            </header>
                            <div class="widget-time-content">
                                <div class="count-item">
                                    <div class="count-item-number" id="timer"></div>
                                </div>
                            </div>
                        </section>
                        <section class="widget widget-simple-sm">
                            <div class="widget-simple-sm-icon">
                                <i class="font-icon font-icon-chart-2"></i>
                            </div>
                            <div class="widget-simple-sm-bottom">
                                <div id="led-light">
                                </div>
                                <p id="message"></p></div>
                        </section>
					</aside><!--.profile-side-->
				</div>
                <div class="col-xl-9 col-lg-8">
                    <section class="card card-default" id="tugas-preview">
                        <div class="card-block" id="accordion" id="tugas-preview">
                            <?php
                                $tugasClass->getStatusTugas($tugas['_id'], $_SESSION['lms_id']) ? $statusTugas = "1" : $statusTugas = "0";
                                strtotime((new DateTime())->format('d M Y')) > strtotime((new DateTime($tugas['deadline']))->format('d M Y')) ? $deadline = "1" : $deadline = "0";

                                // if ($_SESSION['lms_id'] == $tugas['creator']) {
                                //if ($hakKelas['status'] == 1 || $_SESSION['lms_id'] == $tugas['creator']) {
                                if ($hakKelas['status'] == 1 || $_SESSION['lms_id'] == $infoMapel['creator']) {
                                
                                } else {
                                    echo '<article id="article-'.$tugas['_id'].'" class="box-typical profile-post panel article-siswa">
                                            <div class="profile-post-header">
                                                <div class="user-card-row">
                                                    <div class="tbl-row">
                                                        <div class="tbl-cell tbl-cell-photo">
                                                            <img src="assets/img/assignment.png" alt="">
                                                        </div>
                                                        <div class="tbl-cell">
                                                            <div class="user-card-row-name">'.$tugas['nama'].'<span class="pull-right"><b><i class="fa fa-lock"></i> Kode Ujian</b> &nbsp; : <u>'.$tugas['kode'].'</u></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="demo">
                                                <div class="profile-post-content">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span><b><i class="fa fa-book"></i> Bentuk Pengerjaan</b> &nbsp; : '.($tugas['jenis']=='1'?'Ujian':'Penugasan')
                                                            .($tugas['jenis']=='1'?'<br><span><b><i class="fa fa-clock-o"></i> Durasi</b> &nbsp; : <u>'.$tugas['durasi'].' menit</u>':'').'
                                                        </div>
                                                    </div>
                                                    <hr style="margin: 10px 0;">
                                                    '.$tugas["deskripsi"].'
                                                    '.(isset($tugas["file"]) && !empty($tugas["file"]) ? '<a target="_blank" href="assets/dokumen/'.$tugas["file"].'"><span class="font-icon font-icon-clip"></span> File Lampiran</a>' : '').'
                                                    <a id="kerjakan-ujian" onclick="kerjakan_tugas(\''.$tugas['_id'].'\')" title="Ujian" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk mengerjakan ujian." class="btn btn-rounded btn-primary pull-right" style="display: none;">Mulai Ujian</a>
                                                </div>
                                            </div>
                                        </article>';
                                }
                            ?>
                        </div>
                    </section>
                    <section class="card card-default" id="tugas-editor" style="display: none;">
                        <div class="card-block">
                            <h5 class="with-border" id="judul-editor">Lembar Jawaban Ujian</h5>

                            <form id="form_tambah" method="POST" enctype="multipart/form-data">
                                <div class="form-group row" id="row-judul">
                                    <label class="col-md-2 form-control-label">Judul</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Judul Tugas" />
                                        <input type="text" class="form-control" id="kode" name="kumpulTugas" placeholder="Kode Tugas" value="<?=$_GET['id']?>" />
                                    </div>
                                </div>
                                <div class="form-group row" id="div-bentuk-pengerjaan">
                                    <label class="col-md-2 form-control-label">Bentuk Pengerjaan</label>
                                    <div class="col-md-8">
                                        <select class="select2" id="jenis" name="jenis">
                                            <option value="0" selected>Penugasan</option>
                                            <option value="1">Dijadikan Ujian</option>
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
                                        <input type="text" class="form-control" id="IDTugas" name="ID" placeholder="ID Tugas" value="<?=$_GET['id']?>" />
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
                                    <div class="col-md-12">
                                        <div id="editorContainer">
                                            <div id="toolbarLocation"></div>
                                            <textarea id="editormce" class="form-control wrs_div_box" contenteditable="true" tabindex="0" spellcheck="false" aria-label="Rich Text Editor, example"></textarea>
                                            <input id="editor" type="text" name="deskripsi" style="display: none;" />
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </form>
                            <div class="form-group pull-right">
                                <button id="btn-submit" class="btn">Kumpulkan</button>
                            </div>
                        </div>
                    </section>
                </div>
			</div><!--.row-->
		</div><!--.container-fluid-->

	</div><!--.page-content-->

    <!-- Old -->

<?php
	require('includes/footer-top.php');
?>
    <script src="assets/js/lib/socketio/socket.io.js"></script>
    <script src="assets/js/lib/datatables-net/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.countdownTimer.js"></script>


	<script>
        const socket  = io.connect('http://103.52.145.197:3000')

        socket.on('pong', ms => {
            if(ms > 500) {
                $('#led-light').attr('class', 'led-yellow')
                $('#message').text('Bad Connection!')
                if($('#pbtnId').text() == 'Pause'){
                    $('#pbtnId').click()
                    loading('Pastikan kamu terhubung jaringan internet dengan baik!')
                }
            } else {
                $('#led-light').attr('class', 'led-blue')
                $('#message').text('Good Connection!')
                if($('#pbtnId').text() == 'Resume'){
                    $('#pbtnId').click()
                    loaded()
                }
            }
        });

        socket.on('disconnect', msg => {
            $('#led-light').attr('class', 'led-red')
            $('#message').text('Disconnected!')
            if($('#pbtnId').text() == 'Pause'){
                $('#pbtnId').click()
                loading('Pastikan kamu terhubung jaringan internet dengan baik!')
            }
        });

        socket.on('connect', () => {
            if(socket.connected) {
                $('#led-light').attr('class', 'led-blue')
                $('#message').text('Connected!')
            }
        });

        var kumpulkan = 0;


		$(document).ready(function() {

            $('#kerjakan-ujian').click();

            $('#btn-submit,#btn-submit-2').click(function() {
                swal({
                    title: "Apakah anda yakin?",
                    text: "Akan Mengumpulkan Sekarang!",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "Tidak",
                    confirmButtonText: "Ya",
                    confirmButtonClass: "btn-primary",
                    showLoaderOnConfirm: true
                }, function () {
                    $("#editor").val(tinyMCE.get('editormce').getContent());
                    $("#form_tambah").submit();
                });
            });

            var jam         = <?=gmdate("H",strtotime($_SESSION['end_time'])-strtotime(date('Y-m-d H:i:s')))?>;
            var menit       = <?=gmdate("i",strtotime($_SESSION['end_time'])-strtotime(date('Y-m-d H:i:s')))?>;
            var detik       = <?=gmdate("s",strtotime($_SESSION['end_time'])-strtotime(date('Y-m-d H:i:s')))?>;

			$('.note-statusbar').hide();

            $('#timer').countdowntimer({
				hours : jam,
				minutes : menit,
				seconds: detik,
				size : "lg",
				timeUp : timeisUp,
                pauseButton : "pbtnId"
			});

			function timeisUp() {
				//alert("Waktu pengerjaan sudah habis");
                swal({
                    title: "Maaf",
                    text: "Waktu Anda Sudah Habis!",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonText: "Ya",
                    confirmButtonClass: "btn-primary",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                    allowEscapeKey: false
                }, function () {
                    document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=" + window.location.pathname); });
                    document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=" + "/url-API"); });
                    window.onbeforeunload = null;
                    document.location.href="quiz.php?id=<?php echo $_GET['id']; ?>&paket=<?=$infoQuiz['id_paket']?>&submit="+total_nilai_quiz;
                });

				setTimeout(function() {
					window.location.href = $("a")[0].href;
				}, 2000);
			}
		});


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

	</script>

	<script src="assets/js/app.js"></script>
    <script type="text/javascript" src="./assets/js/lib/blockUI/jquery.blockUI.js"></script>
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
