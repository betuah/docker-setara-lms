 
   <div id="table-datatables">   
                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                            <p class="caption">Laporan Data Siswa</p>
                                 <table id="data-table-simple" class="responsive-table display " cellspacing="0">
                                     <thead>
                                     <tr>
                                           <th><center>No</center></th>
                                           <th><center>Kode Siswa</center></th>
                                           <th><center>NIS</center></th>
                                           <th>Nama Siswa</th>
                                           <th>Kelamin</th>
                                           <th>Agama</th>
                                           <th>No Telepon</th>
                                           <th><center>Tahun Angkatan</center></th>
                                           <th><center>Status</center></th>
                                           <th>Foto</th>
                                           
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th><center>No</center></th>
                                           <th><center>Kode Siswa</center></th>
                                           <th><center>NIS</center></th>
                                           <th>Nama Siswa</th>
                                           <th>Kelamin</th>
                                           <th>Agama</th>
                                           <th>No Telepon</th>
                                           <th><center>Tahun Angkatan</center></th>
                                           <th><center>Status</center></th>
                                           <th>Foto</th>
                                          
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    $no=1;
                                     $query=mysql_query("SELECT * FROM siswa");

                                     while($data=mysql_fetch_array($query)){
                                     ?>
                                        <tr>
                                           <td><center><?php echo $no++;?></center></td>
                                           <td><center><?php echo"$data[kode_siswa]";?></center></td>
                                           <td><center><?php echo"$data[nis]"; ?></center></td>
                                           <td><?php echo"$data[nama_siswa]";?></td>
                                           <td><?php echo"$data[jenis_kelamin]";?></td>
                                           <td><?php echo"$data[agama]";?></td>
                                           <td><?php echo"$data[telp]";?></td>
                                           <td><center><?php echo"$data[tahun_angkatan]";?></center></td>
                                           <td><center><?php echo"$data[status]";?></center></td>
                                           <td> <img src="foto/<?php echo $data['foto'] ?>" width="40" height="40" class="circle valign profile-image materialboxed"></td>
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
                        <a href="laporan/siswa_pdf.php" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Cetak">
                          <i class="mdi-maps-local-print-shop"></i>
                        </a>
                        </div>

