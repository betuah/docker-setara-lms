<?php
if(isset($_GET['aksi'])){
  $aksi=$_GET['aksi'];
  if($aksi=="tambahdatakelassiswa"){include "aksikelassiswa/tambah.php";}
  elseif($aksi=="editdatakelassiswa"){include "aksikelassiswa/edit.php";}
  elseif($aksi=="hapusdatakelassiswa"){include "aksikelassiswa/hapus.php";}
  else {include "aksikelassiswa/tampil.php";}
} else { 
    include "aksikelassiswa/tampil.php";
}
?>