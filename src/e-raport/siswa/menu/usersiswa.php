<div id="table-datatables">   
                        <form action="aksi.php?menu=usersiswa&aksi=edit" method="post" onSubmit="return validasi_siswaedit(this);" enctype="multipart/form-data" class="col s12">
                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                            <h4 class="header2">Form Pengaturan</h4>
                             <input id="kode_siswa" type="hidden" name="kode_siswa" value="<?php echo $_SESSION['kode']; ?>" class="validate">
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
                          <div class="row">
                           <div class="input-field col s12 m12 l12">
                            <button class="btn cyan waves-effect waves-light right" type="submit" name="edit">Kirim
                              <i class="mdi-content-send right"></i>
                            </button>
                          </div>
                          </div>
                            </div>
                        </div>
                        </div> 
                        </form>
