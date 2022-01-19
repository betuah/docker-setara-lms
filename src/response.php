<?php
    session_start();

    $from_time1 = date('Y-m-d H:i:s');
    if(isset($_SESSION["end_time"])){
        $to_time1   = $_SESSION["end_time"];
    }else{
        $to_time1   = date('Y-m-d H:i:s');
    }

    $timefirst  = strtotime($from_time1);
    $timesecond  = strtotime($to_time1);

    $differenceinseconds    = $timesecond - $timefirst;

    if(!isset($_SESSION["duration"])){
        echo "finished";
    }elseif($differenceinseconds == 0){
        unset($_SESSION["start_time"]);
        unset($_SESSION["end_time"]);
        unset($_SESSION["duration"]);
        echo "00:00:00";
    }else{
        echo gmdate("H:i:s", $differenceinseconds);
    }

?>
