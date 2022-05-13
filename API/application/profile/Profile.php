<?php
class Profile

{
    function __construct($db)
    {
        $this->db = $db;
    }

    public function getProfile($login)
    { 
        $user = $this->db->getUser($login);
        if($user){
            $posts = $this->db->getPosts($login);
            return array(
                'avatar' => $user->avatar,
                'login' => $user->login,
                'description' => $user->description,
                'posts' => $posts
            );
        }
    }

    public function getNewsFeed($login)
    {
        return $this->db->getNewsFeed($login);
    }
}