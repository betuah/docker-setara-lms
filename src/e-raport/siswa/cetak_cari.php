<?php
session_start();
 // Define relative path from this script to mPDF
 $nama_dokumen='Laporan Nilai Siswa'; //Beri nama file PDF hasil.
define('_MPDF_PATH','../mpdf60/');
include(_MPDF_PATH . "mpdf.php");
$mpdf=new mPDF('utf-8', 'A4'); // Create new mPDF Document
 
//Beginning Buffer to save PHP variables and HTML tags
ob_start(); 
?>
<!--sekarang Tinggal Codeing seperti biasanya. HTML, CSS, PHP tidak masalah.-->
<!--CONTOH Code START-->
<?php
 //KONEKSI
include "../config/connect.php";
$siswa = $_SESSION['kode'];
$kelas=$_GET['id'];
$sql=mysql_query("SELECT siswa.kode_siswa, siswa.nis, siswa.nama_siswa,
                                        nilai.semester, pelajaran.nama_pelajaran,
                                        nilai.nilai_tugas, nilai.nilai_tugas2, nilai.nilai_tugas3, 
                                        nilai.nilai_uts, nilai.nilai_uas,
                                        datakelas.jurusan, guru.nama_guru, kelas.kelas, kelas.tahun_ajar, kelas.nama_kelas
                                        FROM siswa, nilai, pelajaran, datakelas, guru, kelas
                                        WHERE siswa.kode_siswa=nilai.kode_siswa AND
                                        nilai.kode_pelajaran=pelajaran.kode_pelajaran AND 
                                        datakelas.kode_kelas=kelas.kode_kelas AND kelas.kode_guru=guru.kode_guru AND
                                        siswa.kode_siswa='$siswa' AND kelas.kode_kelas=nilai.kode_kelas AND kelas.kelas='$kelas'");
$data=mysql_fetch_array($sql);
$walikelas=$data['nama_guru'];
?>
<img src="../images/kop_laporan.png">
<h3 align="center">DATA HASIL BELAJAR SISWA<br> RAPORT SISWA</h3>
<br>
<table border="0">
<tr>
    <td>NIS</td>
    <td>:</td>
    <td><?php echo $data['nis']; ?></td>
</tr>
<tr>
    <td>Nama Siswa</td>
    <td>:</td>
    <td><?php echo $data['nama_siswa']; ?></td>
</tr>
<tr>
    <td>Jurusan</td>
    <td>:</td>
    <td><?php echo $data['jurusan']; ?></td>
</tr>
<tr>
    <td>Kelas</td>
    <td>:</td>
    <td><?php echo $data['kelas']; ?></td>
</tr>
<tr>
    <td>Tahun Pelajaran</td>
    <td>:</td>
    <td><?php echo $data['tahun_ajar']; ?></td>
</tr>
</table>
<table border="1" cellspacing="0" cellpadding="5">
<tr bgcolor="#64b5f6">
    <th width="180px">Mata Pelajaran</th>
    <th>Semester</th>
    <th>Tugas</th>
    <th>Tugas 2</th>
    <th>Tugas 3</th>
    <th>UTS</th>
    <th>UAS</td>
    <th>Nilai Rata-rata</th>
    <th>Grade</th>
</tr>
<?php
$no=1;
$sql=mysql_query("SELECT siswa.kode_siswa, siswa.nis, siswa.nama_siswa,
                                        nilai.semester, pelajaran.nama_pelajaran,
                                        nilai.nilai_tugas, nilai.nilai_tugas2, nilai.nilai_tugas3, 
                                        nilai.nilai_uts, nilai.nilai_uas,
                                        datakelas.jurusan, guru.nama_guru, kelas.kelas, kelas.tahun_ajar, kelas.nama_kelas
                                        FROM siswa, nilai, pelajaran, datakelas, guru, kelas
                                        WHERE siswa.kode_siswa=nilai.kode_siswa AND
                                        nilai.kode_pelajaran=pelajaran.kode_pelajaran AND 
                                        datakelas.kode_kelas=kelas.kode_kelas AND kelas.kode_guru=guru.kode_guru AND
                                        siswa.kode_siswa='$siswa' AND kelas.kode_kelas=nilai.kode_kelas AND kelas.kelas='$kelas'");
while($data=mysql_fetch_array($sql)){
if($no%2==0){ $warna="#bdbdbd";} else { $warna="#FFFFFF";}
$total_tugas = round(((($data['nilai_tugas'] + $data['nilai_uts'] + $data['nilai_uas'])/3)*40)/100);
$total_uts=($data['nilai_uts']*30)/100;
$total_uas=($data['nilai_uas']*30)/100;
$nilai_rata2=round($total_tugas + $total_uts + $total_uas);
 if($nilai_rata2 >=85 && $nilai_rata2<=100) { $grade="A"; }
    elseif($nilai_rata2 >=75 && $nilai_rata2<=84) { $grade="B"; }
    elseif($nilai_rata2 >=65 && $nilai_rata2<=74) { $grade="C"; }
    elseif($nilai_rata2 >=1 && $nilai_rata2<=64) { $grade="D"; }
    elseif($nilai_rata2==0) { $grade="E"; }

?>
<tr bgcolor="<?php echo $warna; ?>">
<td><?php echo $data['nama_pelajaran']; ?></td>
<td align="center"><?php echo $data['semester']; ?></td>
<td align="center"><?php echo $data['nilai_tugas']; ?></td>
<td align="center"><?php echo $data['nilai_tugas2']; ?></td>
<td align="center"><?php echo $data['nilai_tugas3']; ?></td>
<td align="center"><?php echo $data['nilai_uts']; ?></td>
<td align="center"><?php echo $data['nilai_uas']; ?></td>
<td align="center"><?php echo $nilai_rata2; ?></td>
<td align="center"><?php echo $grade; ?></td>
</tr>
<?php
$no++;
}
?>
</table>
<br><br>
<!--CONTOH Code END-->
<?php
    $r_bulan=array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
    $tgl_real=date("Y-m-d");
    list($thn, $bln, $tgl)=explode("-", $tgl_real);
?>
<p align="right">Makassar, <?php echo $tgl." ".$r_bulan[(int)$bln-1]." ".$thn; ?></p>
<table border="0">
<tr>
    <td width="330px" align="center">Kepala Sekolah SMK IK Mahardika</td>
    <td width="70px">&nbsp;</td>
    <td width="270px" align="center">Wali Kelas</td>
</tr>
<tr>
    <td width="330px">&nbsp;</td>
    <td width="70px">&nbsp;</td>
    <td width="270px" align="center">&nbsp;</td>
</tr>
<tr>
    <td width="330px">&nbsp;</td>
    <td width="70px">&nbsp;</td>
    <td width="270px" align="center">&nbsp;</td>
</tr>
<tr>
    <td width="330px">&nbsp;</td>
    <td width="70px">&nbsp;</td>
    <td width="270px" align="center">&nbsp;</td>
</tr>
<tr>
    <td width="330px" align="center"><b><u>Marselinus Mingge, Bc. HK, S.Sos, M.Si.</u></b></td>
    <td width="70px">&nbsp;</td>
    <td width="270px" align="center"><b><u><?php echo $walikelas; ?></u></b></td>
</tr>
</table>
<?php
$html = ob_get_contents(); //Proses untuk mengambil hasil dari OB..
ob_end_clean();
//Here convert the encode for UTF-8, if you prefer the ISO-8859-1 just change for $mpdf->WriteHTML($html);
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output($nama_dokumen.".pdf" ,'I');
exit;
?>