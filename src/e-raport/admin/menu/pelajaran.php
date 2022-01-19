<?php
if(isset($_GET['aksi'])){
  $aksi=$_GET['aksi'];
  if($aksi=="tambahdatapelajaran"){include "aksipelajaran/tambah.php";}
  elseif($aksi=="editdatapelajaran"){include "aksipelajaran/edit.php";}
  elseif($aksi=="hapusdatapelajaran"){include "aksipelajaran/hapus.php";}
  else {include "aksipelajaran/tampil.php";}
} else { 
    include "aksipelajaran/tampil.php";
}
?>