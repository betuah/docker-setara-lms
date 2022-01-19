<div class="row">
            <div class="col s12 m12 l12">
              <div class="card-panel">
                 <h4 class="header">Data Pelajaran</h4>
                  <div class="divider"></div>
                  <div class="section"></div>
                <div class="row">
                  <?php
                  $query = "SELECT max(kode_nilai) as idMaks FROM nilai";
                  $hasil = mysql_query($query);
                  $data  = mysql_fetch_array($hasil);
                  $kode_nilai = $data['idMaks'];

                  //mengatur 6 karakter sebagai jumalh karakter yang tetap
                  //mengatur 3 karakter untuk jumlah karakter yang berubah-ubah
                  $noUrut = (int) substr($kode_nilai, 2, 3);
                  $noUrut++;

                  //menjadikan 201353 sebagai 6 karakter yang tetap
                  $char = "N";
                  //%03s untuk mengatur 3 karakter di belakang 201353
                  $IDbaru = $char . sprintf("%03s", $noUrut);
                  ?>
                  <form id="formtambah" action="aksi.php?menu=nilai&aksi=tambah" method="post" onSubmit="return validasi_tambahdatanilai(this);" enctype="multipart/form-data" class="col s12" >
                    <div class="row">
                      <div class="input-field col s12">
                        <input id="kode_nilai" type="text" name="kode_nilai" value="<?php echo $IDbaru; ?>" readonly>
                        <label for="kode_nilai">Kode Nilai</label>
                      </div>
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                        <select name="semester" id="semester" >
                          <option value="pilih" selected disabled>- Pilih Semester -</option>
                           <option value="1">1 - Ganjil</option>
                           <option value="2">2 - Genap</option>
                        </select>
                        <label>Semester</label>
                      </div> 
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                        <select name="kode_pelajaran" id="kode_pelajaran" >
                          <option value="pilih" selected disabled>- Pilih Pelajaran -</option>
                          <?php
                                    $sql = mysql_query("SELECT * FROM pelajaran ORDER BY kode_pelajaran ASC");
                                    
                                    while($data = mysql_fetch_array($sql)){
                                    echo '<option value='.$data['kode_pelajaran'].'>'.$data['nama_pelajaran'].'</option>'; }
                                    
                            ?>
                        </select>
                        <label>Pelajaran</label>
                      </div> 
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                         <input id="kode_guru" type="text" name="kode_guru" class="validate">
                          <label for="kode_guru">Nama Wali Kelas</label>
                      </div>
                    </div>
                    <div class="section"></div>
                    <h4 class="header">Data Siswa</h4>
                    <div class="divider"></div>
                     <div class="section"></div>
                    <div class="row">
                       <div class="input-field col s12">
                        <select name="kode_kelas" id="kode_kelas" >
                          <option value="pilih" selected disabled>- Pilih Kelas -</option>
                          <?php
                          $sql = mysql_query("SELECT * FROM datakelas, kelas WHERE datakelas.kode_kelas=kelas.kode_kelas ORDER BY tahun_ajar, nama_kelas ASC");
                          if(mysql_num_rows($sql) != 0){
                                        while($data = mysql_fetch_array($sql)){
                                        echo '<option value='.$data['kode_kelas'].'>'.$data['kelas'].' | '.$data['nama_kelas'].' | '.$data['tahun_ajar'].'</option>'; }
                                        }
                          ?>
                        </select>
                        <label>Kelas</label>
                      </div> 
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                         <input id="kode_siswa" type="text" name="kode_siswa" class="validate">
                          <label for="kode_siswa">Nama Siswa</label>
                      </div>
                    </div>
                    <div class="section"></div>
                    <h4 class="header">Data Nilai</h4>
                  <div class="divider"></div>
                  <div class="section"></div>
                    <div class="row">
                     <div class="input-field col s12 m2 l2 range-field">
                       <label>Nilai Tugas</label>
                      </div>
                      <div class="input-field col s12 m9 l9 range-field">
                       <input type="range" name="nilai_tugas" min="0" max="100" step="1" value="0" oninput="this.form.nilai_tugass.value=this.value" />
                      </div>
                      <div class="input-field col s12 m1 l1 range-field">
                      <input type="number" name="nilai_tugass" min="0" max="100" step="1" value="0" oninput="this.form.nilai_tugas.value=this.value" />
                      </div>
                    </div>
                    <div class="row">
                     <div class="input-field col s12 m2 l2 range-field">
                       <label>Nilai Tugas 2</label>
                      </div>
                      <div class="input-field col s12 m9 l9 range-field">
                       <input type="range" name="nilai_tugas2" min="0" max="100" step="1" value="0" oninput="this.form.nilai_tugass2.value=this.value" />
                      </div>
                      <div class="input-field col s12 m1 l1 range-field">
                      <input type="number" name="nilai_tugass2" min="0" max="100" step="1" value="0" oninput="this.form.nilai_tugas2.value=this.value" />
                      </div>
                    </div>
                    <div class="row">
                     <div class="input-field col s12 m2 l2 range-field">
                       <label>Nilai Tugas 3</label>
                      </div>
                      <div class="input-field col s12 m9 l9 range-field">
                       <input type="range" name="nilai_tugas3" min="0" max="100" step="1" value="0" oninput="this.form.nilai_tugass3.value=this.value" />
                      </div>
                      <div class="input-field col s12 m1 l1 range-field">
                      <input type="number" name="nilai_tugass3" min="0" max="100" step="1" value="0" oninput="this.form.nilai_tugas3.value=this.value" />
                      </div>
                    </div>
                    <div class="row">
                     <div class="input-field col s12 m2 l2 range-field">
                       <label>Nilai UTS</label>
                      </div>
                      <div class="input-field col s12 m9 l9 range-field">
                       <input type="range" name="nilai_uts" min="0" max="100" step="1" value="0" oninput="this.form.nilai_utss.value=this.value" />
                      </div>
                      <div class="input-field col s12 m1 l1 range-field">
                      <input type="number" name="nilai_utss" min="0" max="100" step="1" value="0" oninput="this.form.nilai_uts.value=this.value" />
                      </div>
                    </div>
                    <div class="row">
                     <div class="input-field col s12 m2 l2 range-field">
                       <label>Nilai UAS</label>
                      </div>
                      <div class="input-field col s12 m9 l9 range-field">
                       <input type="range" name="nilai_uas" min="0" max="100" step="1" value="0" oninput="this.form.nilai_uass.value=this.value" />
                      </div>
                      <div class="input-field col s12 m1 l1 range-field">
                      <input type="number" name="nilai_uass" min="0" max="100" step="1" value="0" oninput="this.form.nilai_uas.value=this.value" />
                      </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                          <textarea id="keterangan" class="materialize-textarea validate" name="keterangan" length="120" maxlength="120" placeholder="Optional"></textarea>
                          <label for="keterangan">Keterangan</label>
                        </div>
                      </div>
                    <div class="row">
                    <div class="input-field col s6 m8 l10">
                          <button class="btn red lighten-1 waves-effect waves-light right" type="reset">Reset
                            <i class="mdi-action-autorenew right"></i>
                          </button>
                          </div>
                         <div class="input-field col s6 m4 l2">
                          <button class="btn cyan waves-effect waves-light right" type="submit" name="tambah">Kirim
                            <i class="mdi-content-send right"></i>
                          </button>
                        </div>
                    </div>
                    
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="fixed-action-btn">
                        <a href="?menu=nilai" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Kembali">
                          <i class="large mdi-content-undo"></i>
                        </a>
                        </div>