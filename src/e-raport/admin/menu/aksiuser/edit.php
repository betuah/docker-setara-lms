<div class="row">
            <div class="col s12 m12 l12">
              <div class="card-panel">
                <h4 class="header2">Form Data User</h4>
                <div class="row">
                  <?php
                  $edit=mysql_query("select * from user where kode_".$menu."='$_GET[id]'")or die("gagal".mysql_error());
                  $cek=mysql_num_rows($edit);
                  $data=mysql_fetch_array($edit);
                   if($cek < 1){

                    echo "<script>swal({
                      title: 'Oops...', 
                      text: 'Data tidak ditemukan!', 
                      type: 'error'
                      }, 
                      function(){ 
                        window.location.href='?menu=guru'; 
                      }); </script>";
                  } else { 

                  ?>
                  <form action="aksi.php?menu=user&aksi=edit" method="post" onSubmit="return edituser(this);" enctype="multipart/form-data" class="col s12" >
                    <div class="row">
                       <div class="input-field col s12">
                          <input id="username" type="text" name="username" value="<?php echo $data['username']; ?>" readonly>
                          <label for="username">Username</label>
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
                          <input id="nama_user" type="text" name="nama_user" value="<?php echo $data['nama_user']; ?>" class="validate">
                          <label for="nama_user">Nama User</label>
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
                        <input id="telp" type="tel" name="telp" length="12" maxlength="12" value="<?php echo $data['telp']; ?>" class="validate" >
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
                        <a href="?menu=user" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Kembali">
                          <i class="large mdi-content-undo"></i>
                        </a>
                        </div>
          <?php } ?>