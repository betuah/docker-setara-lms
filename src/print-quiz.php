<?php
    session_start();
    if(!isset($_SESSION['lms_id'])){
        header('Location: index.php');
    }
    include 'setting/connection.php';
    spl_autoload_register(function ($class) {
        include 'setting/controller/' .$class . '.php';
    });

    $mapelClass     = new Mapel();
    $modulClass     = new Modul();
    $quizClass 	    = new Quiz();
    $soalClass 	    = new Soal();

    $infoQuiz	    = $quizClass->getInfoQuiz($_GET['id']);
    $idModul        = $infoQuiz['id_modul'];
    $infoModul      = $modulClass->getInfoModul($idModul);
    $idMapel        = $infoModul['id_mapel'];
    $infoMapel      = $mapelClass->getInfoMapel($idMapel);
    $show_jawaban   = false;

    if(isset($_GET['paket'])){
        $list_soal      = $soalClass->getListSoalbyQuiz($_GET['paket'], $infoQuiz['random_soal']);
        $jumlah_soal    = $soalClass->getNumberbyQuiz($_GET['paket']);
    }

    if(isset($_POST['answer'])){
        $list_soal              = unserialize(base64_decode($_POST['list_soal']));
        $bank_list_opsi_soal    = unserialize(base64_decode($_POST['list_opsi_soal']));

        if($_POST['answer'] == true){
            $show_jawaban = false;
        }else{
            $show_jawaban = true;
        }
    }else{
        $bank_list_opsi_soal    = array();
    }

?>

<html>
    <head lang="en">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Print Soal Penilaian - seTARA Daring</title>
        <link href="assets/img/favicon.ico" rel="shortcut icon">
        <link rel="stylesheet" href="assets/css/separate/elements/player.min.css">
        <link rel="stylesheet" href="assets/css/separate/vendor/fancybox.min.css">
        <link rel="stylesheet" href="assets/css/lib/bootstrap-sweetalert/sweetalert.css">
        <link rel="stylesheet" href="assets/css/separate/pages/profile-2.min.css">
        <link rel="stylesheet" href="assets/css/lib/font-awesome/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/lib/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/main.css">
        <link rel="stylesheet" href="assets/css/separate/pages/widgets.min.css">
    </head>

    <body>
    	<div class="page-content" style="padding-top: 10px;">
    		<div class="container-fluid">
    			<div class="row">
    				<div class="col-xl-12 col-lg-12">
                        <button class="btn btn-rounded btn-primary" id="print" style="margin-top: 20px;"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                        <?php if($show_jawaban == false){ ?>
                            <button class="btn btn-rounded btn-primary btn-show-answer" id="print" style="margin-top: 20px;"><i class="fa fa-eye" aria-hidden="true"></i> Lihat Jawaban</button>
                        <?php }else{ ?>
                            <button class="btn btn-rounded btn-primary btn-show-answer" id="print" style="margin-top: 20px;"><i class="fa fa-eye-slash" aria-hidden="true"></i> Sembunyikan Jawaban</button>
                        <?php } ?>
                        <hr />
                        <h2 class="text-center">Kuis <?=$infoQuiz['nama']?></h2>
                        <h5>Modul&emsp;&emsp;&emsp;&emsp; : <?=$infoModul['nama']?></h5>
                        <h5>Mata Pelajaran&nbsp; : <?=$infoMapel['nama']?></h5>
                    <?php
                        $no_soal = 1;
                        foreach ($list_soal as $soal) {
                            $jawaban_user = $soalClass->getOpsiJawabanUser($_SESSION['lms_id'], $_GET['id'], $soal['_id']);
                    ?>
                        <section class="card card-default">
                            <div class="card-block">
                                <h5 class="with-border">Nomor <?=$no_soal?><span class="pull-right"><?=$no_soal?> / <?=$jumlah_soal?></span></h5>
                                <p id="soal"><?php echo $soal["soal"];?></p>
                                <?php
                                    if(!isset($_POST['answer'])){
                                        $bank_list_opsi_soal[$no_soal] = $soalClass->getListOpsiSoal($soal['_id'], $infoQuiz['random_opsi']);
                                    }
                                    $list_opsi_soal = $bank_list_opsi_soal[$no_soal];

                                    foreach ($list_opsi_soal as $opsi_soal) {
                                ?>
                                <div class="checkbox">
                                    <?php if($opsi_soal['status'] == 'benar' and $show_jawaban){?>
                                        <i class="fa fa-circle" aria-hidden="true"></i><label style="margin-top: -20px; margin-left: 24px;"><?=$opsi_soal['text']?></label>
                                    <?php }else{ ?>
                                        <input type="radio" name="<?=$soal['_id']?>" id="<?=$opsi_soal['_id']?>" value="<?=$opsi_soal['_id']?>" onclick="save_answer('<?=$_GET['id']?>', '<?=$soal['_id']?>', '<?=$opsi_soal['_id']?>');">
                                        <label><?=$opsi_soal['text']?></label>
                                    <?php } ?>

                                </div>
                                <?php
                                    }
                                ?>
                            </div>
                        </section>
                    <?php
                            $no_soal++;
                        }
                    ?>
                    <form id="form_show_answer" class="" action="" method="post" style="display: none;">
                        <input type="text" name="list_soal" value="<?=base64_encode(serialize($list_soal))?>">
                        <input type="text" name="list_opsi_soal" value="<?=base64_encode(serialize($bank_list_opsi_soal))?>">
                        <input type="text" name="answer" value="<?=$show_jawaban?>">
                    </form>
    				</div>
    			</div><!--.row-->
    		</div><!--.container-fluid-->
    	</div><!--.page-content-->

        <script src="assets/js/lib/jquery/jquery.min.js"></script>
        <script type="text/javascript" async src="//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML"></script>

        <script>
            $(document).ready(function(){
                $('#print').click(function(event){
                    $(this).hide();
                    $('.btn-show-answer').hide();
                    window.print();
                    $(this).show();
                    $('.btn-show-answer').show();
                });

                $('.btn-show-answer').click(function () {
                    $("#form_show_answer").submit();
                });

                $('.radio').click(function () {});
            });
            
            $(window).load(function() {
                writeLog('access', "<?=$_SESSION['lms_id']?>", "<?=$logIDKelas?>");
            });

            function writeLog(act, uid, kls){
                $.ajax({
                    type: 'POST',
                    url: 'url-API/log.php',
                    data: {"action": act, "uid":uid, "kls":kls, 'pg':document.title, 'url':document.URL},
                    success: function(res) {
                        console.log(res.message);
                    },
                    error: function () {
                        console.log('Gagal menuliskan ke dalam Log! '+document.title);
                    }
                });
            }
            
            $( document ).ready(function() {
                $('.wrs_tickContainer').remove();
            });
        </script>
    </body>
</html>
