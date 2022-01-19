<?php
include"../config/connect.php";


//harus selalu gunakan variabel term saat memakai autocomplete,
//jika variable term tidak bisa, gunakan variabel q
$term = trim(strip_tags($_GET['term']));
 
$qstring = "SELECT kode_siswa, nis,  nama_siswa FROM siswa WHERE nama_siswa  LIKE '".$term."%'";
//query database untuk mengecek tabel anime 
$result = mysql_query($qstring);
 
while ($row = mysql_fetch_array($result))
{
    $row['value']=htmlentities(stripslashes($row['nama_siswa'].' | '.$row['kode_siswa'].' | '.$row['nis']));
    $row['kode_siswa']=(int)$row['kode_siswa'];
//buat array yang nantinya akan di konversi ke json
    $row_set[] = $row;
}
//data hasil query yang dikirim kembali dalam format json
echo json_encode($row_set);

?>