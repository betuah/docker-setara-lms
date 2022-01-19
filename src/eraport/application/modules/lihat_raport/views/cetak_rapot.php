<!DOCTYPE html>
<html>
<head>
	<title>Cetak Raport</title>
	<style type="text/css">
		body {font-family: arial; font-size: 11pt; width: 8.5in}
		.table {border-collapse: collapse; border: solid 1px #999; width:100%}
		.table tr td, .table tr th {border:  solid 1px #000; padding: 3px;}
		.table tr th {font-weight: bold; text-align: center}
		.rgt {text-align: right;}
		.ctr {text-align: center;}
		.tbl {font-weight: bold}

		table tr td {vertical-align: top}
		.font_kecil {font-size: 12px}
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
	<table>
		<thead><!-- biar bisa ganti lembar otomatis --></thead>

		<tbody>
		<tr>
			<td colspan="6" style="text-align: center; font-weight: bold"><p><h3>HASIL PENCAPAIAN KOMPETENSI PESERTA DIDIK</h3></p></td>
		</tr>
		<tr>
			<td width="20%">Nama Satuan</td><td width="1%">:</td><td width="39%" class="tbl"><?php echo $this->config->item('nama_sekolah'); ?></td>
			<td width="20%">Tingkatan/Setara Kelas</td><td width="1%">:</td><td width="19%" class="tbl"><?php echo strtoupper($wali_kelas['nama_kelas']); ?></td>
		</tr>
		<tr>
			<td>Alamat</td><td>:</td><td class="tbl"><?php echo $this->config->item('alamat_sekolah'); ?></td>
			<td>Paket Kompetensi</td><td>:</td><td class="tbl"><?php echo $semester; ?></td>
		</tr>
		<tr>
			<td>Nama Warga Belajar</td><td>:</td><td class="tbl"><?php echo $det_siswa['nama']; ?></td>
			<td>Tahun Pelajaran</td><td>:</td><td class="tbl"><?php echo $ta; ?></td>
		</tr>
		<tr>
			<td>NIS / NISN</td><td>:</td><td class="tbl"><?php echo $det_siswa['nis']." / ".$det_siswa['nisn']; ?></td>
			<td colspan="3"></td>
		</tr>
		<tr><td colspan="6"><br><br></td></tr>
		<tr><td colspan="6"><b>A. Sikap</b></td></tr>
		<tr><td colspan="6">
			<table style="margin-left: 15px">
				<tr><td width="3%"><b>1.</b></td><td width="97%"><b>Sikap Spiritual</b></td></tr>
				<tr><td></td><td style="border: solid 1px #000; padding: 10px">Deskripsi : <?php echo $nilai_sikap_spiritual; ?></td></tr>
				<tr><td width="3%"><b>2.</b></td><td width="97%"><b>Sikap Sosial</b></td></tr>
				<tr><td></td><td style="border: solid 1px #000; padding: 10px">Deskripsi : <?php echo $nilai_sikap_sosial; ?></td></tr>
			</table>
		</td></tr>
		<tr><td colspan="6"><br><b>B. Pengetahuan</b></td></tr>
		<tr><td colspan="6">
			<table class="table">
				<thead>
				<tr>
					<th colspan="2" rowspan="2" width="30%">Mata Pelajaran</th>
					<th rowspan="2">SKK</th>
					<th rowspan="2">KKM</th>
					<th colspan="3">Nilai</th>
					<th colspan="3">Predikat</th>
				</tr>
				<tr>
					<th>Modul 1</th>
					<th>Modul 2</th>
					<th>Modul 3</th>
					<th>Modul 1</th>
					<th>Modul 2</th>
					<th>Modul 3</th>
				</tr>

				</thead>
				<tbody>
				<?php echo $nilai_utama; ?>
				</tbody>
			</table>
		</td></tr>
		<tr><td colspan="6"><br><b>C. Keterampilan</b></td></tr>
		<tr><td colspan="6">
			<table class="table">
				<thead>
				<tr>
					<th colspan="2" rowspan="2" width="30%">Mata Pelajaran</th>
					<th rowspan="2">SKK</th>
					<th rowspan="2">KKM</th>
					<th colspan="3">Nilai</th>
					<th colspan="3">Predikat</th>
				</tr>
				<tr>
					<th>Modul 1</th>
					<th>Modul 2</th>
					<th>Modul 3</th>
					<th>Modul 1</th>
					<th>Modul 2</th>
					<th>Modul 3</th>
				</tr>

				</thead>
				<tbody>
				<?php echo $nilai_utama; ?>
				</tbody>
			</table>
		</td></tr>
		<tr><td colspan="6"><br><br><br><br><br><br><br><br><br><br><b>D. Ekstrakurikuler</b></td></tr>
		<tr><td colspan="6">
			<table class="table">
				<thead>
					<tr>
						<th width="5%">No</th>
						<th width="30%">Nama Kegiatan</th>
						<th width="10%">Nilai</th>
						<th width="55%">Keterangan</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (!empty($nilai_ekstra)) {
						$no = 1;
						foreach ($nilai_ekstra as $ne) {
							$desk = "";
							if ($ne['nilai'] == "A") {
								$desk = "Memuaskan, aktif mengikuti kegiatan ".$ne['nama']." mingguan";
							} else if ($ne['nilai'] == "B") {
								$desk = "Cukup Memuaskan, aktif mengikuti kegiatan ".$ne['nama']." mingguan";
							} else if ($ne['nilai'] == "C") {
								$desk = "Kurang Memuaskan, pasif mengikuti kegiatan ".$ne['nama']." mingguan";
							} else {
								$desk = "-";
							}
					?>
						<tr>
							<td class="ctr"><?php echo $no; ?></td>
							<td><?php echo $ne['nama']; ?></td>
							<td class="ctr"><?php echo $ne['nilai']; ?></td>
							<td><?php echo $desk; ?></td>
						</tr>
					<?php
							$no++;
						}
					} else {
						echo '<tr><td colspan="4">-</td></tr>';
					}
					?>
				</tbody>
			</table>
		</td></tr>
		<tr style="display: none;"><td colspan="6"><br><b>E. Ketidakhadiran</b></td></tr>
		<tr style="display: none;">
			<td colspan="6">
				<table width="100%">
					<tr>
						<td width="40%">
							<table class="table" width="100%">
								<tr><td width="60%">Sakit</td><td width="40%" class="ctr"><?php echo $nilai_absensi['s']; ?> &nbsp; hari</td></tr>
								<tr><td>Izin</td><td class="ctr"><?php echo $nilai_absensi['i']; ?> &nbsp; hari</td></tr>
								<tr><td>Tanpa Keterangan</td><td class="ctr"><?php echo $nilai_absensi['a']; ?> &nbsp; hari</td></tr>
							</table>
						</td>
						<td width="60%">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<?php
		if ($semester == 2) {
		?>
		<tr>
		    <td colspan="6">
		        <?php
		        $naik_kelas = $det_siswa['tingkat']+1;
		        $kelas_now = $det_siswa['tingkat'];

			    if ($kelas_now != 9) {
		        ?>

		        <div style="border: solid 1px; padding: 10px; margin-top: 40px">
            		<b>Keputusan : </b>
            	    <p>Berdasarkan pencapaian kompetensi pada paket kompetensi ganjil dan genap, peserta didik ditetapkan *) :<br>
            	    <div style="display: block">
                	    <div style="diplay: inline; float: left; width: 200px">naik ke kelas </div>
                	    <div style="diplay: inline; float: left; font-weight: bold"><?php echo $naik_kelas." (".terbilang($naik_kelas).")"; ?></div>
            	    </div><br>
            	    <div style="display: block">
                	    <div style="diplay: inline; float: left; width: 200px"><strike>tinggal di kelas</strike> </div>
                	    <div style="diplay: inline; float: left; font-weight: bold"><strike><?php echo $kelas_now." (".terbilang($kelas_now).")"; ?></strike></div>
                    </div>
                    <br><br>
            	    *) Coret yang tidak perlu
        	    </div>

        	    <?php } else { ?>
        	    <div style="border: solid 1px; padding: 10px; margin-top: 40px">
            		<b>Keputusan : </b>
            	    <p>Berdasarkan pencapaian kompetensi pada kelas 7, 8 dan 9, maka, peserta didik dinyatakan : *) :<br>
            	    <div style="display: block; font-weight: bold">
                	    LULUS / <strike>TIDAK LULUS</strike>
            	    </div><br><br>
            	    *) Coret yang tidak perlu
        	    </div>

        	    <?php } ?>
		    </td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="6">
				<br><br>
				<table width="100%">
					<tr>
						<td width="10%"></td>
						<td width="20%">
							Mengetahui : <br>
							Orang Tua/Wali, <br>
							<br><br><br><br>
							<u>..........................</u>
						</td>
						<td width="8%"></td>
						<td width="25%">
							<br>
							Penanggung Jawab Rombel <br>
							<br><br><br><br>
							<u><b><?php echo $wali_kelas['nama_wali']; ?></b></u><br>
							NIP. <?php echo $wali_kelas['nip_wali']; ?>
						</td>
						<td width="8%"></td>
						<td width="29%">

							Kepala <?php echo $this->config->item('nama_sekolah'); ?> <br>
							<br><br><br><br>
							<u><b><?php echo $det_raport['nama_kepsek']; ?></b></u><br>
							NIP. <?php echo $det_raport['nip_kepsek']; ?>
						</td>
					</tr>
				</table>

			</td>
		</tr>

		</tbody>
	</table>
</body>
</html>
