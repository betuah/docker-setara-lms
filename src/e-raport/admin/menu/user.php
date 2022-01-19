<?php
if(isset($_GET['aksi'])){
  $aksi=$_GET['aksi'];
  if($aksi=="tambahdatauser"){include "aksiuser/tambah.php";}
  elseif($aksi=="editdatauser"){include "aksiuser/edit.php";}
  elseif($aksi=="hapusdatauser"){include "aksiuser/hapus.php";}
  else {include "aksiuser/tampil.php";}
} else { 
    include "aksiuser/tampil.php";
}
?>