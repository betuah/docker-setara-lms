
   <div id="table-datatables">
                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                            <p class="caption">Laporan Data Nilai</p>
                            <form action="" method="POST">
                            Pilih Data Kelas Untuk Melihat Data Nilai
                            <div class="row">
                              <div class="input-field col s12 m3 l3">
                                <select name="kelas" id="kelas" >
                                  <option value="pilih" selected disabled>- Semua Kelas -</option>
                                   <option value="X">X</option>
                                   <option value="XI">XI</option>
                                   <option value="XII">XII</option>
                                </select>
                                <label>Kelas</label>
                              </div>
                              <div class="input-field col s12 m9 l9">
                                <button class="btn-flat btn-floating cyan waves-effect waves-light orange darken-2 btn tooltipped" data-position="top" data-delay="50" data-tooltip="Cari" type="submit" name="cariguru"><i class="mdi-action-search"></i>
                                </button>
                                 <a href="index.php?menu=laporannilai" class="btn-flat btn-floating cyan waves-effect waves-light  btn tooltipped" data-position="top" data-delay="50" data-tooltip="Refresh"><i class="mdi-navigation-refresh"></i></a>
                              </div>
                            </div>
                            </form>
                                 <table id="data-table-simple" class="responsive-table display " cellspacing="0">
                                     <thead>
                                     <tr>
                                           <th><center>No</center></th>
                                           <th><center>NIS</center></th>
                                           <th>Nama Siswa</th>
                                           <th>Mata Pelajaran</th>
                                           <th><center>Semester</center></th>
                                           <th><center>Tugas</center></th>
                                           <th><center>Tugas 2</center></th>
                                           <th><center>Tugas 3</center></th>
                                           <th><center>UTS</center></th>
                                           <th><center>UAS</center></th>
                                           <th>Aksi</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th><center>No</center></th>
                                           <th><center>NIS</center></th>
                                           <th>Nama Siswa</th>
                                           <th>Mata Pelajaran</th>
                                           <th><center>Semester</center></th>
                                           <th><center>Tugas</center></th>
                                           <th><center>Tugas 2</center></th>
                                           <th><center>Tugas 3</center></th>
                                           <th><center>UTS</center></th>
                                           <th><center>UAS</center></th>
                                           <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    if(isset($_POST['kelas'])){
                                      $kelas=$_POST['kelas'];
                                      $no=1;
                                      $cari=mysql_query("SELECT nilai.*, pelajaran.nama_pelajaran, siswa.nama_siswa, siswa.nis, datakelas.jurusan FROM siswa, pelajaran, nilai, datakelas, kelas WHERE nilai.kode_siswa=siswa.kode_siswa AND nilai.kode_pelajaran=pelajaran.kode_pelajaran AND datakelas.kode_siswa=nilai.kode_siswa AND datakelas.kode_siswa=siswa.kode_siswa AND datakelas.kode_kelas=kelas.kode_kelas AND kelas.kode_kelas=nilai.kode_kelas AND kelas.kelas='$kelas' ORDER BY kode_nilai");
                                      if(mysql_num_rows($cari)>0){
                                      while($cari2=mysql_fetch_array($cari)){
                                      ?>
                                      <tr>
                                           <td><center><?php echo $no++;?></center></td>
                                           <td><center><?php echo"$cari2[nis]";?></center></td>
                                           <td><?php echo"$cari2[nama_siswa]"; ?></td>
                                           <td><?php echo"$cari2[nama_pelajaran]";?></td>
                                           <td><center><?php echo"$cari2[semester]";?></center></td>
                                           <td><center><?php echo"$cari2[nilai_tugas]";?></center></td>
                                           <td><center><?php echo"$cari2[nilai_tugas2]";?></center></td>
                                           <td><center><?php echo"$cari2[nilai_tugas3]";?></center></td>
                                           <td><center><?php echo"$cari2[nilai_uts]";?></center></td>
                                           <td><center><?php echo"$cari2[nilai_uas]";?></center></td>
                                           <td><a href="?menu=laporannilai&aksi=lihatdatanilai&id=<?php echo $cari2['kode_nilai'];?>" class="btn-floating waves-effect waves-light btn-flat deep-purple btn tooltipped" data-position="left" data-delay="50" data-tooltip="Detail"><i class="mdi-action-description"></i></a></td>
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
                        <a href="menu/aksilaporannilai/cetak_cari.php?id=<?php echo $kelas; ?>" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Cetak">
                          <i class="mdi-maps-local-print-shop"></i>
                        </a>
                        </div>
                                    <?php
                                    } else {
                                    $no=1;
                                     $query=mysql_query("SELECT nilai.*, pelajaran.nama_pelajaran, siswa.nama_siswa, siswa.nis, datakelas.jurusan FROM siswa, pelajaran, nilai, datakelas, kelas WHERE nilai.kode_siswa=siswa.kode_siswa AND nilai.kode_pelajaran=pelajaran.kode_pelajaran AND datakelas.kode_siswa=nilai.kode_siswa AND datakelas.kode_siswa=siswa.kode_siswa AND datakelas.kode_kelas=kelas.kode_kelas AND kelas.kode_kelas=nilai.kode_kelas ORDER BY kode_nilai");

                                     while($data=mysql_fetch_array($query)){
                                     ?>
                                        <tr>
                                           <td><center><?php echo $no++;?></center></td>
                                           <td><center><?php echo"$data[nis]";?></center></td>
                                           <td><?php echo"$data[nama_siswa]"; ?></td>
                                           <td><?php echo"$data[nama_pelajaran]";?></td>
                                           <td><center><?php echo"$data[semester]";?></center></td>
                                           <td><center><?php echo"$data[nilai_tugas]";?></center></td>
                                           <td><center><?php echo"$data[nilai_tugas2]";?></center></td>
                                           <td><center><?php echo"$data[nilai_tugas3]";?></center></td>
                                           <td><center><?php echo"$data[nilai_uts]";?></center></td>
                                           <td><center><?php echo"$data[nilai_uas]";?></center></td>
                                           <td><a href="?menu=laporannilai&aksi=lihatdatanilai&id=<?php echo $data['kode_nilai'];?>" class="btn-floating waves-effect waves-light btn-flat deep-purple btn tooltipped" data-position="left" data-delay="50" data-tooltip="Detail"><i class="mdi-action-description"></i></a></td>
                                        </tr>
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
                        <a href="menu/aksilaporannilai/cetak_full.php" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Cetak">
                          <i class="mdi-maps-local-print-shop"></i>
                        </a>
                        </div>
                                    <?php
                                    }
                                    ?>
