 
   <div id="table-datatables">   
                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                            <p class="caption">Laporan Data Guru</p>
                                 <table id="data-table-simple" class="responsive-table display " cellspacing="0">
                                     <thead>
                                     <tr>
                                           <th><center>No</center></th>
                                           <th><center>Kode Guru</center></th>
                                           <th>NIP</th>
                                           <th>Nama Guru</th>
                                           <th>Alamat</th>
                                           <th>Kelamin</th>
                                           <th>No Telepon</th>
                                           <th><center>Status</center></th>
                                           <th>Foto</th>
                                           
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th><center>No</center></th>
                                           <th><center>Kode Guru</center></th>
                                           <th>NIP</th>
                                           <th>Nama Guru</th>
                                           <th>Alamat</th>
                                           <th>Kelamin</th>
                                           <th>No Telepon</th>
                                           <th><center>Status</center></th>
                                           <th>Foto</th>
                                          
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    $no=1;
                                     $query=mysql_query("SELECT * FROM guru");

                                     while($data=mysql_fetch_array($query)){
                                     ?>
                                        <tr>
                                           <td><center><?php echo $no++;?></center></td>
                                           <td><center><?php echo"$data[kode_guru]";?></center></td>
                                           <td><?php echo"$data[nip]"; ?></td>
                                           <td><?php echo"$data[nama_guru]";?></td>
                                           <td><?php echo"$data[alamat]";?></td>
                                           <td><?php echo"$data[jenis_kelamin]";?></td>
                                           <td><?php echo"$data[telp]";?></td>
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
                        <a href="laporan/guru_pdf.php" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Cetak">
                          <i class="mdi-maps-local-print-shop"></i>
                        </a>
                        </div>

