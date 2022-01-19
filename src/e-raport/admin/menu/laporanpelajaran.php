 
   <div id="table-datatables">   
                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                            <p class="caption">Laporan Data Pelajaran</p>
                                 <table id="data-table-simple" class="responsive-table display " cellspacing="0">
                                     <thead>
                                     <tr>
                                           <th><center>No</center></th>
                                           <th><center>Kode Pelajaran</center></th>
                                           <th>Nama Pelajaran</th>
                                           <th>Keterangan</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th><center>No</center></th>
                                           <th><center>Kode Pelajaran</center></th>
                                           <th>Nama Pelajaran</th>
                                           <th>Keterangan</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    $no=1;
                                     $query=mysql_query("SELECT * FROM pelajaran");

                                     while($data=mysql_fetch_array($query)){
                                     ?>
                                        <tr>
                                           <td><center><?php echo $no++;?></center></td>
                                           <td><center><?php echo"$data[kode_pelajaran]";?></center></td>
                                           <td><?php echo"$data[nama_pelajaran]"; ?></td>
                                           <td><?php echo"$data[keterangan]";?></td>
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
                        <a href="laporan/pelajaran_pdf.php" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Cetak">
                          <i class="mdi-maps-local-print-shop"></i>
                        </a>
                        </div>

