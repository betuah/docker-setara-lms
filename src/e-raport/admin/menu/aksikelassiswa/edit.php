<div class="row">
            <div class="col s12 m12 l12">
              <div class="card-panel">
                <h4 class="header2">Form Data Kelas Siswa</h4>
                <div class="row">
                  <?php
                  $edit=mysql_query("select * from siswa, datakelas where kode_datakelas='$_GET[id]' and datakelas.kode_siswa=siswa.kode_siswa")or die("gagal".mysql_error());
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
                  <form action="aksi.php?menu=kelassiswa&aksi=edit" method="post" onSubmit="return validasi_tambahkelassiswa(this);" enctype="multipart/form-data" class="col s12" >
                    <div class="row">
                      <div class="input-field col s12">
                        <input id="kode_datakelas" type="text" name="kode_datakelas" value="<?php echo $data['kode_datakelas']; ?>" readonly>
                        <label for="kode_datakelas">Kode Data Kelas</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="input-field col s12 m6 l6">
                        <select name="kode_kelas" id="kode_kelas" disabled>
                          <option value="pilih" selected disabled>- Pilih Nama Kelas -</option>
                          <?php
                          $sql = mysql_query("SELECT * FROM kelas ORDER BY tahun_ajar, nama_kelas ASC");
                         
                                        while($data2 = mysql_fetch_array($sql)){
                                        ?>
                                        <option value="<?php echo $data2['kode_kelas']; ?>" <?php if($data2['kode_kelas']==$data['kode_kelas']){ echo "selected"; } ?>><?php echo $data2['nama_kelas']; ?> | <?php echo $data2['tahun_ajar']; ?></option>'; 

                                        <?php
                                           }
                                        
                          ?>
                        </select>
                        <label>Nama Kelas</label>
                      </div>  
                      <div class="input-field col s12 m6 l6">
                          <select name="jurusan" id="jurusan" >
                          <option value="pilih" disabled>- Pilih Jurusan -</option>
                           <option value="Rekayasa Perangkat Lunak" <?php if($data['jurusan']=="Rekayasa Perangkat Lunak"){ echo "selected"; } ?>>Rekayasa Perangkat Lunak</option>
                           <option value="Multimedia" <?php if($data['jurusan']=="Multimedia"){ echo "selected"; } ?>>Multimedia</option>
                           <option value="Teknik Komputer & Jaringan" <?php if($data['jurusan']=="Teknik Komputer & Jaringan"){ echo "selected"; } ?>>Teknik Komputer dan Jaringan</option>
                           <option value="Administrasi Perkantoran" <?php if($data['jurusan']=="Administrasi Perkantoran"){ echo "selected"; } ?>>Admistrasi Perkantoran</option>
                        </select>
                        <label>Jurusan</label>
                      </div>
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                       <?php 
                      
                      $nama_siswa=$data['nama_siswa'].' | '.$data['kode_siswa'].' | '.$data['nis'] ;
                       ?>
                         <input id="kode_siswa" type="text" name="kode_siswa" class="validate" value="<?php echo $nama_siswa; ?>" disabled>
                          <label for="kode_siswa">Nama Siswa</label>
                      </div>
                      <div class="input-field col s12">
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
                        <a href="?menu=kelassiswa" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Kembali">
                          <i class="large mdi-content-undo"></i>
                        </a>
                        </div>
          <?php } ?>