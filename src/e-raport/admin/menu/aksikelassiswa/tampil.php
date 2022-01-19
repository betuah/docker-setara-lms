 
   <div id="table-datatables">   
                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                            <p class="caption">Data Kelas Siswa</p>
                                 <table id="data-table-simple" class="responsive-table display " cellspacing="0">
                                     <thead>
                                     <tr>
                                           <th><center>No</center></th>
                                           <th><center>NIS</center></th>
                                           <th>Nama Siswa</th>
                                           <th>Jurusan</th>
                                           <th><center>Tahun Ajaran</center></th>
                                           <th><center>Kelas</center></th>
                                           <th><center>Nama Kelas</center></th>
                                           <th>Wali Kelas</th>
                                           <th><center>Status</center></th>
                                           <th><center>Aksi</center></th>
                                          
                                           
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th><center>No</center></th>
                                           <th><center>NIS</center></th>
                                           <th>Nama Siswa</th>
                                           <th>Jurusan</th>
                                           <th><center>Tahun Ajaran</center></th>
                                           <th><center>Kelas</center></th>
                                           <th><center>Nama Kelas</center></th>
                                           <th>Wali Kelas</th>
                                           <th><center>Status</center></th>
                                           <th><center>Aksi</center></th>
                                           
                                           
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    $no=1;
                                     $query=mysql_query("SELECT datakelas.kode_datakelas, datakelas.kode_kelas, datakelas.jurusan, kelas.tahun_ajar, kelas.kelas, kelas.nama_kelas, guru.nama_guru, siswa.nis, siswa.nama_siswa, siswa.tahun_angkatan, siswa.status FROM datakelas, kelas, guru, siswa WHERE datakelas.kode_kelas=kelas.kode_kelas AND kelas.kode_guru=guru.kode_guru AND siswa.kode_siswa=datakelas.kode_siswa");
                                     while($data=mysql_fetch_array($query)){
                                     ?>
                                        <tr>
                                           <td><center><?php echo $no++; ?></center></td>
                                           <td><center><?php echo"$data[nis]";?></center></td>
                                           <td><?php echo"$data[nama_siswa]";?></td>
                                           <td><?php echo"$data[jurusan]";?></td>
                                           <td><center><?php echo"$data[tahun_ajar]"; ?></center></td>
                                           <td><center><?php echo"$data[kelas]";?></center></td>
                                           <td><center><?php echo $data['nama_kelas']; ?></center></td>
                                           <td><?php echo"$data[nama_guru]";?></td>
                                           <td><center><?php echo"$data[status]"; ?></center></td>
                                           <td><center><a href="?menu=kelassiswa&aksi=editdatakelassiswa&id=<?php echo $data['kode_datakelas'];?>" class="btn-floating waves-effect waves-light btn-flat deep-purple btn tooltipped" data-position="left" data-delay="50" data-tooltip="Edit"><i class="mdi-content-create"></i></a> &nbsp;
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
                                              window.location.href='aksi.php?menu=kelassiswa&aksi=hapus&id=<?php echo $data['kode_datakelas'];?>'
                                            } else {
                                                swal('Dibatalkan', 'Data batal dihapus :)', 'error');
                                            }
                                          });" href="#" class="btn-floating waves-effect waves-light btn-flat deep-orange btn tooltipped" data-position="right" data-delay="50" data-tooltip="Hapus"><i class="mdi-content-clear"></i></a></center></td>
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
                        <br>
                        <div class="fixed-action-btn">
                        <a href="?menu=kelassiswa&aksi=tambahdatakelassiswa" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Tambah">
                          <i class="large mdi-content-add"></i>
                        </a>
                        </div>

