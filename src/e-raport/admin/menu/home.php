  <?php
$query=mysql_query("SELECT (select count(nis) from siswa) as jumlah_siswa,
                           (select count(nip) from guru) as jumlah_guru,
                           (select count(username) from user) as jumlah_admin,
                           (select count(kode_pelajaran) from pelajaran) as jumlah_pelajaran,
                           (select count(kode_kelas) from kelas) as jumlah_kelas,
                           (select count(kode_datakelas) from datakelas) as jumlah_kelassiswa");

$data=mysql_fetch_array($query);
?>
                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                             <h5 class="light">Halaman Dashboard</h5>
              <p class="light"> Hai <code class="language-markup"><?php echo $_SESSION['nama']; ?></code>, selamat datang di aplikasi E-Raport!</p>
                            <div class="divider"></div>
                                  <div id="card-stats" class="section">

              <div class="row">
                            <div class="col s12 m6 l4">
                                <div class="card">
                                    <div class="card-content   teal darken-1 white-text">
                                        <i class="mdi-social-people medium"></i>
                                        <h4 class="card-stats-number"></h4>
                                        <p class="card-stats-compare"><?php echo $data['jumlah_siswa']; ?> <span class="green-text text-lighten-5">Jumlah Warga Belajar</span>
                                        </p>
                                    </div>
                                    <div class="card-action   teal darken-2 white-text " >
                                        <div ><a href="?menu=siswa" class="btn-floating btn-flat white tooltipped" data-position="right" data-delay="50" data-tooltip="Selengkapnya"><i class="mdi-content-forward teal-text"></i></a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="card">
                                    <div class="card-content purple darken-1 white-text">
                                        <i class="mdi-social-person medium"></i>
                                        <h4 class="card-stats-number"></h4>
                                        <p class="card-stats-compare"><?php echo $data['jumlah_guru']; ?> <span class="green-text text-lighten-5">Jumlah Tutor</span>
                                        </p>
                                    </div>
                                    <div class="card-action  purple darken-2">
                                         <div ><a href="?menu=guru" class="btn-floating btn-flat  white tooltipped" data-position="right" data-delay="50" data-tooltip="Selengkapnya"><i class="mdi-content-forward purple-text"></i></a></div>

                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="card">
                                    <div class="card-content grey darken-1 white-text">
                                        <i class="mdi-action-lock medium"></i>
                                        <h4 class="card-stats-number"></h4>
                                        <p class="card-stats-compare"><?php echo $data['jumlah_admin']; ?> <span class="green-text text-lighten-5">Jumlah Admin</span>
                                        </p>
                                    </div>
                                    <div class="card-action grey darken-2">
                                         <div ><a href="?menu=user" class="btn-floating btn-flat white tooltipped" data-position="right" data-delay="50" data-tooltip="Selengkapnya"><i class="mdi-content-forward brown-text"></i></a></div>

                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="card">
                                    <div class="card-content   pink darken-1 white-text">
                                        <i class="mdi-av-my-library-books medium"></i>
                                        <h4 class="card-stats-number"></h4>
                                        <p class="card-stats-compare"><?php echo $data['jumlah_pelajaran']; ?> <span class="green-text text-lighten-5">Jumlah Mata Pelajaran</span>
                                        </p>
                                    </div>
                                    <div class="card-action  pink darken-2">
                                           <div ><a href="?menu=pelajaran" class="btn-floating btn-flat white tooltipped" data-position="right" data-delay="50" data-tooltip="Selengkapnya"><i class="mdi-content-forward pink-text"></i></a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="card">
                                    <div class="card-content  indigo darken-1 white-text">
                                        <i class="mdi-action-home medium"></i>
                                        <h4 class="card-stats-number"></h4>
                                        <p class="card-stats-compare"><?php echo $data['jumlah_kelas']; ?> <span class="green-text text-lighten-5">Jumlah Kelas</span>
                                        </p>
                                    </div>
                                    <div class="card-action  indigo darken-2">
                                          <div ><a href="?menu=kelas" class="btn-floating btn-flat white tooltipped" data-position="right" data-delay="50" data-tooltip="Selengkapnya"><i class="mdi-content-forward indigo-text"></i></a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6 l4">
                                <div class="card">
                                    <div class="card-content  orange darken-1 white-text">
                                        <i class="mdi-action-home medium"></i>
                                        <h4 class="card-stats-number"></h4>
                                        <p class="card-stats-compare"><?php echo $data['jumlah_kelassiswa']; ?> <span class="green-text text-lighten-5">Jumlah Kelas Warga Belajar</span>
                                        </p>
                                    </div>
                                    <div class="card-action  orange darken-2">
                                          <div ><a href="?menu=kelassiswa" class="btn-floating btn-flat white tooltipped" data-position="right" data-delay="50" data-tooltip="Selengkapnya"><i class="mdi-content-forward orange-text"></i></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
                            </div>
                            </div>
                        </div>
                        </div>
