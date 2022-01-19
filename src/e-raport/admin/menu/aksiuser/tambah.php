<div class="row">
            <div class="col s12 m12 l12">
              <div class="card-panel">
                <h4 class="header2">Form Data User</h4>
                <div class="row">
                  <?php
                  $query = "SELECT max(kode_user) as idMaks FROM user";
                  $hasil = mysql_query($query);
                  $data  = mysql_fetch_array($hasil);
                  $nip = $data['idMaks'];

                  //mengatur 6 karakter sebagai jumalh karakter yang tetap
                  //mengatur 3 karakter untuk jumlah karakter yang berubah-ubah
                  $noUrut = (int) substr($nip, 2, 3);
                  $noUrut++;

                  //menjadikan 201353 sebagai 6 karakter yang tetap
                  $char = "U";
                  //%03s untuk mengatur 3 karakter di belakang 201353
                  $IDbaru = $char . sprintf("%03s", $noUrut);
                  ?>
                  <form action="aksi.php?menu=user&aksi=tambah" method="post" onSubmit="return validasi_tambahuser(this);" enctype="multipart/form-data" class="col s12" >
                  <div class="row">
                      <div class="input-field col s12">
                        <input id="kode_guru" type="text" name="kode_user" value="<?php echo $IDbaru; ?>" readonly>
                        <label for="kode_guru">Kode User</label>
                    </div>
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                          <input id="username" type="text" name="username" class="validate">
                          <label for="username">Username</label>
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
                          <input id="nama_user" type="text" name="nama_user" class="validate">
                          <label for="nama_user">Nama User</label>
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
                        <input id="telp" type="tel" name="telp" length="12" maxlength="12" class="validate" >
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
                        <a href="?menu=user" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Kembali">
                          <i class="large mdi-content-undo"></i>
                        </a>
                        </div>