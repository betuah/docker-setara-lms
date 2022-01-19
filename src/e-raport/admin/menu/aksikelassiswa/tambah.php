<div class="row">
            <div class="col s12 m12 l12">
              <div class="card-panel">
                <h4 class="header2">Form Data Kelas Siswa</h4>
                <div class="row">
                  <?php
                  $query = "SELECT max(kode_datakelas) as idMaks FROM datakelas";
                  $hasil = mysql_query($query);
                  $data  = mysql_fetch_array($hasil);
                  $kode_datakelas = $data['idMaks'];

                  //mengatur 6 karakter sebagai jumalh karakter yang tetap
                  //mengatur 3 karakter untuk jumlah karakter yang berubah-ubah
                  $noUrut = (int) substr($kode_datakelas, 3, 3);
                  $noUrut++;

                  //menjadikan 201353 sebagai 6 karakter yang tetap
                  $char = "DK";
                  //%03s untuk mengatur 3 karakter di belakang 201353
                  $IDbaru = $char . sprintf("%03s", $noUrut);
                  ?>
                  <form id="formtambah" action="aksi.php?menu=kelassiswa&aksi=tambah" method="post" onSubmit="return validasi_tambahkelassiswa(this);" enctype="multipart/form-data" class="col s12" >
                    <div class="row">
                      <div class="input-field col s12">
                        <input id="kode_datakelas" type="text" name="kode_datakelas" value="<?php echo $IDbaru; ?>" readonly>
                        <label for="kode_datakelas">Kode Data Kelas</label>
                      </div>
                    </div>
                   
                    <div class="row">
                      <div class="input-field col s12 m6 l6">
                        <select name="kode_kelas" id="kode_kelas" >
                          <option value="pilih" selected disabled>- Pilih Nama Kelas -</option>
                          <?php
                          $sql = mysql_query("SELECT * FROM kelas ORDER BY tahun_ajar, nama_kelas ASC");
                          if(mysql_num_rows($sql) != 0){
                                        while($data = mysql_fetch_array($sql)){
                                        echo '<option class='.$data['tahun_ajar'].' value='.$data['kode_kelas'].'-'.$data['tahun_ajar'].'>'.$data['nama_kelas'].' | '.$data['tahun_ajar'].'</option>'; }
                                        }
                          ?>
                        </select>
                        <label>Nama Kelas</label>
                      </div>  
                      <div class="input-field col s12 m6 l6">
                          <select name="jurusan" id="jurusan" >
                          <option value="pilih" selected disabled>- Pilih Jurusan -</option>
                           <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                           <option value="Multimedia">Multimedia</option>
                           <option value="Teknik Komputer & Jaringan">Teknik Komputer dan Jaringan</option>
                           <option value="Administrasi Perkantoran">Admistrasi Perkantoran</option>
                        </select>
                        <label>Jurusan</label>
                      </div>
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                         <input id="kode_siswa" type="text" name="kode_siswa" class="validate">
                          <label for="kode_siswa">Nama Siswa</label>
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
                        <a href="?menu=kelassiswa" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Kembali">
                          <i class="large mdi-content-undo"></i>
                        </a>
                        </div>