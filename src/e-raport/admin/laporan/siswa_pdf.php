<?php
session_start();
 // Define relative path from this script to mPDF
 $nama_dokumen='Laporan Data Guru'; //Beri nama file PDF hasil.
define('_MPDF_PATH','../../mpdf60/');
include(_MPDF_PATH . "mpdf.php");
$mpdf=new mPDF('utf-8', 'A4-L'); // Create new mPDF Document

//Beginning Buffer to save PHP variables and HTML tags
ob_start();
?>
<!--sekarang Tinggal Codeing seperti biasanya. HTML, CSS, PHP tidak masalah.-->
<!--CONTOH Code START-->
<?php
 //KONEKSI
include "../../config/connect.php";
?>
<!-- <img src="../../images/kop_laporanLans.png"> -->
<h3 align="center">DATA SISWA</h3>
<br>
<table border="1" cellspacing="0" cellpadding="5">
<tr bgcolor="#64b5f6">
    <th width="60px">Kode Siswa</th>
    <th>NIS</th>
    <th>Nama Siswa</th>
    <th>Jenis Kelamin</th>
    <th>Agama</td>
    <th width="100px">Tempat Lahir</th>
    <th>Tanggal Lahir</th>
    <th>Alamat</th>
    <th>T.A</th>
    <th>Status</th>
</tr>
<?php
$no=1;
$sql=mysql_query("SELECT * From siswa ORDER BY kode_siswa");
while($data=mysql_fetch_array($sql)){
if($no%2==0){ $warna="#bdbdbd";} else { $warna="#FFFFFF";}
?>
<tr bgcolor="<?php echo $warna; ?>">
<td><?php echo $data['kode_siswa']; ?></td>
<td align="center"><?php echo $data['nis']; ?></td>
<td align="center"><?php echo $data['nama_siswa']; ?></td>
<td align="center"><?php echo $data['jenis_kelamin']; ?></td>
<td align="center"><?php echo $data['agama']; ?></td>
<td align="center"><?php echo $data['tmp_lahir']; ?></td>
<td align="center"><?php echo $data['tgl_lahir']; ?></td>
<td align="center"><?php echo $data['alamat']; ?></td>
<td align="center"><?php echo $data['tahun_angkatan']; ?></td>
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
