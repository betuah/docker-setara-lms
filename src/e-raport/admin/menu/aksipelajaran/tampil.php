 
   <div id="table-datatables">   
                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                            <p class="caption">Data Mata Pelajaran</p>
                                 <table id="data-table-simple" class="responsive-table display" cellspacing="0">
                                     <thead>
                                     <tr>
                                           <th><center>No</center></th> 
                                           <th><center>Kode Mata Pelajaran</center></th>
                                           <th>Nama Mata Pelajaran</th>
                                           <th>Keterangan</th>
                                           <th><center>Aksi</center></th>
                                           
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th><center>No</center></th> 
                                           <th><center>Kode Mata Pelajaran</center></th>
                                           <th>Nama Mata Pelajaran</th>
                                           <th>Keterangan</th>
                                           <th><center>Aksi</center></th>
                                           
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    $no=1;
                                     $query=mysql_query("SELECT * FROM pelajaran");
                                     while($data=mysql_fetch_array($query)){
                                     ?>
                                        <tr>
                                           <td><center><?php echo $no++; ?></center></td>
                                           <td><center><?php echo"$data[kode_pelajaran]";?></center></td>
                                           <td><?php echo $data['nama_pelajaran']; ?></td>
                                           <td><?php echo $data['keterangan']; ?></td>
                                           <td><center><a href="?menu=pelajaran&aksi=editdatapelajaran&id=<?php echo $data['kode_pelajaran'];?>" class="btn-floating waves-effect waves-light btn-flat deep-purple btn tooltipped" data-position="left" data-delay="50" data-tooltip="Edit"><i class="mdi-content-create"></i></a> &nbsp;
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
                                              window.location.href='aksi.php?menu=pelajaran&aksi=hapus&id=<?php echo $data['kode_pelajaran'];?>'
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
                        <a href="?menu=pelajaran&aksi=tambahdatapelajaran" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Tambah">
                          <i class="large mdi-content-add"></i>
                        </a>
                        </div>

