<div class="row">
            <div class="col s12 m12 l12">
              <div class="card-panel">
                <h4 class="header2">Form Data Nilai</h4>
                <div class="row">
                  <?php
                  $edit=mysql_query("select * from nilai where kode_nilai='$_GET[id]'")or die("gagal".mysql_error());
                  $cek=mysql_num_rows($edit);
                  $data=mysql_fetch_array($edit);
                   if($cek < 1){

                    echo "<script>swal({
                      title: 'Oops...', 
                      text: 'Data tidak ditemukan!', 
                      type: 'error'
                      }, 
                      function(){ 
                        window.location.href='?menu=nilai'; 
                      }); </script>";
                  } else { 

                  ?>
                  <form action="aksi.php?menu=nilai&aksi=edit" method="post" onSubmit="return validasi_tambahnilai(this);" enctype="multipart/form-data" class="col s12" >
                    <div class="row">
                      <div class="input-field col s12">
                        <input id="kode_nilai" type="text" name="kode_nilai" value="<?php echo $data['kode_nilai']; ?>" readonly>
                        <label for="kode_nilai">Kode Nilai</label>
                      </div>
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                        <select name="semester" id="semester" disabled>
                          <option value="pilih" disabled>- Pilih Semester -</option>
                           <option value="1" <?php if($data['semester']==1){ echo "selected "; } ?>>1 - Ganjil</option>
                           <option value="2" <?php if($data['semester']==2){ echo "selected "; } ?>>2 - Genap</option>
                        </select>
                        <label>Semester</label>
                      </div> 
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                        <select name="kode_pelajaran" id="kode_pelajaran" disabled >
                          <option value="pilih" disabled>- Pilih Pelajaran -</option>
                          <?php
                                    $sql = mysql_query("SELECT * FROM pelajaran ORDER BY kode_pelajaran ASC");
                                    
                                    while($data2 = mysql_fetch_array($sql)){
                          ?>
                                    <option value="<?php echo $data2['kode_pelajaran']; ?>" <?php if($data2['kode_pelajaran']==$data['kode_pelajaran']){ echo "selected"; } ?>><?php echo $data2['nama_pelajaran']; ?></option>'; 
                            <?php
                            }
                                    
                            ?>
                        </select>
                        <label>Pelajaran</label>
                      </div> 
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                       <?php
                       $tampil=mysql_query("SELECT * FROM guru, nilai where kode_nilai='$_GET[id]' AND guru.kode_guru=nilai.kode_guru ");
                       $tampildata=mysql_fetch_array($tampil);
                         $nama_guru=$tampildata['nama_guru'].' | '.$tampildata['kode_guru'].' | '.$tampildata['nip'] ;
                       ?>
                         <input id="kode_guru" type="text" name="kode_guru" class="validate" value="<?php echo $nama_guru; ?>" disabled>
                          <label for="kode_guru">Nama Wali Kelas</label>
                      </div>
                    </div>
                    <div class="section"></div>
                    <h4 class="header">Data Siswa</h4>
                    <div class="divider"></div>
                     <div class="section"></div>
                    <div class="row">
                       <div class="input-field col s12">
                        <select name="kode_kelas" id="kode_kelas" disabled>
                          <option value="pilih" disabled>- Pilih Kelas -</option>
                          <?php
                          $sql = mysql_query("SELECT * FROM kelas ORDER BY tahun_ajar, nama_kelas ASC");
                          if(mysql_num_rows($sql) != 0){
                                        while($data3 = mysql_fetch_array($sql)){
                          ?>
                                       <option value="<?php echo $data3['kode_kelas']; ?>" <?php if($data3['kode_kelas']==$data['kode_kelas']){ echo "selected"; } ?>><?php echo $data3['kelas']; ?> | <?php echo $data3['nama_kelas']; ?> | <?php echo $data3['tahun_ajar']; ?></option>'; 

                          <?php
                          }
                                        }
                          ?>
                        </select>
                        <label>Kelas</label>
                      </div> 
                    </div>
                    <div class="row">
                       <div class="input-field col s12">
                       <?php
                       $tampil=mysql_query("SELECT * FROM siswa, nilai where kode_nilai='$_GET[id]' AND siswa.kode_siswa=nilai.kode_siswa ");
                       $tampildata2=mysql_fetch_array($tampil);
                         $nama_siswa=$tampildata2['nama_siswa'].' | '.$tampildata2['kode_siswa'].' | '.$tampildata2['nis'] ;
                       ?>
                         <input id="kode_siswa" type="text" name="kode_siswa" class="validate" value="<?php echo $nama_siswa; ?>" disabled>
                          <label for="kode_siswa">Nama Siswa</label>
                      </div>
                    </div>
                    <div class="section"></div>
                    <h4 class="header">Data Nilai</h4>
                  <div class="divider"></div>
                  <div class="section"></div>
                    <div class="row">
                     <div class="input-field col s12 m2 l2 range-field">
                       <label>Nilai Tugas</label>
                      </div>
                      <div class="input-field col s12 m9 l9 range-field">
                       <input type="range" name="nilai_tugas" min="0" max="100" step="1" value="<?php echo $data['nilai_tugas']; ?>" oninput="this.form.nilai_tugass.value=this.value" />
                      </div>
                      <div class="input-field col s12 m1 l1 range-field">
                      <input type="number" name="nilai_tugass" min="0" max="100" step="1" value="<?php echo $data['nilai_tugas']; ?>" oninput="this.form.nilai_tugas.value=this.value" />
                      </div>
                    </div>
                    <div class="row">
                     <div class="input-field col s12 m2 l2 range-field">
                       <label>Nilai Tugas 2</label>
                      </div>
                      <div class="input-field col s12 m9 l9 range-field">
                       <input type="range" name="nilai_tugas2" min="0" max="100" step="1" value="<?php echo $data['nilai_tugas2']; ?>" oninput="this.form.nilai_tugass2.value=this.value" />
                      </div>
                      <div class="input-field col s12 m1 l1 range-field">
                      <input type="number" name="nilai_tugass2" min="0" max="100" step="1" value="<?php echo $data['nilai_tugas2']; ?>" oninput="this.form.nilai_tugas2.value=this.value" />
                      </div>
                    </div>
                    <div class="row">
                     <div class="input-field col s12 m2 l2 range-field">
                       <label>Nilai Tugas 3</label>
                      </div>
                      <div class="input-field col s12 m9 l9 range-field">
                       <input type="range" name="nilai_tugas3" min="0" max="100" step="1" value="<?php echo $data['nilai_tugas3']; ?>" oninput="this.form.nilai_tugass3.value=this.value" />
                      </div>
                      <div class="input-field col s12 m1 l1 range-field">
                      <input type="number" name="nilai_tugass3" min="0" max="100" step="1" value="<?php echo $data['nilai_tugas3']; ?>" oninput="this.form.nilai_tugas3.value=this.value" />
                      </div>
                    </div>
                    <div class="row">
                     <div class="input-field col s12 m2 l2 range-field">
                       <label>Nilai UTS</label>
                      </div>
                      <div class="input-field col s12 m9 l9 range-field">
                       <input type="range" name="nilai_uts" min="0" max="100" step="1" value="<?php echo $data['nilai_uts']; ?>" oninput="this.form.nilai_uts2.value=this.value" />
                      </div>
                      <div class="input-field col s12 m1 l1 range-field">
                      <input type="number" name="nilai_uts2" min="0" max="100" step="1" value="<?php echo $data['nilai_uts']; ?>" oninput="this.form.nilai_uts.value=this.value" />
                      </div>
                    </div>
                    <div class="row">
                     <div class="input-field col s12 m2 l2 range-field">
                       <label>Nilai UAS</label>
                      </div>
                      <div class="input-field col s12 m9 l9 range-field">
                       <input type="range" name="nilai_uas" min="0" max="100" step="1" value="<?php echo $data['nilai_uas']; ?>" oninput="this.form.nilai_uas2.value=this.value" />
                      </div>
                      <div class="input-field col s12 m1 l1 range-field">
                      <input type="number" name="nilai_uas2" min="0" max="100" step="1" value="<?php echo $data['nilai_uas']; ?>" oninput="this.form.nilai_uas.value=this.value" />
                      </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                          <textarea id="keterangan" class="materialize-textarea validate" name="keterangan" length="120" maxlength="120" placeholder="Optional"><?php echo $data['keterangan']; ?></textarea>
                          <label for="keterangan">Keterangan</label>
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
                        <a href="?menu=nilai" class="btn-floating btn-large waves-effect waves-light green btn tooltipped" data-position="top" data-delay="50" data-tooltip="Kembali">
                          <i class="large mdi-content-undo"></i>
                        </a>
                        </div>
          <?php } ?>