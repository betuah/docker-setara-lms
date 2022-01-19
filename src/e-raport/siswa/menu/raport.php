 
   <div id="table-datatables">   
                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                            <form action="" method="POST">
                            Pilih Data Kelas Untuk Melihat Nilai Raport
                            <div class="row">
                              <div class="input-field col s12 m3 l3">
                                <select name="kelas" id="kelas" >
                                  <option value="pilih" selected disabled>- Pilih Kelas -</option>
                                   <option value="X">  X</option>
                                   <option value="XI">  XI</option>
                                   <option value="XII"> XII</option>
                                </select>
                                <label>Tingkat/ Kelas</label>
                              </div> 
                              <div class="input-field col s12 m6 l6">
                                <button class="btn-flat btn-floating cyan waves-effect waves-light orange darken-2 btn tooltipped" data-position="top" data-delay="50" data-tooltip="Cari" type="submit" name="cariguru"><i class="mdi-action-search"></i>
                                </button>
                                 <a href="index.php?menu=raport" class="btn-flat btn-floating cyan waves-effect waves-light  btn tooltipped" data-position="top" data-delay="50" data-tooltip="Refresh"><i class="mdi-navigation-refresh"></i></a>
                              </div>

                            </div>
                            </form>
                            <table>
                            
                                    <?php
                                    $siswa=$_SESSION['kode'];
                                    if(isset($_POST['kelas'])){
                                      $kelas=$_POST['kelas'];
                                      $no=1;
                                      $lihat=mysql_query("SELECT siswa.kode_siswa, siswa.nis, siswa.nama_siswa, nilai.semester, pelajaran.nama_pelajaran, nilai.nilai_tugas, nilai.nilai_tugas2, nilai.nilai_tugas3, nilai.nilai_uts, nilai.nilai_uas, nilai.keterangan, datakelas.jurusan, guru.nama_guru FROM siswa, nilai, pelajaran, datakelas, kelas, guru WHERE siswa.kode_siswa=nilai.kode_siswa AND nilai.kode_pelajaran=pelajaran.kode_pelajaran AND datakelas.kode_siswa=siswa.kode_siswa AND datakelas.kode_kelas=kelas.kode_kelas AND kelas.kode_kelas=nilai.kode_kelas AND kelas.kode_guru=guru.kode_guru AND kelas.kelas='$kelas' AND siswa.kode_siswa='$siswa'") or die(mysql_error());
                                      $ambilguru=mysql_fetch_array($lihat);
                                      if(mysql_num_rows($lihat)>0){
                                    $qry=mysql_query("SELECT tahun_ajar, kelas, nama_kelas,  semester FROM nilai, kelas, siswa WHERE nilai.kode_siswa=siswa.kode_siswa AND kelas.kode_kelas=nilai.kode_kelas AND siswa.kode_siswa='$_SESSION[kode]'");
                                    $ambildata=mysql_fetch_array($qry);
                                    ?>
                                    <tr>
                                        <td width="150px">Nama Siswa</td>
                                        <td>: <?php echo $_SESSION['nama']; ?></td>
                                        <td width="150px">Tingkat/ Tahun Ke</td>
                                        <td>: <?php echo $ambildata['kelas']; ?></td>
                                    </tr> 
                                    <tr>
                                        <td>Nomor Induk Siswa</td>
                                        <td>: <?php echo $_SESSION['user']; ?></td>
                                        <td>Kelas</td>
                                        <td>: <?php echo $ambildata['nama_kelas']; ?></td>
                                    </tr> 
                                    <tr>
                                        <td>Tahun Pelajaran</td>
                                        <td>: <?php echo $ambildata['tahun_ajar']; ?></td>
                                        <td>Wali Kelas</td>
                                        <td>: <?php echo $ambilguru['nama_guru']; ?></td>
                                    </tr>
                                    </table>
                                         <table class="responsive-table striped" cellspacing="0">
                                             <thead>
                                             <tr>
                                                   <th rowspan="3"><center>No</center></th> 
                                                   <th rowspan="3">Mata Pelajaran</th> 
                                                   <th rowspan="3">Semester</th> 
                                                   <th colspan="7"><center>Kelulusan</center></th>
                                            </tr>
                                            <tr>
                                                   <th ><center>Angka</center></th> 
                                                   <th colspan="6"><center>Edikat</center></th> 
                                            </tr>
                                            <tr>
                                                   <th><center>&nbsp;</center></th> 
                                                   <th><center>A</center></th> 
                                                   <th><center>B</center></th>
                                                   <th><center>C</center></th>
                                                   <th><center>D</center></th>
                                                   <th><center>E</center></th>
                                            </tr>
                                            </thead>
                                            
                                            <tbody>
                                      <?php
                                      $cari=mysql_query("SELECT siswa.kode_siswa, siswa.nis, siswa.nama_siswa, nilai.semester, pelajaran.nama_pelajaran, nilai.nilai_tugas, nilai.nilai_tugas2, nilai.nilai_tugas3, nilai.nilai_uts, nilai.nilai_uas, nilai.keterangan, datakelas.jurusan, guru.nama_guru FROM siswa, nilai, pelajaran, datakelas, kelas, guru WHERE siswa.kode_siswa=nilai.kode_siswa AND nilai.kode_pelajaran=pelajaran.kode_pelajaran AND datakelas.kode_siswa=siswa.kode_siswa AND datakelas.kode_kelas=kelas.kode_kelas AND kelas.kode_kelas=nilai.kode_kelas AND kelas.kode_guru=guru.kode_guru AND kelas.kelas='$kelas' AND siswa.kode_siswa='$siswa'") or die(mysql_error());
                                      while($cari2=mysql_fetch_array($cari)){
                                              $total_tugas = round(((($cari2['nilai_tugas'] + $cari2['nilai_uts'] + $cari2['nilai_uas'])/3)*40)/100);
                                            $total_uts=($cari2['nilai_uts']*30)/100;
                                            $total_uas=($cari2['nilai_uas']*30)/100;
                                            $nilai_rata2=round($total_tugas + $total_uts + $total_uas);
                                             if($nilai_rata2 >=85 && $nilai_rata2<=100) { $grade="A"; }
                                                elseif($nilai_rata2 >=75 && $nilai_rata2<=84) { $grade="B"; }
                                                elseif($nilai_rata2 >=65 && $nilai_rata2<=74) { $grade="C"; }
                                                elseif($nilai_rata2 >=1 && $nilai_rata2<=64) { $grade="D"; }
                                                elseif($nilai_rata2==0) { $grade="E"; }
                                            ?>
                                             <tr>
                                                 <td><center><?php echo $no++;?></center></td>
                                                 <td><?php echo"$cari2[nama_pelajaran]";?></td>
                                                 <td><?php echo"$cari2[semester]";?></td>
                                                 <td><center><?php echo"$nilai_rata2";?></center></td>
                                                 <td><center><?php if($grade=="A"){echo $grade;} else { echo "-";} ?></center></td>
                                                 <td><center><?php if($grade=="B"){echo $grade;} else { echo "-";} ?></center></td>
                                                 <td><center><?php if($grade=="C"){echo $grade;} else { echo "-";} ?></center></td>
                                                 <td><center><?php if($grade=="D"){echo $grade;} else { echo "-";} ?></center></td>
                                                 <td><center><?php if($grade=="E"){echo $grade;} else { echo "-";} ?></center></td>
                                              </tr>
                                        <?php
                                      }
                                     
                                      } else {
                                      ?>
                                        <h5>Data Belum Ada!</h5>
                                      <?php
                                      }
                                      ?>
                                      
                                              </tbody>  
                                          </table>
                                      </div>
                                      </div>
                                  </div>
                                  </div> 
                                  <div class="fixed-action-btn">
                                  <a href="cetak_raport.php?id=<?php echo $kelas; ?>" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Cetak">
                                    <i class="mdi-maps-local-print-shop"></i>
                                  </a>
                                  </div>
                                    <?php
                                    } else {
                                     ?>
                                        <h5>Tidak ada data!</h5>
                                    </tbody>  
                                </table>
                            </div>
                            </div>
                        </div>
                        </div> 
                                    <?php
                                    }
                                    ?>
                                    
