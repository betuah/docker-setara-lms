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

<?php
require("includes/header-menu.php");

$quizClass 	    = new Quiz();
$soalClass 	    = new Soal();

$infoQuiz	    = $quizClass->getInfoQuiz($_GET['id']);
$list_soal      = $quizClass->getQuizPublish($_GET['id']);
$idModul        = $infoQuiz['id_modul'];
$key            = strtotime($_SESSION["start_time"]);

if(isset($_GET['paket'])){
    //$jumlah_soal    = $soalClass->getNumberbyQuiz($_GET['paket']);
}

if(!isset($_SESSION["start_time"])){
    //header( "Location: create-quiz.php?modul=$idModul");
    echo "<script>window.location.href='create-quiz?modul=".$infoQuiz['id_modul']."';</script>";
}

if(!isset($_COOKIE["total_nilai_quiz"])) {
    setcookie("total_nilai_quiz", 0, time() + (86400 * 30), "/quiz.php");
}

if(isset($_GET['submit'])){
    $nilaiQuiz  = $_GET['submit'];
    $user       = $_GET['user'];
    $sekolah    = $list_soal['id_sekolah'];

    //$nilaiQuiz      = $quizClass->hitungNilaiQuiz($_SESSION['lms_id'], '5cda7233a2f3841d02b1a307', $jumlah_soal);
    $quizClass->submitQuiz($user, $sekolah, $_GET['id'], $nilaiQuiz, $_GET['jumlah_soal']);
    $quizClass->setInfoQuizPeserta($_GET['id'], $user, $sekolah, "3", "end");

    unset($_SESSION["start_time"]);
    unset($_SESSION["end_time"]);
    unset($_SESSION["duration"]);

    echo "<script>window.location.href='create-quiz?modul=".$infoQuiz['id_modul']."';</script>";
}
?>
    <script type="text/javascript">document.title = "Ulangan <?=$infoQuiz['nama']?> - seTARA daring";</script>
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
												<p class="title"><?=$infoQuiz['nama']?></p>
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
                            <!-- <button id="submit-2" class="btn btn-rounded btn-primary" title="Ulangan" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Tombol untuk mulai mengerjakan ulangan." disabled><i class="font-icon font-icon-play"></i> Kumpulkan</button> -->
                            <button id="submit-2" class="btn btn-rounded btn-primary" title="Ulangan" data-toggle="popover" data-placement="right" data-trigger="hover" data-content="Tombol untuk mulai mengerjakan ulangan."><i class="font-icon font-icon-play"></i> Kumpulkan</button>
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
                    <table id="example" class="display table table-striped" cellspacing="0" width="100%">
                        <thead style="display: none;">
                            <tr>
                                <th>Soal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $jumlah_soal = sizeof($list_soal['daftar_soal']);
                                $page = 0;

                                if($infoQuiz['random_soal'] == "1"){
                                    $daftar_soal = $quizClass->getRandom($list_soal['daftar_soal']);
                                }else{
                                    $daftar_soal = $list_soal['daftar_soal'];
                                }

                                foreach ($daftar_soal as $index => $this_soal) {
                            ?>
                                <tr>
                                    <td>
                                        <?php
                                            $page++;
                                            $header_soal        = "<h5 class='with-border'>Nomor ".$page."<span class='pull-right'>".$page." dari ".$jumlah_soal."</span></h5>";
                                            
                                            echo $header_soal;
                                            echo $this_soal['soal'];
                                        
                                            $jawaban_user       = $_COOKIE["$key"."-"."$this_soal[_id]"];//$soalClass->getOpsiJawabanUser($_SESSION['lms_id'], $id_quiz, $doc['_id']);
                                    
                                            if($infoQuiz['random_opsi'] == "1"){
                                                $daftar_opsi = $quizClass->getRandom($this_soal['opsi']);
                                            }else{
                                                $daftar_opsi = $this_soal['opsi'];
                                            }

                                            foreach ($daftar_opsi as $opsi_soal) {
                                                if($opsi_soal['_id'] == $jawaban_user){
                                                    $checked    = "checked";
                                                }else{
                                                    $checked    = "";
                                                }

                                                if($opsi_soal['status'] == 'benar'){
                                                    $status_soal = 1;
                                                }else{
                                                    $status_soal = 0;
                                                }

                                                echo "<div class='radio'>
                                                            <input type='radio' name='".$this_soal['_id']."' id='".$opsi_soal['_id']."' value='".$opsi_soal['_id']."' onclick=save_answer('".$_GET['id']."','".base64_encode(rand(0,1000)."#".$status_soal."#".rand(0,1000))."','".$this_soal['_id']."','".$opsi_soal['_id']."') ".$checked.">
                                                            <label for='".$opsi_soal['_id']."'>".$opsi_soal['text']."</label>
                                                        </div>";
                                            }
                                        ?>
                                        
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
			</div><!--.row-->
            <div class="row">
				<div class="col-xl-12 col-lg-12">
                    <hr />
                    <div class="form-group pull-right">
                        <button id="submit" type class="btn btn-rounded btn-primary" style="display: none;">Kumpulkan</button>
                    </div>
				</div>
			</div><!--.row-->
		</div><!--.container-fluid-->

	</div><!--.page-content-->

    <!-- Old -->

<?php
	require('includes/footer-top-evaluasi.php');
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

        window.onbeforeunload = function() {
            return "Leaving this page will reset the wizard";
        };

        var key = "<?=strtotime($_SESSION['start_time'])?>";

        var total_nilai_quiz = parseInt(getCookie("total_nilai_quiz"));
        if(!total_nilai_quiz){
            total_nilai_quiz = 0;
        }
        var kumpulkan = 0;
        var jumlah_soal = <?=$jumlah_soal?>;

        var table = $('#example').DataTable( {
            // "processing"    : true,
            // "bServerSide"   : true,
            // "bInfo"         : false,
            // "sAjaxSource"   : "url-API/Kelas/Modul/Quiz/Soal/Load?quiz="+"<?=$_GET['id']?>"+"&opsi="+"<?=$infoQuiz['random_opsi']?>",
            // "deferRender"   : true,
            // "aoColumns"     : [
            //                     { "bVisible": false, "mDataProp": "id_paket" },
            //                     { "bVisible": false, "mDataProp": "id_paket" },
            //                     { "mDataProp": "view_soal" }
            //                     ],
            // "searchCols"    : [null, {"sSearch": "<?=$infoQuiz['id_paket']?>"}, null, null],
            // "order"         : [[1, 'asc']],
            "order"         : false,
            "pageLength"    : 1,
            "lengthChange"  : false,
            "language"      : {
                                    "infoFiltered"  : ""
                                },
            "sDom"          : '<"top"p>',
            "drawCallback": function( settings ) {
               if(kumpulkan==1){
                   $("#submit").show();
                //    $("#submit-2").prop('disabled', false);
               }else{
                   $("#submit").hide();
                //    $("#submit-2").prop('disabled', true);
               }
               loaded();
            }
        } );


        $("#example").on('page.dt', function () {
            loading('Memuat soal ...');
            var info = table.page.info();

            if(info.page+1 == info.pages){
                kumpulkan = 1;
            }else{
                kumpulkan = 0;
            }
        });

		$(document).ready(function() {

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
                    document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=" + window.location.pathname); });
                    window.onbeforeunload = null;
                    document.location.href="quiz.php?id=<?php echo $_GET['id']; ?>&paket=<?=$infoQuiz['id_paket']?>&submit="+total_nilai_quiz+"&jumlah_soal="+jumlah_soal+"&user=<?=$_SESSION['lms_id']?>";
                });

				setTimeout(function() {
					window.location.href = $("a")[0].href;
				}, 2000);
			}
		});

        function save_answer(id_quiz, status_soal, id_soal, id_opsi_soal){
            var status = atob(status_soal);
            status = status.split('#')[1];

            if(status == 1){
                status = 'benar';
            }else{
                status = 'salah';
            }

            loading('Menyimpan jawaban ...');
            $.ajax({
                url: "url-API/Kelas/Modul/Quiz/",
                type: 'POST',
                data: {"action": "saveAnswerNew", "id_quiz": id_quiz, "id_soal": id_soal, "id_opsi_soal": id_opsi_soal},
                success: function (data) {

                    if(status=='benar'){
                        if(getCookie(key+"-"+id_soal+"-status") == ""){
                            total_nilai_quiz++;
                        }else{
                            if(getCookie(key+"-"+id_soal+"-status")=='salah'){
                                total_nilai_quiz++;
                            }
                        }
                    }else{
                        if(getCookie(key+"-"+id_soal+"-status") == ""){
                            //do nothing
                        }else{
                            if(getCookie(key+"-"+id_soal+"-status")=='benar'){
                                total_nilai_quiz--;
                            }
                        }
                    }
                    loaded();

                    setCookie(key+"-"+id_soal, id_opsi_soal, '18 Dec 2013 12:00:00 UTC', window.location.pathname);
                    setCookie(key+"-"+id_soal+"-status", status, '18 Dec 2013 12:00:00 UTC', window.location.pathname);
                    setCookie("total_nilai_quiz", total_nilai_quiz, '18 Dec 2013 12:00:00 UTC', window.location.pathname);
                }
            });
        }

        $('#submit,#submit-2').on('click', function() {
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
                document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=" + window.location.pathname); });
                document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=" + window.location.pathname); });
                document.cookie = 'my_cookie=; path=/url-API; domain=setara.kemdikbud.go.id; expires=' + new Date(0).toUTCString();
                window.onbeforeunload = null;
                document.location.href="quiz.php?id=<?php echo $_GET['id']; ?>&paket=<?php echo $_GET['paket']; ?>&submit="+total_nilai_quiz+"&jumlah_soal="+jumlah_soal+"&user=<?=$_SESSION['lms_id']?>";
            });
        });

        function getCookie(cname) {
            var name = cname + "=",
                ca = document.cookie.split(';'),
                i,
                c,
                ca_length = ca.length;
            for (i = 0; i < ca_length; i += 1) {
                c = ca[i];
                while (c.charAt(0) === ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) !== -1) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function setCookie(variable, value, expires_seconds, mypath) {
            var d = new Date();
            d = new Date(d.getTime() + 1000 * expires_seconds);
            document.cookie = variable + '=' + value + '; expires=' + d.toGMTString() + '; path=' + mypath;
        }
	</script>

	<script src="assets/js/app.js"></script>
    <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML">
    </script>
    <script type="text/javascript" src="./assets/js/lib/blockUI/jquery.blockUI.js"></script>

<?php
	require('includes/footer-bottom.php');
?>
