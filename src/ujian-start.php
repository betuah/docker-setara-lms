<?php
    session_start();
    date_default_timezone_set('Asia/Jakarta');

    include 'setting/connection.php';
    spl_autoload_register(function ($class) {
      include 'setting/controller/' .$class . '.php';
    });

    $quizClass 	= new Quiz();
    $soalClass 	= new Soal();

    $infoQuiz	= $quizClass->getQuizPublish($_GET['id']);
    $duration   = $infoQuiz['durasi'];

    if(!isset($_SESSION["duration"]) OR !isset($_SESSION["start_time"])){
        $_SESSION["duration"]       = $duration;
        $_SESSION["start_time"]     = date("Y-m-d H:i:s");
    }

    $end_time   = date("Y-m-d H:i:s", strtotime('+'.$_SESSION["duration"].'minutes', strtotime($_SESSION["start_time"])));

    $_SESSION["end_time"]   = $end_time;

    $quizClass->setInfoQuizPeserta($_GET['id'], (string)$_SESSION['lms_id'], $infoQuiz['id_sekolah'], "1", "start");

?>
<script type="text/javascript">
    window.location = "ujian.php?id=<?=$_GET['id']?>&paket=<?=$_GET['paket']?>";
</script>
