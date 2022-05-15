<?php
class Profile

{
    function __construct($db)
    {
        $this->db = $db;
    }

    public function getProfile($login, $requestor)
    {
        $user = $this->db->getUser($login);
        if($user){
            $posts = $this->db->getPosts($login, $requestor);
            return array(
                'name' => $user->name,
                'avatar' => $user->avatar,
                'login' => $user->login,
                'description' => $user->description,
                'posts' => $posts,
                'isFollowed' => $this->db->isUserFollowed($login, $requestor)
            );
        }
    }

    public function getNewsFeed($login)
    {
        return $this->db->getNewsFeed($login);
    }
}