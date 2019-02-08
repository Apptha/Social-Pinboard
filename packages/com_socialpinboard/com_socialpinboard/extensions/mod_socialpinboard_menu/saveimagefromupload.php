<?php

$tempValue = rand();
$uploaddir = "images/socialpinboard/temp/";
$userImageDetails = pathinfo($_FILES['uploadfile']['name']);

$file = $uploaddir . $tempValue . '.' . $userImageDetails['extension'];

if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) {
    $res = $tempValue . '.' . $userImageDetails['extension'];
}
echo $res;
?>