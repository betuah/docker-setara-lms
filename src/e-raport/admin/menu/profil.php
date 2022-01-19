<div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card-panel">
                             <h5 class="light">Data Profil</h5>

                            <div class="divider"></div>
                            <div class="section"></div>

<div class="row">
<div class="col s12 m12 l12">

                  <div id="profile-card" class="card">
                    <div class="card-image waves-effect waves-block waves-light">
                      <img class="activator" src="../images/uuuu.jpg" alt="user bg">
                    </div>
                    <div class="card-content">
                      <img src="foto/<?php echo $_SESSION['foto']; ?>" alt="" height="80px" width="80px" class="circle activator card-profile-image" >
                      <a class="btn-floating activator btn-move-up waves-effect waves-light teal darken-2 right tooltipped" data-position="left" data-delay="50" data-tooltip="Detail">
                        <i class="mdi-editor-mode-edit"></i>
                      </a>
                       <?php
                      $username=$_SESSION['user'];
                      $query=mysql_query("SELECT * FROM user WHERE username='$username'");
                      $data=mysql_fetch_array($query);
                      ?>
                      <span class="card-title activator grey-text text-darken-4"><?php echo $_SESSION['nama']; ?></span>
                      <p><i class="mdi-action-perm-identity"></i> <?php echo $_SESSION['hak']; ?></p>
                      <p><i class="mdi-action-perm-phone-msg"></i> <?php echo $_SESSION['telp']; ?></p>
                      <p><i class="mdi-communication-email"></i> <?php echo $data['email'];  ?></p>

                    </div>
                    <div class="card-reveal">
                      <span class="card-title grey-text text-darken-4"><?php echo $_SESSION['nama']; ?> <i class="mdi-navigation-close right"></i></span>
                      <p>Berikut beberapa informasi tentang diri saya dan kontak yang bisa dihubungi.</p>
                      <p><i class="mdi-action-perm-identity"></i> <?php echo $_SESSION['hak']; ?></p>
                      <p><i class="mdi-action-perm-phone-msg"></i> <?php echo $_SESSION['telp']; ?></p>
                      <p><i class="mdi-communication-email"></i><?php echo $data['email'];  ?></p>
                    </div>
                  </div>
                </div>
    </div>
</div>
</div>
</div>
