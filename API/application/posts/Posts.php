<?php
class Posts
{
    function __construct($db)
    {
        $this->db = $db;
    }

    private function uploadFiles($imageName, $videoName, $audioName ){
        if ($_FILES['image']) {
            move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $imageName . '.png');
        }

        if ($_FILES['video']) {
            move_uploaded_file($_FILES['video']['tmp_name'], '../videos/' . $videoName . '.mp4');
        }

        if ($_FILES['audio']) {
            move_uploaded_file($_FILES['audio']['tmp_name'], '../audio/' . $audioName . '.mp3');
        }
    }

    public function getPosts($login, $requestor_login)
    {
        $user = $this->db->getUser($login);
        $requestor = $this->db->getUser($requestor_login);
        $requestor_id = $requestor->id;
        if ($user && $requestor)

            return $this->db->getPosts($user, $requestor_id);
    }

    public function like($login, $post_id)
    {
        $user = $this->db->getUser($login);
        $post = $this->db->getPost($post_id);
        if ($user && $post)
            return $this->db->like($user->id, $post->id);
    }

    public function dislike($login, $post_id)
    {
        $user = $this->db->getUser($login);
        if ($user && $post_id)
            return $this->db->dislike($user->id, $post_id);
    }

    public function deletePost($login, $post_id)
    {
        $post = $this->db->getPost($post_id);
        $user = $this->db->getUser($login);
        $postAudio = strstr($post->audio, 'audio/');
        $postImage = strstr($post->image, 'images/');
        $postVideo = strstr($post->video, 'videos/');
        if ($post->user_id !== $user->id) {
            return;
        }
        if ($post) {
            if ($postAudio)
                unlink("../$postAudio");
            if ($postImage)
                unlink("../$postImage");
            if ($postVideo)
                unlink("../$postVideo");
            return $this->db->deletePost($post->id);
        }
    }

    public function uploadPost($login, $audioName, $videoName, $imageName, $text)
    {
        $this->uploadFiles($imageName, $videoName, $audioName);
        if (!$imageName)
            $imageName = '';
        if (!$videoName)
            $videoName = '';
        if (!$audioName)
            $audioName = '';
        return $this->db->uploadPost($login, $audioName, $videoName, $imageName, $text);
    }

    public function addComment($login, $post_id, $text)
    {
        $user = $this->db->getUser($login);
        $post = $this->db->getPost($post_id);
        if($post->id && $user->id && strlen($text) < 100){
            return $this->db->addComment($user->id, $post->id, $text);
        }    
    }
}