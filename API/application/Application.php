<?php
require_once('db/DB.php');
require_once('users/Users.php');
require_once('profile/Profile.php');
require_once('posts/Posts.php');

class Application
{
    public function __construct()
    {
        $db = new DB();
        $this->db = $db;
        $this->users = new Users($db);
        $this->posts = new Posts($db);
        $this->profile = new Profile($db, $this->posts);
    }

    /*User methods */
    public function login($params)
    {
        $login = $params['login'];
        $hash = $params['hash'];
        $rand = $params['rand'];
        if ($login && $hash && $rand)
            return $this->users->login($login, $hash, $rand);
    }

    public function follow($params)
    {
        $user_login = $params['userLogin'];
        $follower_login = $params['followerLogin'];
        if ($user_login && $follower_login)
            return $this->users->follow($user_login, $follower_login);
    }

    public function unfollow($params)
    {
        $user_login = $params['userLogin'];
        $follower_login = $params['followerLogin'];
        if ($user_login && $follower_login)
            return $this->users->unfollow($user_login, $follower_login);
    }

    public function registration($params)
    {
        $login = $params['login'];
        $hash = $params['hash'];
        $name = $params['name'];
        if ($login && $hash && $name)
            return $this->users->registration($login, $hash, $name);
    }
    /*Posts methods */

    public function uploadPost($params)
    {
        $login = $params['login'];
        $text = $params['text'];
        if ($_FILES['image']) {
            $imageName = md5(md5($_FILES['image']['name']) . rand(0, 1000)) . rand(0, 1000);
        }
        if ($_FILES['video']) {
            $videoName = md5(md5($_FILES['video']['name']) . rand(0, 1000)) . rand(0, 1000);
        }
        if ($_FILES['audio']) {
            $audioName = md5(md5($_FILES['audio']['name']) . rand(0, 1000)) . rand(0, 1000);
        }
        return $this->posts->uploadPost($login, $audioName, $videoName, $imageName, $text);
    }

    public function getPosts($params)
    {
        $login = $params['login'];
        if ($login)
            return $this->posts->getPosts($login, $login);
    }

    public function like($params)
    {
        $login = $params['login'];
        $post_id = $params['id'];
        if ($login && $post_id)
            return $this->posts->like($login, $post_id);
    }

    public function dislike($params)
    {
        $login = $params['login'];
        $post_id = $params['id'];
        return $this->posts->dislike($login, $post_id);
    }

    public function deletePost($params)
    {
        $login = $params['login'];
        $post_id = $params['id'];
        if ($login && $post_id)
            return $this->posts->deletePost($login, $post_id);
    }

    public function addComment($params)
    {
        $login = $params['login'];
        $post_id = $params['id'];
        $text = $params['text'];
        if ($login && $post_id)
            return $this->posts->addComment($login, $post_id, $text);
    }


    /*Profile methods */

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

    public function updateDescription($params)
    {
        $login = $params['login'];
        $description = $params['description'];
        if ($login && $description)
            return $this->profile->updateDescription($login, $description);
    }

    public function updateAvatar($params)
    {
        $login = $params['login'];
        $avatar = $_FILES['avatar'];
        if ($login && $avatar)
            return $this->profile->updateAvatar($login, $avatar);
    }

    public function updateName($params)
    {
        $login = $params['login'];
        $name = $params['name'];
        if ($login && $name)
            return $this->profile->updateName($login, $name);
    }

    /*DB Methods */

    public function getUsers()
    {
        return $this->db->getUsers();
    }
}
