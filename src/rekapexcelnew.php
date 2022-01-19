<?php
	if(!isset($_COOKIE['beeuser'])){
	header("Location: login.php");}
?>
<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */
include "../../config/server.php";
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once 'PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:N1');
$objPHPExcel->getActiveSheet()->getStyle("A1:N1")->getFont()->setSize(18);
   $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $objPHPExcel->getActiveSheet()->getStyle("A1:N1")->applyFromArray($style);


cellColor('A3:N3', 'e7e7e7');
//cellColor('A30:Z30', 'F28A8C');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
// Add some data
$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue('A1', 'HASIL UJIAN')
			->setCellValue('A3', 'No.')
			->setCellValue('B3', 'Nomor Ujian')
			->setCellValue('C3', 'Nama Peserta')
			->setCellValue('D3', 'Testing Center')
			->setCellValue('E3', 'Token')
			->setCellValue('F3', 'Jawaban Benar')
			->setCellValue('G3', 'Jawaban Salah')
			->setCellValue('H3', 'Tidak Menjawab')
			->setCellValue('I3', 'Nilai')
			->setCellValue('J3', 'Keterangan');

	if($_COOKIE['beekelas']=='ALL'){
		$sql = mysql_query("select *, s.XKodeKelas as kelas_siswa from cbt_siswa_ujian c left join cbt_siswa s on s.XNomerUjian = c.XNomerUjian
			left join cbt_ujian u on u.XTokenUjian = c.XTokenUjian  where c.XKodeSoal = '$_REQUEST[soal]'");
	}else{
		$sql = mysql_query("select *, s.XKodeKelas as kelas_siswa from cbt_siswa_ujian c left join cbt_siswa s on s.XNomerUjian = c.XNomerUjian
			left join cbt_ujian u on u.XTokenUjian = c.XTokenUjian  where c.XKodeSoal = '$_REQUEST[soal]' AND s.XKodeKelas='$_COOKIE[beekelas]'");
	}

$baris = 4;
$no = 1;
while($s = mysql_fetch_array($sql)){

//     $var_siswa = "$p[XNomerUjian]";
// 	$var_nama = "$p[XNamaSiswa]";
// 	$var_sesi = "$p[XSesi]";
//     $var_kelas = "$p[XKodeKelas]";
// 	$var_jurusan = "$p[XKodeJurusan]";
// 	$grup = "$p[XKodeKelas] - $p[XKodeJurusan]";
//
// 	$sqlpaket = mysql_query("select * from cbt_paketsoal p LEFT JOIN cbt_mapel m on m.XKodeMapel=p.XKodeMapel where p.XKodeSoal = '$var_soal'");
// 	$p1 = mysql_fetch_array($sqlpaket);
// 	$per_pil = $p1['XPersenPil'];
// 	$per_esai = $p1['XPersenEsai'];
// 	$var_pil = $p1['XPilGanda'];
// 	$var_esai = $p1['XEsai'];
// 	$namamapel = $p1['XNamaMapel'];
// 	$kodemapel = $p1['XKodeMapel'];
//
//
// $var_siswa = $p['XNomerUjian'];
// $var_sesi = $p['XSesi'];
//
// //ambil nilai esai masing2 siswa
// $sqljumlah = mysql_query("select sum(XNilaiEsai) as hasil from cbt_jawaban where XKodeSoal = '$var_soal' and XUserJawab = '$var_siswa' and XTokenUjian = '$var_token'");
// $o = mysql_fetch_array($sqljumlah);
// $nilai_esai = $o['hasil'];
//
//
// $sqldijawab = mysql_num_rows(mysql_query("SELECT * FROM `cbt_jawaban` WHERE XKodeSoal = '$var_soal' and XUserJawab = '$var_siswa' and XJawaban != ''"));
// 	$sqljawaban = mysql_query(" SELECT count( XNilai ) AS HasilUjian,XTokenUjian FROM `cbt_jawaban` WHERE XKodeSoal = '$var_soal' and XUserJawab = '$var_siswa' and XNilai =
// 	'1' ");
// 	$sqj = mysql_fetch_array($sqljawaban);
// 	$tokenujian = $sqj['XTokenUjian'];
// 	$jumbenar = $sqj['HasilUjian'];
// 	$jumsalah = $sqldijawab-$jumbenar;
// 	$tidakdijawab	= $var_pil-$sqldijawab;
// 	$nilai_pil = ($jumbenar/$var_pil)*100;
// 	$total_pil = ($kodemapel == 'TP' ? (($jumbenar*4)+($jumsalah*(-1))):(($jumbenar*1)+($jumsalah*0)));//$nilai_pil*($per_pil/100);
// 	$total_esai = $nilai_esai*($per_esai/100);
// 	$total_nilai = $total_pil+$total_esai;

	$sqlsoal = mysql_num_rows(mysql_query("select * from cbt_soal where XKodeSoal = '$s[XKodeSoal]'"));
	$sqlpakai = mysql_num_rows(mysql_query("select * from cbt_nilai where XKodeSoal = '$s[XKodeSoal]'"));

	$sqljawaban = mysql_query("SELECT count( XNilai ) AS HasilUjian FROM `cbt_jawaban` WHERE XKodeSoal = '$s[XKodeSoal]' and XUserJawab = '$s[XNomerUjian]' and XNilai = '1' and XTokenUjian = '$s[XTokenUjian]'");
		$sqj = mysql_fetch_array($sqljawaban);
	$sqljawaban2 = mysql_query("SELECT count( XNilaiJawab ) AS HasilUjian FROM `cbt_jawaban` WHERE XKodeSoal = '$s[XKodeSoal]' and XUserJawab = '$s[XNomerUjian]' and XNilaiJawab = '' and XTokenUjian = '$s[XTokenUjian]' and XJenisSoal = '1'");
		$sqj2 = mysql_fetch_array($sqljawaban2);
		$jumbenar = $sqj['HasilUjian'];
		$jum_kosong	= $sqj2['HasilUjian'];
		$hasil_pil = $jumbenar;
		$jumsalah	= $sqlsoal-($jumbenar+$jum_kosong);
		$nilai_pil = ($s['XKodeMapel'] == 'TP' ? (($jumbenar*4)+($jumsalah*(-1))):(($jumbenar*1)+($jumsalah*0)));//round((($jumbenar/$var_pil)*$per_pil),2);


		$sqljumlahx = mysql_query("select sum(XNilai) as hasil_pg, sum(XNilaiEsai) as hasil from cbt_jawaban where XKodeSoal = '$s[XKodeSoal]' and XUserJawab =
		'$s[XNomerUjian]' and XTokenUjian = '$s[XTokenUjian]'");
		$o = mysql_fetch_array($sqljumlahx);
		$cekjum = mysql_num_rows($sqljumlahx);
		$nilai_pg = round($nilai_pil,2);
		$nilai_essay = round($o['hasil'],2);
		$total_nilai = round($nilai_pg+$nilai_essay,2);
		$sqljur = mysql_query("SELECT * FROM `cbt_siswa` WHERE XNamaSiswa= '$s[XNamaSiswa]' ");
		$sjur = mysql_fetch_array($sqljur);
		$kojur = $sjur['XKodeJurusan'];

		if($total_nilai <= 21){
			$sta	= "Penempatan Kelas X";
		}elseif($total_nilai >= 22 AND $total_nilai <= 42){
			$sta	= "Penempatan Kelas XI";
		}else{
			$sta	= "Penempatan Kelas XII";
		}


// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0)
            //->setCellValue('A4', 'Miscellaneous glyphs')
            //->setCellValue('A5', 'sfdsdf');

			->setCellValue("A$baris", $no)
			->setCellValue("B$baris", $s['XNomerUjian'])
			->setCellValue("C$baris", $s['XNamaSiswa'])
			->setCellValue("D$baris", $s['kelas_siswa'])
			->setCellValue("E$baris", $s['XTokenUjian'])
			->setCellValue("F$baris", $jumbenar)
			->setCellValue("G$baris", $jumsalah)
			->setCellValue("H$baris", $jum_kosong)
			->setCellValue("I$baris", "$nilai_pg")
			->setCellValue("J$baris", "$sta");

			$no = $no +1;

	$baris = $baris + 1;
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle("Hasil Ujian ".$_REQUEST['soal']."-".$_REQUEST['jurusan']);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client�s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Hasil_UJIAN_'.$_REQUEST['soal'].'_'.$_REQUEST['jurusan'].'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
