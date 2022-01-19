 <script language="javascript">
 
function validasi_login(form) {
        
         if(form.hak_akses.value=="pilih"){
            Materialize.toast('Pilih Hak Akses terlebih dahulu!', 5000);
            form.hak_akses.focus();
            return (false);
         } 
         re = /^[a-zA-Z0-9]+$/ ;
         if(form.username.value==""){
            Materialize.toast('Username tidak boleh kosong!', 5000);
            form.username.focus();
            return (false);
         } else if (!re.test(form.username.value)){
            Materialize.toast('Input hanya berupa huruf dan angka tanpa spasi!', 5000);
            form.username.focus();
            return (false);
         }
         if(form.password1.value==""){
            Materialize.toast('Password tidak boleh kosong!', 5000);
            form.password1.focus();
            return (false);
         }
        
    }

 <!-- Validasi form guru -->
 function validasi_tambahguru(form) { 
        re = /^[0-9]{18}$/;
         if(form.nip.value==""){
            Materialize.toast('Nip tidak boleh kosong!!', 5000);
            form.nip.focus();
            return (false);
         } else if (!re.test(form.nip.value)){
            Materialize.toast('Input hanya berupa angka 18 digit!', 5000);
            form.nip.focus();
            return (false);
         }
         re = /^[a-zA-Z]+$/ ;
         if(form.username.value==""){
            Materialize.toast('Username tidak boleh kosong!', 5000);
            form.username.focus();
            return (false);
         } else if (!re.test(form.username.value)){
            Materialize.toast('Input hanya berupa huruf a-z tanpa spasi!', 5000);
            form.username.focus();
            return (false);
         }
         
        if(form.password1.value==""){
        Materialize.toast('Password tidak boleh kosong!', 5000);
        form.password1.focus();
        return (false);
        }
        if(form.password2.value==""){
        Materialize.toast('Konfirmasi Password tidak boleh kosong!', 5000);
        form.password2.focus();
        return (false);
        }
        if(form.password1.value == form.password2.value) {
        } else {
        Materialize.toast('Konfirmasi Password tidak cocok!', 5000);
        form.password1.focus();
        return (false);
        }
         re = /^[a-zA-Z  . ,]+$/ ;
         if(form.nama_guru.value==""){
            Materialize.toast('Nama Guru tidak boleh kosong!', 5000);
            form.nama_guru.focus();
            return (false);
         } else if (!re.test(form.nama_guru.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.nama_guru.focus();
            return (false);
         }
         if(form.alamat.value==""){
            Materialize.toast('Alamat tidak boleh kosong!', 5000);
            form.alamat.focus();
            return (false);
         } 
         if(form.kolomstatus.value=="pilih"){
            Materialize.toast('Pilih Status terlebih dahulu!', 5000);
            form.status.focus();
            return (false);
         }
         re = /^[0-9]{11,12}$/;
         if(form.telp.value==""){
            Materialize.toast('Telepon tidak boleh kosong!!', 5000);
            form.telp.focus();
            return (false);
         } else if (!re.test(form.telp.value)){
            Materialize.toast('Input minimal 11 angka maksimal 12 angka!', 5000);
            form.telp.focus();
            return (false);
         }

         if(form.foto.value==""){
            Materialize.toast('Foto tidak boleh kosong!', 5000);
            form.foto.focus();
            return (false);
         }
         if(form.kelas.value=="pilih"){
            Materialize.toast('Pilih Kelas terlebih dahulu!', 5000);
            form.kelas.focus();
            return (false);
        }
         

    }

    function validasi_editguru(form) { 
        if(document.getElementById('gantipass').checked){
            
             if(form.password2.value==""){
            Materialize.toast('Password Baru tidak boleh kosong!', 5000);
            form.password2.focus();
            return (false);
             }
             if(form.password3.value==""){
            Materialize.toast('Konfirmasi Password tidak boleh kosong!', 5000);
            form.password3.focus();
            return (false);
             }
            if(form.password2.value == form.password3.value) {
            } else {
              Materialize.toast('Konfirmasi Password tidak cocok!', 5000);
            form.password2.focus();
            return (false);
            }

        }
        
         re = /^[a-zA-Z  . ,]+$/ ;
         if(form.nama_guru.value==""){
            Materialize.toast('Nama Guru tidak boleh kosong!', 5000);
            form.nama_guru.focus();
            return (false);
         } else if (!re.test(form.nama_guru.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.nama_guru.focus();
            return (false);
         }
         if(form.alamat.value==""){
            Materialize.toast('Alamat tidak boleh kosong!', 5000);
            form.alamat.focus();
            return (false);
         } 
         if(form.status.value=="pilih"){
            Materialize.toast('Pilih Status terlebih dahulu!', 5000);
            form.status.focus();
            return (false);
         }
         re = /^[0-9]{11,12}$/;
         if(form.telp.value==""){
            Materialize.toast('Telepon tidak boleh kosong!!', 5000);
            form.telp.focus();
            return (false);
         } else if (!re.test(form.telp.value)){
            Materialize.toast('Input minimal 11 angka maksimal 12 angka!', 5000);
            form.telp.focus();
            return (false);
         }

         if(form.foto.value==""){
            Materialize.toast('Foto tidak boleh kosong!', 5000);
            form.foto.focus();
            return (false);
         }
         if(form.kelas.value=="pilih"){
            Materialize.toast('Pilih Kelas terlebih dahulu!', 5000);
            form.kelas.focus();
            return (false);
        }
         

    }
 <!-- Tutup Validasi form guru -->

 <!-- Validasi form siswa -->
    function validasi_tambahsiswa(form) {
         if(form.password1.value==""){
        Materialize.toast('Password tidak boleh kosong!', 5000);
        form.password1.focus();
        return (false);
        }
        if(form.password2.value==""){
        Materialize.toast('Konfirmasi Password tidak boleh kosong!', 5000);
        form.password2.focus();
        return (false);
        }
        if(form.password1.value == form.password2.value) {
        } else {
        Materialize.toast('Konfirmasi Password tidak cocok!', 5000);
        form.password1.focus();
        return (false);
        }
         re = /^[a-zA-Z ]+$/ ;
         if(form.nama_siswa.value==""){
            Materialize.toast('Nama Siswa tidak boleh kosong!', 5000);
            form.nama_siswa.focus();
            return (false);
         } else if (!re.test(form.nama_siswa.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.nama_siswa.focus();
            return (false);
         }
         if(form.alamat.value==""){
            Materialize.toast('Alamat tidak boleh kosong!', 5000);
            form.alamat.focus();
            return (false);
         } 
         re = /^[a-zA-Z  -]+$/ ;
         if(form.tmp_lahir.value==""){
            Materialize.toast('Tempat Lahir tidak boleh kosong!', 5000);
            form.tmp_lahir.focus();
            return (false);
         } else if (!re.test(form.tmp_lahir.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.tmp_lahir.focus();
            return (false);
         }
         if(form.tgl_lahir.value==""){
            Materialize.toast('Tanggal Lahir tidak boleh kosong!', 5000);
            form.tgl_lahir.focus();
            return (false);
         }
         if(form.agama.value=="pilih"){
            Materialize.toast('Pilih Agama terlebih dahulu!', 5000);
            form.agama.focus();
            return (false);
         }
         if(form.kolomstatus.value=="pilih"){
            Materialize.toast('Pilih Status terlebih dahulu!', 5000);
            form.kolomstatus.focus();
            return (false);
         }
 
         if(form.tahun_angkatan.value=="pilih"){
            Materialize.toast('Pilih Tahun Angkatan terlebih dahulu!', 5000);
            form.tahun_angkatan.focus();
            return (false);
         }
         re = /^[0-9]{11,12}$/;
         if(form.telp.value==""){
            Materialize.toast('Telepon tidak boleh kosong!!', 5000);
            form.telp.focus();
            return (false);
         } else if (!re.test(form.telp.value)){
            Materialize.toast('Input minimal 11 angka maksimal 12 angka!', 5000);
            form.telp.focus();
            return (false);
         }

         if(form.foto.value==""){
            Materialize.toast('Foto tidak boleh kosong!', 5000);
            form.foto.focus();
            return (false);
         }
    }

     function validasi_editsiswa(form) { 
        if(document.getElementById('gantipass').checked){
            if(form.password1.value==""){
            Materialize.toast('Password sekarang tidak boleh kosong!', 5000);
            form.password1.focus();
            return (false);
             }
             if(form.password2.value==""){
            Materialize.toast('Password Baru tidak boleh kosong!', 5000);
            form.password2.focus();
            return (false);
             }
             if(form.password3.value==""){
            Materialize.toast('Konfirmasi Password tidak boleh kosong!', 5000);
            form.password3.focus();
            return (false);
             }
            if(form.password2.value == form.password3.value) {
            } else {
              Materialize.toast('Konfirmasi Password tidak cocok!', 5000);
            form.password2.focus();
            return (false);
            }

        }
        
         re = /^[a-zA-Z  . ,]+$/ ;
         if(form.nama_siswa.value==""){
            Materialize.toast('Nama Siswa tidak boleh kosong!', 5000);
            form.nama_siswa.focus();
            return (false);
         } else if (!re.test(form.nama_siswa.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.nama_siswa.focus();
            return (false);
         }
         if(form.alamat.value==""){
            Materialize.toast('Alamat tidak boleh kosong!', 5000);
            form.alamat.focus();
            return (false);
         }
         re = /^[a-zA-Z  -]+$/ ;
         if(form.tmp_lahir.value==""){
            Materialize.toast('Tempat Lahir tidak boleh kosong!', 5000);
            form.tmp_lahir.focus();
            return (false);
         } else if (!re.test(form.tmp_lahir.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.tmp_lahir.focus();
            return (false);
         }
         if(form.tgl_lahir.value==""){
            Materialize.toast('Tanggal Lahir tidak boleh kosong!', 5000);
            form.tgl_lahir.focus();
            return (false);
         }
         if(form.agama.value=="pilih"){
            Materialize.toast('Pilih Agama terlebih dahulu!', 5000);
            form.agama.focus();
            return (false);
         } 
         if(form.pilihstatus.value=="pilih"){
            Materialize.toast('Pilih Status terlebih dahulu!', 5000);
            form.pilihstatus.focus();
            return (false);
         }
         if(form.tahun_angkatan.value=="pilih"){
            Materialize.toast('Pilih Tahun Angkatan terlebih dahulu!', 5000);
            form.tahun_angkatan.focus();
            return (false);
         }
         re = /^[0-9]{11,12}$/;
         if(form.telp.value==""){
            Materialize.toast('Telepon tidak boleh kosong!!', 5000);
            form.telp.focus();
            return (false);
         } else if (!re.test(form.telp.value)){
            Materialize.toast('Input minimal 11 angka maksimal 12 angka!', 5000);
            form.telp.focus();
            return (false);
         }

         if(form.foto.value==""){
            Materialize.toast('Foto tidak boleh kosong!', 5000);
            form.foto.focus();
            return (false);
         }
    }
     <!-- Tutup Validasi form siswa -->


       <!-- Validasi form user -->
    function validasi_tambahuser(form) {
        re = /^[a-zA-Z]+$/ ;
         if(form.username.value==""){
            Materialize.toast('Username tidak boleh kosong!', 5000);
            form.username.focus();
            return (false);
         } else if (!re.test(form.username.value)){
            Materialize.toast('Input hanya berupa huruf a-z tanpa spasi!', 5000);
            form.username.focus();
            return (false);
         }
          if(form.password1.value==""){
        Materialize.toast('Password tidak boleh kosong!', 5000);
        form.password1.focus();
        return (false);
        }
        if(form.password2.value==""){
        Materialize.toast('Konfirmasi Password tidak boleh kosong!', 5000);
        form.password2.focus();
        return (false);
        }
        if(form.password1.value == form.password2.value) {
        } else {
        Materialize.toast('Konfirmasi Password tidak cocok!', 5000);
        form.password1.focus();
        return (false);
        }
         re = /^[a-zA-Z ]+$/ ;
         if(form.nama_user.value==""){
            Materialize.toast('Nama tidak boleh kosong!', 5000);
            form.nama_user.focus();
            return (false);
         } else if (!re.test(form.nama_user.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.nama_user.focus();
            return (false);
         }
         re = /^[0-9]{11,12}$/;
         if(form.telp.value==""){
            Materialize.toast('Telepon tidak boleh kosong!!', 5000);
            form.telp.focus();
            return (false);
         } else if (!re.test(form.telp.value)){
            Materialize.toast('Input minimal 11 angka maksimal 12 angka!', 5000);
            form.telp.focus();
            return (false);
         }
         if(form.foto.value==""){
            Materialize.toast('Foto tidak boleh kosong!', 5000);
            form.foto.focus();
            return (false);
         }
    }
    <!-- Tutup Validasi form user -->

    <!-- Validasi form kelas -->
    function validasi_tambahkelas(form) {
         if(form.tahun_ajar.value=="pilih"){
            Materialize.toast('Pilih Tahun Ajaran terlebih dahulu!', 5000);
            form.tahun_ajar.focus();
            return (false);
         }
         if(form.kelas.value=="pilih"){
            Materialize.toast('Pilih Kelas terlebih dahulu!', 5000);
            form.kelas.focus();
            return (false);
         }
         if(form.nama_kelas.value=="pilih"){
            Materialize.toast('Pilih Nama Kelas terlebih dahulu!', 5000);
            form.nama_kelas.focus();
            return (false);
         }
         if(form.kolomstatus.value=="pilih"){
            Materialize.toast('Pilih Status terlebih dahulu!', 5000);
            form.kolomstatus.focus();
            return (false);
         }
        re = /^[a-zA-Z0-9 . , |]+$/ ;
         if(form.kode_guru.value==""){
            Materialize.toast('Nama Wali Kelas tidak boleh kosong!', 5000);
            form.kode_guru.focus();
            return (false);
         } else if (!re.test(form.kode_guru.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.kode_guru.focus();
            return (false);
         }
    }
    <!-- Tutup Validasi form kelas -->

    <!-- Validasi form kelas siswa -->
    function validasi_tambahkelassiswa(form) {
         if(form.kode_kelas.value=="pilih"){
            Materialize.toast('Pilih Nama Kelas terlebih dahulu!', 5000);
            form.kode_kelas.focus();
            return (false);
         }
         if(form.jurusan.value=="pilih"){
            Materialize.toast('Pilih Jurusan terlebih dahulu!', 5000);
            form.jurusan.focus();
            return (false);
         }
        re = /^[a-zA-Z0-9 . , |]+$/ ;
         if(form.kode_siswa.value==""){
            Materialize.toast('Nama Siswa tidak boleh kosong!', 5000);
            form.kode_siswa.focus();
            return (false);
         } else if (!re.test(form.kode_siswa.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.kode_siswa.focus();
            return (false);
         }
    }
    <!-- Tutup Validasi form kelas siswa-->

    function validasi_pelajaran(form) {
        re = /^[a-zA-Z ]+$/ ;
         if(form.nama_pelajaran.value==""){
            Materialize.toast('Nama mata pelajaran tidak boleh kosong!', 5000);
            form.nama_pelajaran.focus();
            return (false);
         } else if (!re.test(form.nama_pelajaran.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.nama_pelajaran.focus();
            return (false);
         }
         if(form.keterangan.value=="pilih"){
            Materialize.toast('Pilih Keterangan terlebih dahulu!', 5000);
            form.keterangan.focus();
            return (false);
         }
        
    }
    </script>
    <script type="text/javascript">

  function edituser(form)
  {
     if(document.getElementById('gantipass').checked){
            if(form.password1.value==""){
            Materialize.toast('Password sekarang tidak boleh kosong!', 5000);
            form.password1.focus();
            return (false);
             }
             if(form.password2.value==""){
            Materialize.toast('Password Baru tidak boleh kosong!', 5000);
            form.password2.focus();
            return (false);
             }
             if(form.password3.value==""){
            Materialize.toast('Konfirmasi Password tidak boleh kosong!', 5000);
            form.password3.focus();
            return (false);
             }
            if(form.password2.value == form.password3.value) {
            } else {
              Materialize.toast('Konfirmasi Password tidak cocok!', 5000);
            form.password2.focus();
            return (false);
            }

    }
    re = /^[a-zA-Z ]+$/ ;
         if(form.nama_user.value==""){
            Materialize.toast('Nama tidak boleh kosong!', 5000);
            form.nama_user.focus();
            return (false);
         } else if (!re.test(form.nama_user.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.nama_user.focus();
            return (false);
         }
         re = /^[0-9]{11,12}$/;
         if(form.telp.value==""){
            Materialize.toast('Telepon tidak boleh kosong!!', 5000);
            form.telp.focus();
            return (false);
         } else if (!re.test(form.telp.value)){
            Materialize.toast('Input minimal 11 angka maksimal 12 angka!', 5000);
            form.telp.focus();
            return (false);
         }
         
    

  
  }

  <!-- Validasi form nilai -->
    function validasi_tambahdatanilai(form) {
         if(form.semester.value=="pilih"){
            Materialize.toast('Pilih Semester terlebih dahulu!', 5000);
            form.semester.focus();
            return (false);
         }
         if(form.kode_pelajaran.value=="pilih"){
            Materialize.toast('Pilih Pelajaran terlebih dahulu!', 5000);
            form.kode_pelajaran.focus();
            return (false);
         }
         re = /^[a-zA-Z0-9 . , |]+$/ ;
         if(form.kode_guru.value==""){
            Materialize.toast('Nama Wali Kelas tidak boleh kosong!', 5000);
            form.kode_guru.focus();
            return (false);
         } else if (!re.test(form.kode_guru.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.kode_guru.focus();
            return (false);
         }
         if(form.kode_kelas.value=="pilih"){
            Materialize.toast('Pilih Kelas terlebih dahulu!', 5000);
            form.kode_kelas.focus();
            return (false);
         }
         re = /^[a-zA-Z0-9 . , |]+$/ ;
         if(form.kode_siswa.value==""){
            Materialize.toast('Nama Siswa tidak boleh kosong!', 5000);
            form.kode_siswa.focus();
            return (false);
         } else if (!re.test(form.kode_siswa.value)){
            Materialize.toast('Input hanya berupa huruf a-z!', 5000);
            form.kode_siswa.focus();
            return (false);
         }
         /*
         if(form.nilai_tugas.value=="0"){
            Materialize.toast('Harap masukkan Nilai Tugas!', 5000);
            form.nilai_tugas.focus();
            return (false);
         }
         if(form.nilai_uts.value=="0"){
            Materialize.toast('Harap masukkan Nilai UTS!', 5000);
            form.nilai_uts.focus();
            return (false);
         }
         if(form.nilai_uas.value=="0"){
            Materialize.toast('Harap masukkan Nilai UAS!', 5000);
            form.nilai_uas.focus();
            return (false);
         }
         */
        
    }
    <!-- Tutup Validasi form nilai -->

    <!-- Validasi siswa nilai -->
    function validasi_siswaedit(form) { 
            if(form.password1.value==""){
            Materialize.toast('Password sekarang tidak boleh kosong!', 5000);
            form.password1.focus();
            return (false);
             }
             if(form.password2.value==""){
            Materialize.toast('Password Baru tidak boleh kosong!', 5000);
            form.password2.focus();
            return (false);
             }
             if(form.password3.value==""){
            Materialize.toast('Konfirmasi Password tidak boleh kosong!', 5000);
            form.password3.focus();
            return (false);
             }
            if(form.password2.value == form.password3.value) {
            } else {
              Materialize.toast('Konfirmasi Password tidak cocok!', 5000);
            form.password2.focus();
            return (false);
            }
    }
 <!-- Tutup Validasi siswa nilai -->

     <!-- Validasi guru nilai -->
    function validasi_guruedit(form) { 
            if(form.password1.value==""){
            Materialize.toast('Password sekarang tidak boleh kosong!', 5000);
            form.password1.focus();
            return (false);
             }
             if(form.password2.value==""){
            Materialize.toast('Password Baru tidak boleh kosong!', 5000);
            form.password2.focus();
            return (false);
             }
             if(form.password3.value==""){
            Materialize.toast('Konfirmasi Password tidak boleh kosong!', 5000);
            form.password3.focus();
            return (false);
             }
            if(form.password2.value == form.password3.value) {
            } else {
              Materialize.toast('Konfirmasi Password tidak cocok!', 5000);
            form.password2.focus();
            return (false);
            }
    }
 <!-- Tutup Validasi guru nilai -->
</script>
    <script>
    document.onload = disable_enable();
    function disable_enable(pilihan) {
        if(document.getElementById('gantipass').checked) {
        document.getElementById('password1').disabled=false,
        document.getElementById('password2').disabled=false,
        document.getElementById('password3').disabled=false,
        document.getElementById('lihat').disabled=false;
        } else document.getElementById('password1').disabled=true,
                document.getElementById('password2').disabled=true,
                document.getElementById('password3').disabled=true,
                document.getElementById('lihat').disabled=true;
    }
    function getSubKategori() {}

    document.onload = lihatpassword();
    function lihatpassword(pilihan) {
        if(document.getElementById('lihat').checked) {
        document.getElementById('password1').type='text',
        document.getElementById('password2').type='text',
        document.getElementById('password3').type='text';
       
        } else document.getElementById('password1').type='password',
                document.getElementById('password2').type='password',
                document.getElementById('password3').type='password';
               
    }
    function getpass() {}
</script>
