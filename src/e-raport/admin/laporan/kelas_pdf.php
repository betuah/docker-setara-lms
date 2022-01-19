<?php
session_start();
 // Define relative path from this script to mPDF
 $nama_dokumen='Laporan Data Kelas'; //Beri nama file PDF hasil.
define('_MPDF_PATH','../../mpdf60/');
include(_MPDF_PATH . "mpdf.php");
$mpdf=new mPDF('utf-8', 'A4'); // Create new mPDF Document

//Beginning Buffer to save PHP variables and HTML tags
ob_start();
?>
<!--sekarang Tinggal Codeing seperti biasanya. HTML, CSS, PHP tidak masalah.-->
<!--CONTOH Code START-->
<?php
 //KONEKSI
include "../../config/connect.php";
?>
<!-- <img src="../../images/kop_laporan.png"> -->
<h3 align="center">DATA KELAS</h3>
<br>
<table border="1" cellspacing="0" cellpadding="5">
<tr bgcolor="#64b5f6">
    <th width="60px">Kode Kelas</th>
    <th>Tahun Ajaran</th>
    <th>Kelas</th>
    <th>Nama Kelas</th>
    <th width="280px">Wali Kelas</th>
    <th>Status</th>
</tr>
<?php
$no=1;
$sql=mysql_query("SELECT kelas.*, guru.nama_guru FROM kelas, guru
        WHERE kelas.kode_guru = guru.kode_guru ORDER BY kode_kelas ASC");
while($data=mysql_fetch_array($sql)){
if($no%2==0){ $warna="#bdbdbd";} else { $warna="#FFFFFF";}
?>
<tr bgcolor="<?php echo $warna; ?>">
<td><?php echo $data['kode_kelas']; ?></td>
<td align="center"><?php echo $data['tahun_ajar']; ?></td>
<td align="center"><?php echo $data['kelas']; ?></td>
<td align="center"><?php echo $data['nama_kelas']; ?></td>
<td align="left"><?php echo $data['nama_guru']; ?></td>
<td align="center"><?php echo $data['status']; ?></td>

</tr>
<?php
$no++;
}
?>
</table>
<?php
$html = ob_get_contents(); //Proses untuk mengambil hasil dari OB..
ob_end_clean();
//Here convert the encode for UTF-8, if you prefer the ISO-8859-1 just change for $mpdf->WriteHTML($html);
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output($nama_dokumen.".pdf" ,'I');
exit;
?>
