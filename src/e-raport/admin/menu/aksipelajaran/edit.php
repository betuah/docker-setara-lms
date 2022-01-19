<div class="row">
            <div class="col s12 m12 l12">
              <div class="card-panel">
                <h4 class="header2">Form Data Mata Pelajaran</h4>
                <div class="row">
                  <?php
                  $edit=mysql_query("select * from pelajaran where kode_pelajaran='$_GET[id]'")or die("gagal".mysql_error());
                  $cek=mysql_num_rows($edit);
                  $data=mysql_fetch_array($edit);
                   if($cek < 1){

                    echo "<script>swal({
                      title: 'Oops...', 
                      text: 'Data tidak ditemukan!', 
                      type: 'error'
                      }, 
                      function(){ 
                        window.location.href='?menu=pelajaran'; 
                      }); </script>";
                  } else { 

                  ?>
                  <form action="aksi.php?menu=pelajaran&aksi=edit" method="post" onSubmit="return validasi_pelajaran(this);" enctype="multipart/form-data" class="col s12" >
                    <div class="row">
                      <div class="input-field col s12">
                        <input id="kode_pelajaran" type="text" name="kode_pelajaran" value="<?php echo $data['kode_pelajaran']; ?>" readonly>
                        <label for="kode_pelajaran">Kode Mata Pelajaran</label>
                      </div>
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                          <input id="nama_pelajaran" type="text" name="nama_pelajaran" value="<?php echo $data['nama_pelajaran']; ?>" class="validate">
                          <label for="nama_pelajaran">Nama Pelajaran</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="input-field col s12">
                        <select name="keterangan" id="status" >
                          <option value="pilih" disabled>- Pilih Keterangan -</option>
                          <option value="Wajib" <?php if($data['keterangan']=="Wajib"){ echo "selected"; } ?>>Wajib</option>
                          <option value="Tambahan" <?php if($data['keterangan']=="Tambahan"){ echo "selected"; } ?>>Tambahan</option>
                        </select>
                        <label>Keterangan</label>
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
                        <a href="?menu=pelajaran" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Kembali">
                          <i class="large mdi-content-undo"></i>
                        </a>
                        </div>
          <?php } ?>