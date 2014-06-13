<?php
    include('./website_metadata_getter.php');
    $filepath = './website_metadata';

    //[讀舊檔]
    $old_data = website_metadata_get();
    //[讀新檔]
    $new_data = array(
        "adminname" => $_POST['adminname'],
        "password" => $_POST['password'],
        "realname" => $_POST['realname'],
        "websitename" => $_POST['websitename'],
    );

    //[比較&覆蓋]
    foreach ($old_data as $key => $value) {
        if( $new_data[$key] == '' ){
            $new_data[$key] = $old_data[$key];
        }
    }

    //[寫入]
    $fp = fopen($filepath, 'w');
    fwrite($fp, json_encode($new_data));
    fclose($fp);

    echo "OK";
