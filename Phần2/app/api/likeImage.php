<?php
require_once '../../config/database.php';
spl_autoload_register(function ($className)
{
   require_once "../models/$className.php";
});

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['photoId'];

$photoModel = new PhotoModel();
$like = $photoModel->like($id);
echo json_encode($like);