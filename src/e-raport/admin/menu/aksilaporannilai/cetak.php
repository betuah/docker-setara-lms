 
   <div id="table-datatables">   
                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                            <p class="caption">Laporan Nilai Siswa</p>
                                 <table class="striped bordered" cellspacing="0" cellpadding="0">
                                    
                                    <?php
                                     $id=$_GET['id'];
                                     $query=mysql_query("SELECT nilai.*, pelajaran.nama_pelajaran, siswa.nama_siswa, siswa.nis, datakelas.jurusan FROM nilai, pelajaran, siswa, datakelas WHERE  nilai.kode_pelajaran=pelajaran.kode_pelajaran AND nilai.kode_siswa=siswa.kode_siswa AND nilai.kode_siswa=datakelas.kode_siswa AND nilai.kode_nilai='$id' ORDER BY semester, kode_pelajaran ASC");
                                     while($data=mysql_fetch_array($query)){
                                     ?>
                                        <tr>
                                           <td>Kode Siswa</td>
                                           <td>:</td>
                                           <td><?php echo $data['kode_siswa']; ?></td>
                                        </tr>
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
                                           <td>Semester</td>
                                           <td>:</td>
                                           <td><?php echo $data['semester']; ?></td>
                                        </tr>
                                        <tr>
                                           <td>Mata Pelajaran</td>
                                           <td>:</td>
                                           <td><?php echo $data['nama_pelajaran']; ?></td>
                                        </tr>
                                        <tr>
                                           <td>Nilai Tugas</td>
                                           <td>:</td>
                                           <td><?php echo $data['nilai_tugas']; ?></td>
                                        </tr>
                                        <tr>
                                           <td>Nilai Tugas 2</td>
                                           <td>:</td>
                                           <td><?php echo $data['nilai_tugas2']; ?></td>
                                        </tr>
                                        <tr>
                                           <td>Nilai Tugas 3</td>
                                           <td>:</td>
                                           <td><?php echo $data['nilai_tugas3']; ?></td>
                                        </tr>
                                        <tr>
                                           <td>Nilai UTS</td>
                                           <td>:</td>
                                           <td><?php echo $data['nilai_uts']; ?></td>
                                        </tr>
                                        <tr>
                                           <td>Nilai UAS</td>
                                           <td>:</td>
                                           <td><?php echo $data['nilai_uas']; ?></td>
                                        </tr>
                                        <tr>
                                           <td>Nilai Rata-rata</td>
                                           <td>:</td>
                                           <td>
                                           <?php 
                                            $total_tugas = round(((($data['nilai_tugas'] + $data['nilai_uts'] + $data['nilai_uas'])/3)*40)/100);
                                            $total_uts=($data['nilai_uts']*30)/100;
                                            $total_uas=($data['nilai_uas']*30)/100;
                                            $nilai_rata2=round($total_tugas + $total_uts + $total_uas);
                                              echo $nilai_rata2 ; 
                                           ?>
                                           </td>
                                        </tr>
                                        <tr>
                                           <td>Grade</td>
                                           <td>:</td>
                                           <td><?php 
                                              if($nilai_rata2 >=85 && $nilai_rata2<=100) { $grade="A"; }
                                               elseif($nilai_rata2 >=75 && $nilai_rata2<=84) { $grade="B"; }
                                               elseif($nilai_rata2 >=65 && $nilai_rata2<=74) { $grade="C"; }
                                               elseif($nilai_rata2 >=1 && $nilai_rata2<=64) { $grade="D"; }
                                               elseif($nilai_rata2==0) { $grade="E"; }
                                            echo $grade;
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                           <td>Keterangan</td>
                                           <td>:</td>
                                           <td><?php echo $data['keterangan']; ?></td>
                                        </tr>
                                        
                                       <?php
                                         }
                                       ?>
                                    
                                </table>
                                <p align="right"><a href="menu/aksilaporannilai/nilai_pdf.php?id=<?php echo $_GET['id']; ?>" class="waves-effect waves-light btn-flat deep-purple darken-2 white-text text-white btn"><i class="mdi-maps-local-print-shop"></i> &nbsp; Cetak</a></p>
                                
                            </div>
                            </div>
                        </div>
                        </div> 
                        <br>
                        <div class="fixed-action-btn">
                        <a href="?menu=laporannilai" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Kembali">
                          <i class="large mdi-content-undo"></i>
                        </a>
                        </div>
