<?php
require("includes/header-top.php");
?>

<link rel="stylesheet" href="./assets/css/separate/vendor/blockui.min.css">
<link rel="stylesheet" href="assets/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="assets/css/separate/vendor/datatables-net.min.css">

<?php
require("includes/header-menu.php");

$quizClass 	    = new Quiz();
$soalClass 	    = new Soal();

$infoQuiz	    = $quizClass->getInfoQuiz($_GET['id']);
$idModul        = $infoQuiz['id_modul'];

if(isset($_GET['paket'])){
    //$jumlah_soal    = $soalClass->getNumberbyQuiz($_GET['paket']);
}

if(!isset($_SESSION["start_time"])){
    //header( "Location: create-quiz.php?modul=$idModul");
    echo "<script>window.location.href='create-quiz?modul=$idModul';</script>";
}

if(!isset($_COOKIE["total_nilai_quiz"])) {
    setcookie("total_nilai_quiz", 0, time() + (86400 * 30), "/quiz-temp.php");
}

if(isset($_GET['submit'])){
    $nilaiQuiz=$_GET['submit'];

    //$nilaiQuiz      = $quizClass->hitungNilaiQuiz($_SESSION['lms_id'], '5cda7233a2f3841d02b1a307', $jumlah_soal);
    $quizClass->submitQuiz((string)$_SESSION['lms_id'], $_GET['id'], $nilaiQuiz);

    unset($_SESSION["start_time"]);
    unset($_SESSION["end_time"]);
    unset($_SESSION["duration"]);

    echo "<script>window.location.href='create-quiz?modul=$idModul';</script>";
}

?>
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
												<p class="title"><?=$infoQuiz['nama']?></p>
												<p>Sisa Waktu <span id="timer"></span></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--.profile-header-photo-->

		<div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <table id="example" class="display table table-striped" cellspacing="0" width="100%">
                        <thead style="display: none;">
                            <tr>
                                <th>Id</th>
                                <th>Id Paket</th>
                                <th>Soal</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

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

<?php
	require('includes/footer-top-evaluasi.php');
?>
    <script src="assets/js/lib/datatables-net/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.countdownTimer.js"></script>

	<script>
        window.onbeforeunload = function() {
            return "Leaving this page will reset the wizard";
        };

        var total_nilai_quiz = parseInt(getCookie("total_nilai_quiz"));
        var kumpulkan = 0;

        var table = $('#example').DataTable( {
            "processing"    : true,
            "bServerSide"   : true,
            "bInfo"         : false,
            "sAjaxSource"   : "url-API/Kelas/Modul/Quiz/Soal/Load-temp?quiz="+"<?=$_GET['id']?>"+"&opsi="+"<?=$infoQuiz['random_opsi']?>",
            "deferRender"   : true,
            "aoColumns"     : [
                                { "bVisible": false, "mDataProp": "id_paket" },
                                { "bVisible": false, "mDataProp": "id_paket" },
                                { "mDataProp": "view_soal" }
                                ],
            "searchCols"    : [null, {"sSearch": "<?=$_GET['paket']?>"}, null, null],
            "order"         : [[1, 'asc']],
            "pageLength"    : 1,
            "lengthChange"  : false,
            "language"      : {
                                    "infoFiltered"  : ""
                                },
            "sDom"          : '<"top"p>',
            "drawCallback": function( settings ) {
               if(kumpulkan==1){
                   $("#submit").show();
               }else{
                   $("#submit").hide();
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
				timeUp : timeisUp
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
                    document.location.href="quiz.php?id=<?php echo $_GET['id']; ?>&paket=<?php echo $_GET['paket']; ?>&submit="+total_nilai_quiz;
                });

				setTimeout(function() {
					window.location.href = $("a")[0].href;
				}, 2000);
			}
		});

        function save_answer(id_quiz, id_soal, id_opsi_soal, status){
            loading('Menyimpan jawaban ...');
            $.ajax({
                url: "url-API/Kelas/Modul/Quiz/",
                type: 'POST',
                data: {"action": "saveAnswerNew", "id_quiz": id_quiz, "id_soal": id_soal, "id_opsi_soal": id_opsi_soal},
                success: function (data) {

                    if(status=='benar'){
                        if(getCookie(id_soal+"-status") == ""){
                            total_nilai_quiz++;
                        }else{
                            if(getCookie(id_soal+"-status")=='salah'){
                                total_nilai_quiz++;
                            }
                        }
                    }else{
                        if(getCookie(id_soal+"-status") == ""){
                            //do nothing
                        }else{
                            if(getCookie(id_soal+"-status")=='benar'){
                                total_nilai_quiz--;
                            }
                        }
                    }
                    loaded();

                    setCookie(id_soal, id_opsi_soal, '18 Dec 2013 12:00:00 UTC', "/url-API");
                    setCookie(id_soal+"-status", status, '18 Dec 2013 12:00:00 UTC', window.location.pathname);
                    setCookie("total_nilai_quiz", total_nilai_quiz, '18 Dec 2013 12:00:00 UTC', window.location.pathname);
                }
            });
        }

        $('#submit').on('click', function() {
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
                document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=" + "/url-API"); });
                window.onbeforeunload = null;
                document.location.href="quiz.php?id=<?php echo $_GET['id']; ?>&paket=<?php echo $_GET['paket']; ?>&submit="+total_nilai_quiz;
            });
        });

        $('#next-soal').on('click', function() {
            // $('#block-soal').block({
            //     message: '<div class="blockui-default-message"><i class="fa fa-circle-o-notch fa-spin"></i><h6>Mohon Tunggu</h6></div>',
            //     overlayCSS:  {
            //         background: 'rgba(24, 44, 68, 0.8)',
            //         opacity: 1,
            //         cursor: 'wait'
            //     },
            //     css: {
            //         width: '50%'
            //     },
            //     blockMsgClass: 'block-msg-default'
            // });

            $.ajax({
                type: 'POST',
                url: 'url-API/Kelas/Modul/Quiz/',
                data: {"action": "showSoal", "ID": $("#id-soal").text()},
                success: function(res) {
                    alert("Soalnya: "+res);
                    $("#block-soal").load("quiz.php #block-soal");
                },
                error: function () {
                    alert("gagal");
                }
            });
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
      				url: 'url-API/Kelas/Modul/Materi/',
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
