<?php
if(isset($_GET['aksi'])){
  $aksi=$_GET['aksi'];
  if($aksi=="tambahdatanilai"){include "aksinilai/tambah.php";}
  elseif($aksi=="editdatanilai"){include "aksinilai/edit.php";}
  elseif($aksi=="hapusdatanilai"){include "aksinilai/hapus.php";}
  else {include "aksinilai/tampil.php";}
} else { 
    include "aksinilai/tampil.php";
}
?>