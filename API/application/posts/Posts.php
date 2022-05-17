<?php
class Posts
{
    function __construct($db){
        $this->db = $db;
    }

    public function getPosts($login, $requestor_login){
        $user = $this->db->getUser($login);
        $requestor = $this->db->getUser($requestor_login);
        $requestor_id = $requestor->id;
        if($user && $requestor)
            return $this->db->getPosts($user, $requestor_id);
    }

    public function like($login, $post_id){
        $user = $this->db->getUser($login);
        if($user && $post_id)
            return $this->db->like($user->id, $post_id);
    }

    public function dislike($login, $post_id){
        $user = $this->db->getUser($login);
        if($user && $post_id)
            return $this->db->dislike($user->id, $post_id);
    }

    public function uploadPost($login, $audioName, $videoName, $imageName, $text)
    {
        if ($_FILES['image']) {
            move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $imageName . '.png');
        }

        if ($_FILES['video']) {
            move_uploaded_file($_FILES['video']['tmp_name'], '../videos/' . $videoName . '.mp4');
        }

        if ($_FILES['audio']) {
            move_uploaded_file($_FILES['audio']['tmp_name'], '../audio/' . $audioName . '.mp3');
        }
        if(!$imageName) $imageName = '';
        if(!$videoName) $videoName = '';
        if(!$audioName) $audioName = '';
        return $this->db->uploadPost($login, $audioName, $videoName, $imageName, $text);
    }
}