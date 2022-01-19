<!DOCTYPE html>
<html>
<head>
	<title>Cetak Raport</title>
	<style type="text/css">
		body {font-family: arial; font-size: 12pt}
		.table {border-collapse: collapse; border: solid 1px #999; width:100%}
		.table tr td, .table tr th {border:  solid 1px #999; padding: 3px; font-size: 12px}
		.rgt {text-align: right;}
		.ctr {text-align: center;}
		table tr td {vertical-align: top}
	</style>
	<script type="text/javascript">
		function PrintWindow() {
		   window.print();
		   CheckWindowState();
		}

		function CheckWindowState()    {
			if(document.readyState=="complete") {
				window.close();
			} else {
				setTimeout("CheckWindowState()", 1000)
			}
		}
		PrintWindow();
	</script>
</head>
<body>
	<center>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<b>LAPORAN</b><br><br>
		HASIL PENCAPAIAN KOMPETENSI PESERTA DIDIK<br>
		<?php echo strtoupper($this->config->item('nama_sekolah')); ?>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<table style="margin-left:10%; width: 70%">
			<tr>
				<td width="20%">Nama Satuan Pendidikan</td>
				<td width="2%">:</td>
				<td width="50%"><?php echo strtoupper($this->config->item('nama_sekolah')); ?></td>
			</tr>
			<tr>
				<td>NPSN</td>
				<td>:</td>
				<td>20411926</td>
			</tr>
			<tr>
				<td>Alamat</td>
				<td>:</td>
				<td>Jakarta, Kode Pos : 55673, <br>Telepon : 0811 267 5969</td>
			</tr>
			<tr>
				<td>Kelurahan</td>
				<td>:</td>
				<td>Jakarta</td>
			</tr>
			<tr>
				<td>Kecamatan</td>
				<td>:</td>
				<td>Jakarta</td>
			</tr>
			<tr>
				<td>Kabupaten/Kota</td>
				<td>:</td>
				<td>Jakarta Pusat</td>
			</tr>
			<tr>
				<td>Propinsi</td>
				<td>:</td>
				<td>DKI Jakarta</td>
			</tr>
			<tr>
				<td>Website</td>
				<td>:</td>
				<td></td>
			</tr>
			<tr>
				<td>Email</td>
				<td>:</td>
				<td></td>
			</tr>
		</table>



	</center>

</body>
</html>
