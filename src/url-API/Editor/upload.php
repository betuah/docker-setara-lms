<?php
    session_start();

    // Image extentions.
    $imgExt = array("gif", "jpeg", "jpg", "png", "blob");

    // Get extension.
    $extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

    // Generate new random name.
    $name = sha1(microtime()) . "." . $extension;

    if(in_array($extension, $imgExt)){
        $destination = "media/Media/Gambar/".$_SESSION['lms_id']."/".$name;
    }else{
        $destination = "media/Media/Dokumen/".$_SESSION['lms_id']."/".$name;
    }

    // Save file in the uploads folder.
    move_uploaded_file($_FILES["file"]["tmp_name"], "../../".$destination);

    // Generate response.
    $response = new StdClass;
    $response->link = $destination;
    echo stripslashes(json_encode($response));
?>
