<?php
if(isset($_GET['aksi'])){
  $aksi=$_GET['aksi'];
  if($aksi=="tambahdatakelas"){include "aksikelas/tambah.php";}
  elseif($aksi=="editdatakelas"){include "aksikelas/edit.php";}
  elseif($aksi=="hapusdatakelas"){include "aksikelas/hapus.php";}
  else {include "aksikelas/tampil.php";}
} else { 
    include "aksikelas/tampil.php";
}
?>