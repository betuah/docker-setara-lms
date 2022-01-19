<div class="row">
            <div class="col s12 m12 l12">
              <div class="card-panel">
                <h4 class="header2">Form Data Kelas</h4>
                <div class="row">
                  <?php
                  $edit=mysql_query("select * from kelas where kode_kelas='$_GET[id]'")or die("gagal".mysql_error());
                  $cek=mysql_num_rows($edit);
                  $data=mysql_fetch_array($edit);
                   if($cek < 1){

                    echo "<script>swal({
                      title: 'Oops...', 
                      text: 'Data tidak ditemukan!', 
                      type: 'error'
                      }, 
                      function(){ 
                        window.location.href='?menu=kelas'; 
                      }); </script>";
                  } else { 

                  ?>
                  <form action="aksi.php?menu=kelas&aksi=edit" method="post" onSubmit="return validasi_tambahkelas(this);" enctype="multipart/form-data" class="col s12" >
                    <div class="row">
                      <div class="input-field col s12">
                        <input id="kode_kelas" type="text" name="kode_kelas" value="<?php echo $data['kode_kelas']; ?>" readonly>
                        <label for="kode_kelas">Kode Kelas</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="input-field col s12 m6 l6">
                        <select name="tahun_ajar" id="tahun_ajar" disabled>
                          <option value="pilih" selected disabled>- Pilih Tahun -</option>
                          <?php 
                            $tahun_sekarang=date("Y");
                            $tahun_awal=$tahun_sekarang-2;
                            $tahun_akhir=$tahun_sekarang+2;
                            for($tahun_awal; $tahun_awal<=$tahun_akhir; $tahun_awal++){
                              $tahun_tambah=$tahun_awal+1;
                            ?> 
                              <option value="<?php echo $tahun_awal.'/'.$tahun_tambah; ?>" <?php if($data['tahun_ajar']=="$tahun_awal/$tahun_tambah") { echo "selected"; } ?> ><?php echo "$tahun_awal/$tahun_tambah"; ?></option>
                            <?php
                            }
                          ?>

                        </select>
                        <label>Tahun Ajaran</label>
                      </div>
                      <div class="input-field col s12 m6 l6">
                        <select name="kelas" id="kelas" >
                          <option value="pilih" selected disabled>- Pilih Kelas -</option>
                           <option value="X" <?php if($data['kelas']=="X") { echo "selected"; } ?> >X</option>
                           <option value="XI" <?php if($data['kelas']=="XI") { echo "selected"; } ?> >XI</option>
                           <option value="XII" <?php if($data['kelas']=="XII") { echo "selected"; } ?> >XII</option>
                        </select>
                        <label>Kelas</label>
                      </div>  
                    </div>
                    <div class="row">
                      <div class="input-field col s12 m6 l6">
                        <select name="nama_kelas" id="nama_kelas" disabled>
                          <option value="pilih" selected disabled>- Pilih Nama Kelas -</option>
                           <option value="Kelas A" <?php if($data['nama_kelas']=="Kelas A") { echo "selected"; } ?> >Kelas A</option>
                           <option value="Kelas B" <?php if($data['nama_kelas']=="Kelas B") { echo "selected"; } ?> >Kelas B</option>
                           <option value="Kelas C" <?php if($data['nama_kelas']=="Kelas C") { echo "selected"; } ?> >Kelas C</option>
                        </select>
                        <label>Nama Kelas</label>
                      </div>  
                      <div class="input-field col s12 m6 l6">
                          <select name="kolomstatus" id="kolomstatus" >
                          <option value="pilih" selected disabled>- Pilih Status -</option>
                           <option value="Aktif" <?php if($data['status']=="Aktif") { echo "selected"; } ?> >Aktif</option>
                           <option value="Tidak Aktif" <?php if($data['status']=="Tidak Aktif") { echo "selected"; } ?> >Tidak Aktif</option>
                        </select>
                        <label>Status</label>
                      </div>
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                       <?php 
                      $editguru=mysql_query("select * from guru, kelas where kode_kelas='$_GET[id]' and kelas.kode_guru=guru.kode_guru")or die("gagal".mysql_error());
                      $dataguru=mysql_fetch_array($editguru);
                      $nama_wali=$dataguru['nama_guru'].' | '.$dataguru['kode_guru'].' | '.$dataguru['nip'] ;
                       ?>
                         <input id="kode_guru" type="text" name="kode_guru" class="validate" value="<?php echo $nama_wali; ?>">
                          <label for="kode_guru">Nama Wali Kelas</label>
                      </div>
                      <div class="input-field col s12">
                      <p>Keterangan : Jika ingin mengganti <b>Nama Wali Kelas</b>, hapus seluruh isian kolom <b>Nama Wali Kelas</b>, lalu isi dengan <b>Nama Wali Kelas</b> saja. </p>
                      </div>
                    </div>
                    <div class="row">
                         <div class="input-field col s12 m12 l12">
                          <button class="btn cyan waves-effect waves-light right" type="submit" name="edit">Kirim
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
          <?php } ?>