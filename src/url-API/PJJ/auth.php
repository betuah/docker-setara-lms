<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type');

session_start();
require("../../setting/connection.php");
require("../../setting/connection-log.php");

$method	= $_GET;
$table  = $db->user;

if(!isset($_SESSION['lms_id'])){
    $data   = $table->findOne(['_id' => new MongoId($_GET['id'])]);

    $_SESSION['lms_id']             = $data['_id'];
    $_SESSION['lms_username']       = $data['username'];
    $_SESSION['lms_name']           = $data['nama'];
    $_SESSION['lms_status']         = $data['status'];
    $_SESSION['lms_update']         = $data['update'];
}

header('Location: http://pjj.disdik.jabarprov.go.id/jus/');
?>
