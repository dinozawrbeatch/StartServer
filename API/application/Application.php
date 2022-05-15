<?php
require_once('db/DB.php');
require_once('users/Users.php');
require_once('profile/Profile.php');

class Application
{
    public function __construct()
    {
        $db = new DB();
        $this->db = $db;
        $this->users = new Users($db);
        $this->profile = new Profile($db);
    }

    public function login($params)
    {
        $login = $params['login'];
        $hash = $params['hash'];
        $rand = $params['rand'];
        if ($login && $hash && $rand)
            return $this->users->login($login, $hash, $rand);
    }

    public function registration($params)
    {
        $login = $params['login'];
        $hash = $params['hash'];
        $name = $params['name'];
        if ($login && $hash && $name)
            return $this->users->registration($login, $hash, $name);
    }

    public function uploadPost($params)
    {
        if ($_FILES['image']) {
            $imageName = md5(md5($_FILES['image']['name']) . rand(0, 1000)) . rand(0, 1000);
            move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $imageName . '.png');
        }
        if ($_FILES['video']) {
            $videoName = md5(md5($_FILES['video']['name']) . rand(0, 1000)) . rand(0, 1000);
            move_uploaded_file($_FILES['video']['tmp_name'], '../videos/' . $videoName . '.mp4');
        }
        if ($_FILES['audio']) {
            $audioName = md5(md5($_FILES['audio']['name']) . rand(0, 1000)) . rand(0, 1000);
            move_uploaded_file($_FILES['audio']['tmp_name'], '../audio/' . $audioName . '.mp3');
        }
        if(!$imageName) $imageName = '';
        if(!$videoName) $videoName = '';
        if(!$audioName) $audioName = '';
        return $this->db->uploadPost($params['login'], $audioName, $videoName, $imageName, $params['text']);
    }

    public function getPosts($params)
    {
        $login = $params['login'];
        if ($login)
            return $this->db->getPosts($login, $login);
    }

    public function getProfile($params)
    {
        $login = $params['login'];
        $requestor = $params['requestor'];
        if ($login && $requestor)
            return $this->profile->getProfile($login, $requestor);
    }

    public function getNewsFeed($params)
    {
        $login = $params['login'];
        if ($login)
            return $this->profile->getNewsFeed($login);
    }

    public function getUsers()
    {
        return $this->db->getUsers();
    }
    
    public function like($params)
    {
        $login = $params['login'];
        $post_id = $params['id'];
        return $this->db->like($post_id, $login);
    }
    
    public function dislike($params)
    {
        $login = $params['login'];
        $post_id = $params['id'];
        return $this->db->dislike($post_id, $login);
    }

    public function follow($params)
    {
        $user_login = $params['userLogin'];
        $follower_login = $params['followerLogin'];
        if($user_login && $follower_login)
            return $this->users->follow($user_login, $follower_login);
    }
    
    public function unfollow($params)
    {
        $user_login = $params['userLogin'];
        $follower_login = $params['followerLogin'];
        if($user_login && $follower_login)
            return $this->users->unfollow($user_login, $follower_login);
    }  
}
