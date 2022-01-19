<?php
if(isset($_GET['aksi'])){
  $aksi=$_GET['aksi'];
  if($aksi=="tambahdatasiswa"){include "aksisiswa/tambah.php";}
  elseif($aksi=="editdatasiswa"){include "aksisiswa/edit.php";}
  elseif($aksi=="hapusdatasiswa"){include "aksisiswa/hapus.php";}
  else {include "aksisiswa/tampil.php";}
} else { 
    include "aksisiswa/tampil.php";
}
?>