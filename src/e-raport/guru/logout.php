<?php
include"../config/connect.php";
session_start();
session_destroy();
header('location:../index.php');
?>