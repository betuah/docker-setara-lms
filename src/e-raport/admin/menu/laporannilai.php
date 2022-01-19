<?php
if(isset($_GET['aksi'])){
  $aksi=$_GET['aksi'];
  if($aksi=="lihatdatanilai"){include "aksilaporannilai/cetak.php";}
  else {include "aksilaporannilai/tampil.php";}
} else { 
    include "aksilaporannilai/tampil.php";
}
?>