<div class="row">
            <div class="col s12 m12 l12">
              <div class="card-panel">
                <h4 class="header2">Form Data Siswa</h4>
                <div class="row">
                  <?php
                 
                  $hasil=mysql_query("SELECT (select MAX(kode_siswa) from siswa) as idmaks, 
                           
                           (select MAX(nis) from siswa) as idmaks2 ");
                  $data  = mysql_fetch_array($hasil);
                  $kode_siswa = $data['idmaks'];
                  $nis = $data['idmaks2'];

                  //mengatur 6 karakter sebagai jumalh karakter yang tetap
                  //mengatur 3 karakter untuk jumlah karakter yang berubah-ubah
                  $noUrut = (int) substr($kode_siswa, 2, 4);
                  $noUrut2 = (int) substr($nis, 5, 3);
                  $noUrut++;
                  $noUrut2++;

                  //menjadikan 201353 sebagai 6 karakter yang tetap
                  $char = "S";
                  $char2 = "2015";
                  //%03s untuk mengatur 3 karakter di belakang 201353
                  $IDbaru = $char . sprintf("%04s", $noUrut);
                  $IDbaru2 = $char2 . sprintf("%03s", $noUrut);
                  ?>
                  <form id="formtambah" action="aksi.php?menu=siswa&aksi=tambah" method="post" onSubmit="return validasi_tambahsiswa(this);" enctype="multipart/form-data" class="col s12" >
                     <div class="row">
                      <div class="input-field col s12">
                        <input id="kode_siswa" type="text" name="kode_siswa" value="<?php echo $IDbaru; ?>" readonly>
                        <label for="nis">Kode Siswa</label>
                    </div>
                    </div>
                    <div class="row">
                      <div class="input-field col s12">
                        <input id="nis" type="text" name="nis" value="<?php echo $IDbaru2; ?>" readonly>
                        <label for="nis">Nomor Induk Siswa</label>
                    </div>
                    </div>
                    <div class="row">
                      <div class="input-field col s12 m6 l6">
                          <input id="password1" type="password" name="password1" class="validate" >
                          <label for="password1">Password</label>
                      </div>
                     <div class="input-field col s12 m6 l6">
                          <input id="password2" type="password" name="password2" class="validate" >
                          <label for="password2">Konfirmasi Password</label>
                      </div>
                        &nbsp;&nbsp; <input type="checkbox" id="lihat" onChange="getpass; lihatpassword(this.value)" >
                        <label for="lihat">Tampilkan password</label>
                    </div>
                    <div class="section"></div>
                    <div class="row">
                       <div class="input-field col s12">
                          <input id="nama_siswa" type="text" name="nama_siswa" class="validate">
                          <label for="nama_siswa">Nama Siswa</label>
                      </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                          <textarea id="alamat" class="materialize-textarea validate" name="alamat" length="120" maxlength="120"></textarea>
                          <label for="alamat">Alamat</label>
                        </div>
                      </div>
                      <div class="row">
                       <div class="input-field col s6">
                          <input id="tmp_lahir" type="text" name="tmp_lahir" class="validate" >
                          <label for="tmp_lahir">Tempat Lahir</label>
                      </div>
                      <div class="input-field col s6">
                        <input id="tgl_lahir" type="date" class="datepicker" placeholder="Tanggal Lahir" name="tgl_lahir">
                      </div>
                      </div>
                    <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <select name="agama" id="agama" >
                          <option value="pilih" selected disabled>- Pilih Agama -</option>
                          <option value="Islam">Islam</option>
                          <option value="Kristen">Kristen</option>
                          <option value="Hindu">Hindu</option>
                          <option value="Budha">Budha</option>
                        </select>
                        <label>Agama</label>
                      </div>  
                     <div class="input-field col s12 m4 l4">
                        <label for="jkel">Jenis Kelamin</label><br>
                  </div>
                          <div class="input-field col s12 m2 l2">
                        
                      <input name="jenis_kelamin" type="radio" value="Laki-laki" id="laki_laki" checked>
                      <label for="laki_laki">Laki-laki</label><br>
                    
                    
                      <input name="jenis_kelamin" type="radio" value="Perempuan" id="perempuan" >
                      <label for="perempuan">Perempuan</label>
                    
                          </div>
                  </div>
                  <div class="section"></div>
                  <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <select name="kolomstatus" id="status" >
                          <option value="pilih" selected disabled>- Pilih Status -</option>
                          <option value="Aktif">Aktif</option>
                          <option value="Tidak">Tidak</option>
                        </select>
                        <label>Status</label>
                      </div>  
                     
                  <div class="input-field col s12 m6 l6">
                        <select name="tahun_angkatan" id="status" >
                          <option value="pilih" selected disabled>- Pilih Tahun -</option>
                          <?php 
                            $tahun_sekarang=date("Y");
                            $tahun_awal=$tahun_sekarang-2;
                            $tahun_akhir=$tahun_sekarang+2;
                            for($tahun_awal; $tahun_awal<=$tahun_akhir; $tahun_awal++){
                              $tahun_tambah=$tahun_awal+1;
                              echo "<option value='$tahun_awal/$tahun_tambah'>$tahun_awal/$tahun_tambah</option>";
                            }
                          ?>

                        </select>
                        <label>Tahun Angkatan</label>
                      </div>
                  
                  
                 
                  </div>
                  <div class="row">
                  <div class="file-field input-field col s12 m6 l6">
                       <div class="btn waves-effect waves-light teal lighten-2">
                        <span>File</span>
                        <input type="file" name="foto" >
                      </div>
                      <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Maksimal file gambar 500kb" >
                      </div>
                  </div>


                  <div class="input-field col s12 m6 l6">
                        <input id="telp" type="tel" name="telp" length="12" maxlength="12" class="validate" autocomplete="off">
                        <label for="telp">Telepon</label>
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
                        <a href="?menu=siswa" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Kembali">
                          <i class="large mdi-content-undo"></i>
                        </a>
                        </div>


