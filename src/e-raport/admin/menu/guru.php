 <?php
if(isset($_GET['aksi'])){
  $aksi=$_GET['aksi'];
  if($aksi=="tambahdataguru"){include "aksiguru/tambah.php";}
  elseif($aksi=="editdataguru"){include "aksiguru/edit.php";}
  elseif($aksi=="hapusdataguru"){include "aksiguru/hapus.php";}
  else {include "aksiguru/tampil.php";}
} else { 
    include "aksiguru/tampil.php";
}
?>



 