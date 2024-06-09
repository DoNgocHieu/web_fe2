<?php
require_once '../../config/database.php';
spl_autoload_register(function ($className)
{
   require_once "../models/$className.php";
});

$photoModel = new PhotoModel();
$photos = $photoModel->getAllPhotos();
echo json_encode($photos);