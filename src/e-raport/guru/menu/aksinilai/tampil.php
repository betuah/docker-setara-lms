  
   <div id="table-datatables">   
                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                            <p class="caption">Data Nilai</p>
                            <form action="" method="POST">
                            Pilih Data Kelas Untuk Melihat Data Nilai
                            <div class="row">
                              <div class="input-field col s12 m3 l3">
                                <select name="kelas" id="kelas" >
                                  <option value="pilih" selected disabled>- Pilih Kelas -</option>
                                   <option value="X">X</option>
                                   <option value="XI">XI</option>
                                   <option value="XII">XII</option>
                                </select>
                                <label>Kelas</label>
                              </div> 
                              <div class="input-field col s12 m9 l9">
                                <button class="btn-flat btn-floating cyan waves-effect waves-light orange darken-2 btn tooltipped" data-position="top" data-delay="50" data-tooltip="Cari" type="submit" name="cariguru"><i class="mdi-action-search"></i>
                                </button>
                                 <a href="index.php?menu=nilai" class="btn-flat btn-floating cyan waves-effect waves-light  btn tooltipped" data-position="top" data-delay="50" data-tooltip="Refresh"><i class="mdi-navigation-refresh"></i></a>
                              </div>

                            </div>
                            </form>
                                 
                                    <?php
                                    if(isset($_POST['kelas'])){
                                      $kelas=$_POST['kelas'];
                                      $no=1;
                                      $cari=mysql_query("SELECT nilai.*, pelajaran.nama_pelajaran, siswa.nama_siswa, siswa.nis FROM siswa, pelajaran, nilai, datakelas, kelas WHERE nilai.kode_siswa=siswa.kode_siswa AND nilai.kode_pelajaran=pelajaran.kode_pelajaran AND datakelas.kode_siswa=siswa.kode_siswa AND datakelas.kode_kelas=kelas.kode_kelas AND kelas.kode_kelas=nilai.kode_kelas AND kelas.kelas='$kelas' ORDER BY kode_nilai");
                                      if(mysql_num_rows($cari)>0){
                                      ?>
                                        <table id="data-table-simple" class="responsive-table display" cellspacing="0">
                                     <thead>
                                     <tr>
                                           <th><center>No</center></th>
                                           <th><center>NIS</center></th>
                                           <th>Nama Siswa</th>
                                           <th><center>Semester</center></th>
                                           <th>Mata Pelajaran</th>
                                           <th><center>Tugas</center></th>
                                           <th><center>Tugas 2</center></th>
                                           <th><center>Tugas 3</center></th>
                                           <th><center>UTS</center></th>
                                           <th><center>UAS</center></th>
                                           <th><center>Aksi</center></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th><center>No</center></th>
                                           <th><center>NIS</center></th>
                                           <th>Nama Siswa</th>
                                           <th><center>Semester</center></th>
                                           <th>Mata Pelajaran</th>
                                           <th><center>Tugas</center></th>
                                           <th><center>Tugas 2</center></th>
                                           <th><center>Tugas 3</center></th>
                                           <th><center>UTS</center></th>
                                           <th><center>UAS</center></th>
                                           <th><center>Aksi</center></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                      <?php
                                    while($cari2=mysql_fetch_array($cari)){
                                      ?>
                                        <tr>
                                           <td><center><?php echo $no++; ?></center></td>
                                           <td><center><?php echo"$cari2[nis]";?></center></td>
                                           <td><?php echo $cari2['nama_siswa']; ?></td>
                                           <td><center><?php echo"$cari2[semester]";?></center></td>
                                           <td><?php echo"$cari2[nama_pelajaran]";?></td>
                                           <td><center><?php echo"$cari2[nilai_tugas]";?></center></td>
                                           <td><center><?php echo"$cari2[nilai_tugas2]";?></center></td>
                                           <td><center><?php echo"$cari2[nilai_tugas3]";?></center></td>
                                           <td><center><?php echo"$cari2[nilai_uts]";?></center></td>
                                           <td><center><?php echo"$cari2[nilai_uas]";?></center></td>
                                           <td><center><a href="?menu=nilai&aksi=editdatanilai&id=<?php echo $cari2['kode_nilai'];?>" class="btn-floating waves-effect waves-light btn-flat deep-purple btn tooltipped" data-position="left" data-delay="50" data-tooltip="Edit"><i class="mdi-content-create"></i></a> &nbsp;
                                           <a onClick="swal({
                                            title: 'Anda yakin?',
                                            text: 'Data akan dihapus secara permanen!',
                                            type: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#DD6B55',
                                            confirmButtonText: 'Ya, hapus!',
                                            cancelButtonText: 'Tidak, Batalkan!',
                                            closeOnConfirm: false,
                                            closeOnCancel: false
                                          },
                                          function(isConfirm){
                                            if (isConfirm) {
                                              window.location.href='aksi.php?menu=nilai&aksi=hapus&id=<?php echo $cari2['kode_nilai'];?>'
                                            } else {
                                                swal('Dibatalkan', 'Data batal dihapus :)', 'error');
                                            }
                                          });" href="#" class="btn-floating waves-effect waves-light btn-flat deep-orange btn tooltipped" data-position="right" data-delay="50" data-tooltip="Hapus"><i class="mdi-content-clear"></i></a></center></td>
                                        </tr>
                                      <?php
                                      }
                                      } else {
                                      ?>
                                        <h5>Data Belum Ada!</h5>
                                      <?php
                                      }
                                    ?>
                                     <div class="fixed-action-btn">
                                      <a href="?menu=nilai&aksi=tambahdatanilai" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Tambah">
                                        <i class="large mdi-content-add"></i>
                                      </a>
                                      </div>

                                    <?php
                                    } else {
                                    ?>
                                      <h5>Tidak Ada Data!</h5>
                                    <?php
                                    }
                                    ?>
                                    </tbody>  
                                </table>
                            </div>
                            </div>
                        </div>
                        </div> 
                        <br>
                       