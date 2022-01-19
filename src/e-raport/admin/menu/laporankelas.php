 
   <div id="table-datatables">   
                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                            <p class="caption">Laporan Data Kelas</p>
                                 <table id="data-table-simple" class="responsive-table display " cellspacing="0">
                                     <thead>
                                     <tr>
                                           <th><center>No</center></th>
                                           <th><center>Kode Kelas</center></th>
                                           <th><center>Tahun Ajaran</center></th>
                                           <th><center>Kelas</center></th>
                                           <th><center>Nama Kelas</center></th>
                                           <th>Wali Kelas</th>
                                           <th><center>Status</center></th>
                                           
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th><center>No</center></th>
                                           <th><center>Kode Kelas</center></th>
                                           <th><center>Tahun Ajaran</center></th>
                                           <th><center>Kelas</center></th>
                                           <th><center>Nama Kelas</center></th>
                                           <th>Wali Kelas</th>
                                           <th><center>Status</center></th>
                                          
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    $no=1;
                                     $query=mysql_query("SELECT kelas.*, guru.nama_guru FROM kelas, guru WHERE kelas.kode_guru=guru.kode_guru");

                                     while($data=mysql_fetch_array($query)){
                                     ?>
                                        <tr>
                                           <td><center><?php echo $no++;?></center></td>
                                           <td><center><?php echo"$data[kode_kelas]";?></center></td>
                                           <td><center><?php echo"$data[tahun_ajar]"; ?></center></td>
                                           <td><center><?php echo"$data[kelas]";?></center></td>
                                           <td><center><?php echo"$data[nama_kelas]";?></center></td>
                                           <td><?php echo"$data[nama_guru]";?></center></td>
                                           <td><center><?php echo"$data[status]";?></center></td>
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
                        <a href="laporan/kelas_pdf.php" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Cetak">
                          <i class="mdi-maps-local-print-shop"></i>
                        </a>
                        </div>

