<?php
    session_start();

    // Get src.
    $file = $_POST["file"];

    // Image extentions.
    $imgExt = array("gif", "jpeg", "jpg", "png", "blob");

    // Get extension.
    $extension = explode('.', $file)[1];

    if(in_array($extension, $imgExt)){
        $src = "../../media/Media/Gambar/".$_SESSION['lms_id']."/".$file;
    }else{
        $src = "../../media/Media/Dokumen/".$_SESSION['lms_id']."/".$file;
    }

    // Check if file exists.
    if (file_exists($src)) {
        // Delete file.
        unlink($src);
    }
    echo $file;
?>
