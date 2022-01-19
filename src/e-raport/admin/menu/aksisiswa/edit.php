<div class="row">
            <div class="col s12 m12 l12">
              <div class="card-panel">
                <h4 class="header2">Form Data Siswa</h4>
                <div class="row">
                  <?php
                  $edit=mysql_query("select * from siswa where  kode_".$menu."='$_GET[id]'")or die("gagal".mysql_error());
                  $cek=mysql_num_rows($edit);
                  $data=mysql_fetch_array($edit);
                  $tanggal=$data['tgl_lahir'];
                  list($thn,$bln,$tgl)=explode("-", $tanggal);
                  $tanggal_lahir=$tgl."/".$bln."/".$thn;
                  if($cek < 1){

                    echo "<script>swal({
                      title: 'Oops...', 
                      text: 'Data tidak ditemukan!', 
                      type: 'error'
                      }, 
                      function(){ 
                        window.location.href='?menu=siswa'; 
                      }); </script>";
                  } else { 

                  ?>
                  <form action="aksi.php?menu=siswa&aksi=edit" method="post" onSubmit="return validasi_editsiswa(this);" enctype="multipart/form-data" class="col s12" >
                    <div class="row">
                      <div class="input-field col s12">
                        <input id="nip" type="text" name="nis" value="<?php echo $data['nis']; ?>" readonly>
                        <label for="nip">Nomor Induk Siswa</label>
                      </div>
                    </div>
                    <div class="row">
                     <div class="input-field col s12 m6 l6">
                        <label>Jika ingin ganti password, pilih ya.</label><br>
                     </div>
                    
                      <div class="input-field col s12 m6 l6">
                          <div class="switch">
                            <label>
                              Tidak
                              <input type="checkbox" id="gantipass" onChange="getSubKategori; disable_enable(this.value)">
                              <span class="lever"></span>
                              Ya
                            </label>
                          </div>
                      </div>   
                    </div>
                    <br>
                    <div class="row">
                      <div class="input-field col s12">
                          <input id="password1" type="password" name="password1" class="validate">
                          <label for="password1">Password Sekarang</label>
                      </div>   
                    </div>
                    <div class="row">
                      <div class="input-field col s12 m6 l6">
                          <input id="password2" type="password" name="password2" class="validate">
                          <label for="password2">Password Baru</label>
                      </div>  
                      <div class="input-field col s12 m6 l6">
                          <input id="password3" type="password" name="password3" class="validate">
                          <label for="password3">Konfirmasi Password </label>
                      </div>  
                      &nbsp;&nbsp; <input type="checkbox" id="lihat" onChange="getpass; lihatpassword(this.value)" >
                        <label for="lihat">Tampilkan password</label> 
                    </div>
                    <div class="section"></div>
                    <div class="row">
                       <div class="input-field col s12">
                          <input id="nama_siswa" type="text" name="nama_siswa" value="<?php echo $data['nama_siswa']; ?>" class="validate">
                          <label for="nama_siswa">Nama Siswa</label>
                      </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                          <textarea id="alamat" class="materialize-textarea validate" length="200" name="alamat" length="120" maxlength="120"><?php echo $data['alamat']; ?></textarea>
                          <label for="alamat">Alamat</label>
                        </div>
                      </div>
                      <div class="row">
                       <div class="input-field col s6">
                          <input id="tmp_lahir" type="text" name="tmp_lahir" value="<?php echo $data['tmp_lahir']; ?>" class="validate">
                          <label for="tmp_lahir">Tempat Lahir</label>
                      </div>
                      <div class="input-field col s6">
                        <input id="tgl_lahir" type="date" class="datepicker" value="<?php echo $tanggal_lahir; ?>" placeholder="Tanggal Lahir" name="tgl_lahir" class="validate">
                      </div>
                      </div>
                    <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <select name="agama" id="agama">
                          <option value='pilih' disabled >- Pilih agama -</option>
                          <option value="Islam" <?php if($data['agama']=="Islam") { echo "selected"; } ?> >Islam</option>
                          <option value="Kristen" <?php if($data['agama']=="Kristen") { echo "selected"; } ?> >Kristen</option>
                          <option value="Hindu" <?php if($data['agama']=="Hindu") { echo "selected"; } ?> >Hindu</option>
                          <option value="Budha" <?php if($data['agama']=="Budha") { echo "selected"; } ?> >Budha</option>
                        </select>
                        <label>Agama</label>
                      </div>  
                     

                  <div class="input-field col s12 m4 l4">
                        <label for="telp">Jenis Kelamin</label><br>
                  </div>
                          <div class="input-field col s12 m2 l2">
                        
                      <input name="jenis_kelamin" type="radio" value="Laki-laki" id="laki_laki" <?php if($data['jenis_kelamin']=="Laki-laki") { echo "checked"; } ?> >
                      <label for="laki_laki">Laki-laki</label><br>
                    
                    
                      <input name="jenis_kelamin" type="radio" value="Perempuan" id="perempuan" <?php if($data['jenis_kelamin']=="Perempuan") { echo "checked"; } ?> >
                      <label for="perempuan">Perempuan</label>
                    
                          </div>
                  
                 
                  </div>
                  <div class="section"></div>
                  <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <select name="kolomstatus" id="status" >
                          <option value="pilih" selected disabled>- Pilih Status -</option>
                          <option value="Aktif" <?php if($data['status']=="Aktif") { echo "selected"; } ?>>Aktif</option>
                          <option value="Tidak" <?php if($data['status']=="Tidak") { echo "selected"; } ?>>Tidak</option>
                        </select>
                        <label>Status</label>
                      </div>  
                     
                  <div class="input-field col s12 m6 l6">
                        <select name="tahun_angkatan" id="tahun_angkatan" >
                          <option value="pilih" selected disabled>- Pilih Tahun -</option>
                          <?php 
                            $tahun_sekarang=date("Y");
                            $tahun_awal=$tahun_sekarang-2;
                            $tahun_akhir=$tahun_sekarang+2;
                            for($tahun_awal; $tahun_awal<=$tahun_akhir; $tahun_awal++){
                              $tahun_tambah=$tahun_awal+1;
                            ?>
                            <option value='<?php echo $tahun_awal.'/'.$tahun_tambah; ?>' <?php if($data['tahun_angkatan']==$tahun_awal.'/'.$tahun_tambah){ echo "selected"; } ?>><?php echo $tahun_awal.'/'.$tahun_tambah; ?></option>
                          <?php
                            }
                          ?>

                        </select>
                        <label>Tahun Angkatan</label>
                      </div>
                  
                  
                 
                  </div>
                  <div class="row">
                  <div class="file-field input-field col s9 m5 l5">
                       <div class="btn waves-effect waves-light teal lighten-2">
                        <span>File</span>
                        <input type="file" name="edit_foto" >
                      </div>
                      <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Maksimal file gambar 500kb" value="<?php echo $data['foto']; ?>">
                      </div>
                  </div>
                  <div class="input-field col s3 m1 l1"><img src="foto/<?php echo $data['foto']; ?>" width="50" height="50" class="circle valign profile-image materialboxed"></div>
                  <div class="input-field col s12 m6 l6">
                        <input id="telp" type="tel" name="telp" length="12" maxlength="12" value="<?php echo $data['telp']; ?>" class="validate">
                        <label for="telp">Telepon</label>
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
                        <a href="?menu=siswa" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Kembali">
                          <i class="large mdi-content-undo"></i>
                        </a>
                        </div>

            <?php } ?>
