<?php
require_once "Database.php";
class PhotoModel extends Database
{
    public function getAllPhotos()
    {
        $sql = parent::$connection->prepare('SELECT * from photos');
        return parent::select($sql);
    }
    public function loadMore($arrPhotoId)
    {
        if(count($arrPhotoId) == 0 ){
            $numi ="";
            $numArrId = "";
        }
        else{
            $numi = str_repeat('i',count($arrPhotoId));
            $numArrId = "NOT IN(" . str_repeat('?,',count($arrPhotoId)-1)."?)";
        }
        $sql = parent::$connection->prepare("SELECT * from photos WHERE photos.id $numArrId LIMIT 3");
        if(count($arrPhotoId) != 0 ){
           
            $sql->bind_param($numi,...$arrPhotoId);
        }
        return parent::select($sql);
    }

    public function like($id)
    {
        $sql = parent::$connection->prepare('UPDATE `photos` SET `likes`=`likes`+1 WHERE `id`=?');
        $sql->bind_param('i', $id);
        $sql->execute();

        $sql = parent::$connection->prepare('SELECT `likes` from photos WHERE `id`=?');
        $sql->bind_param('i', $id);
        return parent::select($sql)[0];
    }

    public function view($id)
    {
        $sql = parent::$connection->prepare('UPDATE `photos` SET `views`=`views`+1 WHERE `id`=?');
        $sql->bind_param('i', $id);
        $sql->execute();

        $sql = parent::$connection->prepare('SELECT `views` from photos WHERE `id`=?');
        $sql->bind_param('i', $id);
        return parent::select($sql)[0];
    }
    
    public function pin($id)
    {
        $sql = parent::$connection->prepare('UPDATE `photos` SET `pins`=`pins`+1 WHERE `id`=?');
        $sql->bind_param('i', $id);
        $sql->execute();

        $sql = parent::$connection->prepare('SELECT `pins` from photos WHERE `id`=?');
        $sql->bind_param('i', $id);
        return parent::select($sql)[0];
    }
    public function search($key)
    {
      $sql =  parent::$connection->prepare("SELECT * FROM `photos` WHERE id = ? OR title LIKE ?");
      $like = "%" . $key . "%";
      $sql->bind_param('is', $key, $like);
      return parent::select($sql) ;
    }
    
}
