<div class="row">
            <div class="col s12 m12 l12">
              <div class="card-panel">
                <h4 class="header2">Form Data Kelas</h4>
                <div class="row">
                  <?php
                  $query = "SELECT max(kode_kelas) as idMaks FROM kelas";
                  $hasil = mysql_query($query);
                  $data  = mysql_fetch_array($hasil);
                  $kode_kelas = $data['idMaks'];

                  //mengatur 6 karakter sebagai jumalh karakter yang tetap
                  //mengatur 3 karakter untuk jumlah karakter yang berubah-ubah
                  $noUrut = (int) substr($kode_kelas, 2, 3);
                  $noUrut++;

                  //menjadikan 201353 sebagai 6 karakter yang tetap
                  $char = "K";
                  //%03s untuk mengatur 3 karakter di belakang 201353
                  $IDbaru = $char . sprintf("%03s", $noUrut);
                  ?>
                  <form id="formtambah" action="aksi.php?menu=kelas&aksi=tambah" method="post" onSubmit="return validasi_tambahkelas(this);" enctype="multipart/form-data" class="col s12" >
                    <div class="row">
                      <div class="input-field col s12">
                        <input id="kode_kelas" type="text" name="kode_kelas" value="<?php echo $IDbaru; ?>" readonly>
                        <label for="kode_kelas">Kode Kelas</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="input-field col s12 m6 l6">
                        <select name="tahun_ajar" id="tahun_ajar" >
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
                        <label>Tahun Ajaran</label>
                      </div>
                      <div class="input-field col s12 m6 l6">
                        <select name="kelas" id="kelas" >
                          <option value="pilih" selected disabled>- Pilih Kelas -</option>
                           <option value="X">X</option>
                           <option value="XI">XI</option>
                           <option value="XII">XII</option>
                        </select>
                        <label>Kelas</label>
                      </div>  
                    </div>
                    <div class="row">
                      <div class="input-field col s12 m6 l6">
                        <select name="nama_kelas" id="nama_kelas" >
                          <option value="pilih" selected disabled>- Pilih Nama Kelas -</option>
                           <option value="Kelas A">Kelas A</option>
                           <option value="Kelas B">Kelas B</option>
                           <option value="Kelas C">Kelas C</option>
                        </select>
                        <label>Nama Kelas</label>
                      </div>  
                      <div class="input-field col s12 m6 l6">
                          <select name="pilihstatus" id="pilihstatus" >
                          <option value="pilih" selected disabled>- Pilih Status -</option>
                           <option value="Aktif">Aktif</option>
                           <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                        <label>Status</label>
                      </div>
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                         <input id="kode_guru" type="text" name="kode_guru" class="validate">
                          <label for="kode_guru">Nama Wali Kelas</label>
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
                        <a href="?menu=kelas" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Kembali">
                          <i class="large mdi-content-undo"></i>
                        </a>
                        </div>