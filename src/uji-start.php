<?php
    session_start();
    date_default_timezone_set('Asia/Jakarta');

    include 'setting/connection.php';
    spl_autoload_register(function ($class) {
      include 'setting/controller/' .$class . '.php';
    });

    $tugasClass = new Tugas();
    $quizClass  = new Quiz();

    $infoTugas	= $tugasClass->getDetailTugas($_GET['id']);
    $duration   = $infoTugas['durasi'];

    if(!isset($_SESSION["duration"]) OR !isset($_SESSION["start_time"])){
        $_SESSION["duration"]       = $duration;
        $_SESSION["start_time"]     = date("Y-m-d H:i:s");
    }

    $end_time   = date("Y-m-d H:i:s", strtotime('+'.$_SESSION["duration"].'minutes', strtotime($_SESSION["start_time"])));

    $_SESSION["end_time"]   = $end_time;

    $quizClass->setInfoQuizPeserta($_GET['id'], (string)$_SESSION['lms_id'], $_SESSION['lms_sekolah'], "2", "start");

?>
<script type="text/javascript">
    window.location = "uji.php?id=<?=$_GET['id']?>";
</script>
