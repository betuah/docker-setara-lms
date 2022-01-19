<div class="row">
            <div class="col s12 m12 l12">
              <div class="card-panel">
                <h4 class="header2">Form Data Mata Pelajaran</h4>
                <div class="row">
                  <?php
                  $query = "SELECT max(kode_pelajaran) as idMaks FROM pelajaran";
                  $hasil = mysql_query($query);
                  $data  = mysql_fetch_array($hasil);
                  $kode_pelajaran = $data['idMaks'];

                  //mengatur 6 karakter sebagai jumalh karakter yang tetap
                  //mengatur 3 karakter untuk jumlah karakter yang berubah-ubah
                  $noUrut = (int) substr($kode_pelajaran, 1, 3);
                  $noUrut++;

                  //menjadikan 201353 sebagai 6 karakter yang tetap
                  $char = "P";
                  //%03s untuk mengatur 3 karakter di belakang 201353
                  $IDbaru = $char . sprintf("%03s", $noUrut);
                  ?>
                  <form id="formtambah" action="aksi.php?menu=pelajaran&aksi=tambah" method="post" onSubmit="return validasi_pelajaran(this);" enctype="multipart/form-data" class="col s12" >
                    <div class="row">
                      <div class="input-field col s12">
                        <input id="kode_pelajaran" type="text" name="kode_pelajaran" value="<?php echo $IDbaru; ?>" readonly>
                        <label for="kode_pelajaran">Kode pelajaran</label>
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                          <input id="nama_pelajaran" type="text" name="nama_pelajaran" class="validate">
                          <label for="nama_pelajaran">Nama Mata Pelajaran</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="input-field col s12">
                        <select name="keterangan" id="status" >
                          <option value="pilih" selected disabled>- Pilih Keterangan -</option>
                          <option value="Wajib">Wajib</option>
                          <option value="Tambahan">Tambahan</option>
                        </select>
                        <label>Keterangan</label>
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
                        <a href="?menu=pelajaran" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Kembali">
                          <i class="large mdi-content-undo"></i>
                        </a>
                        </div>