<?php
session_start();
require("../../../../setting/connection.php");
require("../../../../setting/connection-log.php");
require("../../../../setting/controller/Materi.php");

$method = $_REQUEST;
$table  = $db->materi;
$materiClass   = new Materi();

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

if(isset($method['action'])){
    if($method['action'] == 'show'){
        $ID     = new MongoId($method['ID']);
        $data   = $table->findOne(array("_id" => $ID));
        $resp   = array('data'=>$data);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'showAll'){
        $catch  = $table->find(array());
        foreach ($catch as $row) {
            $data[]   = $row;
        }
        $count  = $catch->count();
        $resp   = array('count'=>$count, 'data'=>$data);
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'remv'){
        $delete = array("_id" => new MongoId($method['ID']));
        $data   = $table->remove($delete);

        //------ Menulis LOG ---------
        $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"23", "id_data"=>"$method[ID]", "date_created"=>date('Y-m-d H:i:s')));

        $resp   = array('response'=>'Terhapus!', 'message'=>'Data berhasil dihapus!', 'icon'=>'success');
		$Json   = json_encode($resp);
		header('Content-Type: application/json');

		echo $Json;
	}

    if($method['action'] == 'insert-reply'){
        $idMateri   = $method['id_materi'];
        $komentar   = $method['komentar'];
        $creator    = $method['creator'];

        $insert  = array("id_materi"=>"$idMateri", "id_reply"=>"NULL", "deskripsi"=>"$komentar", "creator"=>"$creator", "date_created"=>date('Y-m-d H:i:s'));

        $db->materi_komentar->insert($insert);
        if ($insert) {
            $newID  = $insert['_id'];
            $status = "Success";

            //------ Menulis LOG ---------
            $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$creator", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"24", "id_data"=>"$newID", "data"=>$data, "date_created"=>date('Y-m-d H:i:s')));

        }else {
            $status = "Failed";
        }

        $listReply = $materiClass->getComment($idMateri);

        ?>
        <text id="new-jumlah-comment-<?=$idMateri?>" style="display: none;"><?=$listReply['count']?></text>
        <?php

        foreach ($listReply['data'] as $reply) {
            $image              = empty($reply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$reply['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
            $listCommentReply   = $materiClass->getCommentReply($reply['_id']);
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
                        <?=$listCommentReply['count']?> Balasan
                    </a>
                    <a href="" onclick="return writeCommentReply('<?=$reply['_id']?>');" class="meta-item" style="font-size: .875rem">
                        <i class="font-icon font-icon-comment"></i>
                        Balas
                    </a>
                </div>
            </div>
            <div id="demo<?=$reply['_id']?>"  class="collapse">
            <?php
            foreach ($listCommentReply['data'] as $commentReply) {
                $image      = empty($commentReply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$commentReply['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
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

        $insert  = array("id_materi"=>"NULL", "id_reply"=>"$idReply", "deskripsi"=>"$komentar", "creator"=>"$creator", "date_created"=>date('Y-m-d H:i:s'));

        $db->materi_komentar->insert($insert);
        if ($insert) {
            $newID  = $insert['_id'];
            $status = "Success";

            //------ Menulis LOG ---------
            $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$creator", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"24", "id_data"=>"$newID", "data"=>$data, "date_created"=>date('Y-m-d H:i:s')));
        }else {
            $status = "Failed";
        }

        $listCommentReply   = $materiClass->getCommentReply($idReply);
        ?>
        <text id="new-jumlah-comment-reply-<?=$idReply?>" style="display: none;"><?=$listCommentReply['count']?></text>
        <?php

        foreach ($listCommentReply['data'] as $commentReply) {
            $image      = empty($commentReply['user_foto']) ? "<img src='assets/img/avatar-2-128.png' style='max-width: 75px; max-height: 75px;' />" : "<img src='http://sumberbelajar.seamolec.org/Assets/foto/".$commentReply['user_foto']."' style='max-width: 75px; max-height: 75px;' />" ;
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

    if($method['action'] == 'remvPost'){
        $delete = array("_id" => new MongoId($method['ID']));
        $data   = $db->materi_komentar->remove($delete);

        //------ Menulis LOG ---------
        $log    = $dbLog->log_action->insert(array("IP_address"=>$_SERVER['REMOTE_ADDR'], "id_user"=>"$method[user]", "id_sekolah"=>$_SESSION['lms_sekolah'], "id_kelas"=>"$method[kelas]", "aksi"=>"25", "id_data"=>"$method[ID]", "data"=>$data, "date_created"=>date('Y-m-d H:i:s')));

        $resp   = array('response'=>'Terhapus!', 'message'=>'Data berhasil dihapus!', 'icon'=>'success');
        $Json   = json_encode($resp);
        header('Content-Type: application/json');

        echo $Json;
    }

}

?>
