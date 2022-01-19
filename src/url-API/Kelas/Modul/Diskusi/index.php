<?php
session_start();
require("../../../../setting/connection.php");
require("../../../../setting/connection-log.php");
require("../../../../setting/controller/Diskusi.php");

$method	        = $_REQUEST;
$table          = $db->diskusi_modul;
$diskusiClass	= new Diskusi();

if(isset($method['action'])){

    if($method['action'] == 'insert-topik'){
        $idModul    = $method['id_modul'];
        $judul      = $method['judul'];
        $deskripsi  = $method['deskripsi'];
        $kelas      = $method['kelas'];
        $creator    = $method['creator'];

        $insert  = array("id_modul"=>"$idModul", "id_reply"=>"NULL", "judul"=>"$judul", "deskripsi"=>"$deskripsi", "creator"=>"$creator", "date_created"=>date('Y-m-d H:i:s'));

        $table->insert($insert);
        if ($insert) {
            $newID  = $insert['_id'];
            $status = "Success";

            //------ Menulis LOG ---------
            $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$creator", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$kelas", "aksi"=>"38", "id_data"=>"$newID", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));
        }else {
            $status = "Failed";
        }

        $listTopik	= $diskusiClass->getListbyModul($idModul);
        ?>

        <section class="tabs-section">
            <div class="tab-content no-styled profile-tabs">
                <form id="form-tambah-topik" class="box-typical" method="post" action="">
                    <input type="text" class="write-something" name="topik" id="topik" placeholder="Topik Diskusi" title="Topik Diskusi" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-content="Silahkan isikan topik diskusi yang akan dibuat!" required/>
                    <textarea class="write-something" name="textTopik" id="textTopik" placeholder="Apa yang ingin anda diskusikan?" title="Hal yang didiskusikan" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-content="Silahkan isikan apa yang akan ada didiskusikan!" style="min-height: 88px;" required></textarea>
                    <div class="box-typical-footer">
                        <div class="tbl">
                            <div class="tbl-row">
                                <div class="tbl-cell tbl-cell-action">
                                    <button type="submit" name="postingText" class="btn btn-rounded pull-right">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form><!--.box-typical-->
                <?php
                foreach ($listTopik['data'] as $posting) {
                    $image		= empty($posting['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$posting['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
                    $listReply	= $diskusiClass->getListReply($posting['_id']);
                ?>
                    <article class="box-typical profile-post <?php echo ($posting['_id'] == $newID) ? 'new': '' ?>" style="border: 2px solid #d8e2e7">
                        <div class="profile-post-header">
                            <div class="user-card-row">
                                <div class="tbl-row">
                                    <div class="tbl-cell tbl-cell-photo">
                                            <?=$image?>
                                    </div>
                                    <div class="tbl-cell">
                                        <div class="user-card-row-name"><?=$posting['user']?> &nbsp; <i class="fa fa-play" style="font-size: 70%; display: inline-block;"></i> &nbsp; <?=$posting['judul']?></div>
                                        <div class="color-blue-grey-lighter"><?=selisih_waktu($posting['date_created'])?></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if ($_SESSION['lms_id'] == $posting['creator']) {
                                ?>
                            <a class="shared" onclick="removePost('<?=$posting['_id']?>')" title="Hapus" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Tombol untuk menghapus Diskusi yang sudah dibuat.">
                                <i class="font-icon font-icon-trash"></i>
                            </a>
                            <?php } ?>
                        </div>
                        <div class="profile-post-content">
                            <p>
                                <?=nl2br($posting['deskripsi'])?>
                            </p>
                        </div>
                        <?php

                        ?>
                        <div class="box-typical-footer profile-post-meta">
                            <a href="#demo<?=$posting['_id']?>" data-toggle="collapse" data-parent="#accordion" class="meta-item">
                                <?=$listReply['count']?> Comment
                            </a>
                            <a href="" onclick="return writeReply('<?=$posting['_id']?>');" class="meta-item">
                                <i class="font-icon font-icon-comment"></i>
                                Komentari
                            </a>
                        </div>
                        <div class="comment-rows-container" style="position: static;background-color: #ecf2f5; max-height: none;">
                            <div id="demo<?=$posting['_id']?>" class="collapse">
                            <?php
                                foreach ($listReply['data'] as $reply) {
                                    $image				= empty($reply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$reply['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
                                    $listCommentReply	= $diskusiClass->getListReply($reply['_id']);
                            ?>
                            <div class="comment-row-item">
                                <div class="tbl-row">
                                    <div class="avatar-preview avatar-preview-32">
                                        <a href="#"><?=$image?></a>
                                    </div>
                                    <div class="tbl-cell comment-row-item-header">
                                        <div class="user-card-row-name" style="font-weight: 600"><?=$reply['user']?></div>
                                        <div class="color-blue-grey-lighter" style="font-size: .875rem"><?=selisih_waktu($reply['date_created'])?></div>
                                    </div>
                                </div>
                                <div class="comment-row-item-content" style="margin-top: 5px;">
                                    <p><?=$reply['deskripsi']?></p>
                                    <div class="comment-row-item-box-typical-footer profile-post-meta" style="border-top: 1px solid #ccc; margin-top: 10px; padding-top: 10px;">
                                        <a href="#demo<?=$reply['_id']?>" data-toggle="collapse" data-parent="#accordion" class="meta-item" style="font-size: .875rem">
                                            <?=$listCommentReply['count']?> Comment
                                        </a>
                                        <a href="" onclick="return writeCommentReply('<?=$reply['_id']?>');" class="meta-item" style="font-size: .875rem">
                                            <i class="font-icon font-icon-comment"></i>
                                            Komentari
                                        </a>
                                    </div>
                                </div>
                                <div id="demo<?=$reply['_id']?>"  class="collapse">
                                <?php
                                foreach ($listCommentReply['data'] as $commentReply) {
                                    $image		= empty($commentReply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$commentReply['foto']."' style='max-width: 75px; max-height: 75px;' />" ;
                                ?>
                                <div class="comment-row-item quote" style="padding-right: 45px;">
                                    <div class="tbl-row">
                                        <div class="avatar-preview avatar-preview-32">
                                            <a href="#"><?=$image?></a>
                                        </div>
                                        <div class="tbl-cell comment-row-item-header">
                                            <div class="user-card-row-name" style="font-weight: 600"><?=$commentReply['user']?></div>
                                            <div class="color-blue-grey-lighter" style="font-size: .875rem"><?=selisih_waktu($commentReply['date_created'])?></div>
                                        </div>
                                    </div>
                                    <div class="comment-row-item-content" style="margin-top: 5px;">
                                        <p><?=$commentReply['deskripsi']?></p>
                                    </div>
                                </div><!--.comment-row-item-->
                                <?php } ?>
                                </div>
                            </div><!--.comment-row-item-->
                            <div class="comment-row-item" id="for-comment-reply-<?=$reply['_id']?>" style="padding-right: 60px;">

                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        <input id="write-reply-<?=$posting['_id']?>" onfocus="setReplyComment('<?=$posting['_id']?>');" type="text" class="write-something comment" placeholder="Tuliskan komentar disini"/>
                        </div><!--.comment-rows-container-->
                    </article>
                <?php
                }
                ?>
            </div><!--.tab-content-->
        </section><!--.tabs-section-->
<?php
    }

    if($method['action'] == 'insert-reply'){
        $idReply    = $method['id_reply'];
        $komentar   = $method['komentar'];
        $creator    = $method['creator'];
        $kelas      = $method['kelas'];

        $insert  = array("id_modul"=>"NULL", "id_reply"=>"$idReply", "judul"=>"NULL", "deskripsi"=>"$komentar", "creator"=>"$creator", "date_created"=>date('Y-m-d H:i:s'));

        $table->insert($insert);
        if ($insert) {
            $newID  = $insert['_id'];
            $status = "Success";

            //------ Menulis LOG ---------
            $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_sekolah"=>$_SESSION['lms_sekolah'], "id_user"=>"$creator", "id_kelas"=>"$kelas", "aksi"=>"40", "id_data"=>"$newID", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));
        }else {
            $status = "Failed";
        }

        $listReply	= $diskusiClass->getListReply($idReply);
        ?>
        <text id="new-jumlah-comment-<?=$idReply?>" style="display: none;"><?=$listReply['count']?></text>
        <?php

        foreach ($listReply['data'] as $reply) {
            $image				= empty($reply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$reply['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
            $listCommentReply	= $diskusiClass->getListReply($reply['_id']);
        ?>
        <div class="comment-row-item <?php echo ($reply['_id'] == $newID) ? 'new': '' ?>">
            <div class="tbl-row">
                <div class="avatar-preview avatar-preview-32">
                    <a href="#"><?=$image?></a>
                </div>
                <div class="tbl-cell comment-row-item-header">
                    <div class="user-card-row-name" style="font-weight: 600"><?=$reply['user']?></div>
                    <div class="color-blue-grey-lighter" style="font-size: .875rem"><?=selisih_waktu($reply['date_created'])?></div>
                </div>
            </div>
            <div class="comment-row-item-content" style="margin-top: 5px;">
                <p><?=$reply['deskripsi']?></p>
                <div class="comment-row-item-box-typical-footer profile-post-meta" style="border-top: 1px solid #ccc; margin-top: 10px; padding-top: 10px;">
                    <a href="#demo<?=$reply['_id']?>" data-toggle="collapse" data-parent="#accordion" class="meta-item" style="font-size: .875rem">
                        <?=$listCommentReply['count']?> Comment
                    </a>
                    <a href="" onclick="return writeCommentReply('<?=$reply['_id']?>');" class="meta-item" style="font-size: .875rem">
                        <i class="font-icon font-icon-comment"></i>
                        Komentari
                    </a>
                </div>
            </div>
            <div id="demo<?=$reply['_id']?>"  class="collapse">
            <?php
            foreach ($listCommentReply['data'] as $commentReply) {
                $image		= empty($commentReply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$commentReply['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
            ?>
            <div class="comment-row-item quote" style="padding-right: 45px;">
                <div class="tbl-row">
                    <div class="avatar-preview avatar-preview-32">
                        <a href="#"><?=$image?></a>
                    </div>
                    <div class="tbl-cell comment-row-item-header">
                        <div class="user-card-row-name" style="font-weight: 600"><?=$commentReply['user']?></div>
                        <div class="color-blue-grey-lighter" style="font-size: .875rem"><?=selisih_waktu($commentReply['date_created'])?></div>
                    </div>
                </div>
                <div class="comment-row-item-content" style="margin-top: 5px;">
                    <p><?=$commentReply['deskripsi']?></p>
                </div>
            </div><!--.comment-row-item-->
            <?php } ?>
            </div>
        </div><!--.comment-row-item-->
        <div class="comment-row-item" id="for-comment-reply-<?=$reply['_id']?>" style="padding-right: 60px;">

        </div>
        <?php
        }
    }

    if($method['action'] == 'insert-comment-reply'){
        $idReply    = $method['id_reply'];
        $komentar   = $method['komentar'];
        $creator    = $method['creator'];
        $kelas      = $method['kelas'];

        $insert  = array("id_modul"=>"NULL", "id_reply"=>"$idReply", "judul"=>"NULL", "deskripsi"=>"$komentar", "creator"=>"$creator", "date_created"=>date('Y-m-d H:i:s'));

        $table->insert($insert);
        if ($insert) {
            $newID  = $insert['_id'];
            $status = "Success";

            //------ Menulis LOG ---------
            $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$creator", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$kelas", "aksi"=>"40", "id_data"=>"$newID", "data"=>$insert, "date_created"=>date('Y-m-d H:i:s')));
        }else {
            $status = "Failed";
        }

        $listCommentReply	= $diskusiClass->getListReply($idReply);
        ?>
        <text id="new-jumlah-comment-reply-<?=$idReply?>" style="display: none;"><?=$listCommentReply['count']?></text>
        <?php

        foreach ($listCommentReply['data'] as $commentReply) {
            $image		= empty($commentReply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$commentReply['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
        ?>
        <div class="comment-row-item quote <?php echo ($commentReply['_id'] == $newID) ? 'new-comment-reply': '' ?>" style="padding-right: 45px;">
            <div class="tbl-row">
                <div class="avatar-preview avatar-preview-32">
                    <a href="#"><?=$image?></a>
                </div>
                <div class="tbl-cell comment-row-item-header">
                    <div class="user-card-row-name" style="font-weight: 600"><?=$commentReply['user']?></div>
                    <div class="color-blue-grey-lighter" style="font-size: .875rem"><?=selisih_waktu($commentReply['date_created'])?></div>
                </div>
            </div>
            <div class="comment-row-item-content" style="margin-top: 5px;">
                <p><?=$commentReply['deskripsi']?></p>
            </div>
        </div><!--.comment-row-item-->
        <?php
        }
    }

}

    function selisih_waktu($timestamp){
        $selisih = time() - strtotime($timestamp) ;
        $detik  = $selisih ;
        $menit  = round($selisih / 60 );
        $jam    = round($selisih / 3600 );
        $hari   = round($selisih / 86400 );
        $minggu = round($selisih / 604800 );
        $bulan  = round($selisih / 2419200 );
        $tahun  = round($selisih / 29030400 );
        if ($detik <= 60) {
            $waktu = $detik.' detik yang lalu';
        } else if  ($menit <= 60) {
            $waktu = $menit.' menit yang lalu';
        } else if ($jam <= 24) {
            $waktu = $jam.' jam yang lalu';
        } else if ($hari <= 7) {
            $waktu = $hari.' hari yang lalu';
        } else if ($minggu <= 4) {
            $waktu = $minggu.' minggu yang lalu';
        } else if ($bulan <= 12) {
            $waktu = $bulan.' bulan yang lalu';
        } else {
            $waktu = $tahun.' tahun yang lalu';
        }
        return $waktu;
    }

    if($method['action'] == 'remv'){
        $delete = array("_id" => new MongoId($method['ID']));
        $data   = $table->remove($delete);
        $aksi = ($method['tipe'] == 1) ? 40 : 42;

        //------ Menulis LOG ---------
        $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"$aksi", "id_data"=>"$method[ID]", "date_created"=>date('Y-m-d H:i:s')));

        $resp   = array('response'=>'Terhapus!', 'message'=>'Data berhasil dihapus!', 'icon'=>'success');
        $Json   = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
    }
?>
