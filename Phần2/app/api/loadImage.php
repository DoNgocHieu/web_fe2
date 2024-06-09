<?php
require_once '../../config/database.php';
spl_autoload_register(function ($className)
{
   require_once "../models/$className.php";
});
$input = json_decode(file_get_contents('php://input'), true);

$photoId = $input["photoId"];
$photoModel = new PhotoModel();
$photos = $photoModel->loadMore($photoId);
echo json_encode($photos);