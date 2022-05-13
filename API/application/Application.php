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
        if ($_FILES['image']['error'] == 0) {
            $imageName = md5(md5($_FILES['image']['name']) . rand(0, 1000)) . rand(0, 1000);
            move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $imageName . '.png');
        }
        if ($_FILES['video']['error'] == 0) {
            $videoName = md5(md5($_FILES['video']['name']) . rand(0, 1000)) . rand(0, 1000);
            move_uploaded_file($_FILES['video']['tmp_name'], '../videos/' . $videoName . '.mp4');
        }
        if ($_FILES['audio']['error'] == 0) {
            $audioName = md5(md5($_FILES['audio']['name']) . rand(0, 1000)) . rand(0, 1000);
            move_uploaded_file($_FILES['audio']['tmp_name'], '../audio/' . $audioName . '.mp3');
        }
        return $this->db->uploadPost($params['login'], $audioName, $videoName, $imageName, $params['text']);
    }

    public function getPosts($login)
    {
        if ($login)
            return $this->db->getPosts($login);
    }

    public function getProfile($login)
    {
        if ($login)
            return $this->profile->getProfile($login);
    }

    public function getNewsFeed($login)
    {
        if ($login)
            return $this->profile->getNewsFeed($login);
    }

    public function getUsers()
    {
        return $this->db->getUsers();
    }
    
    public function like($params)
    {
        $post_id = $params['id'];
        return $this->db->like($post_id);
    }
}
