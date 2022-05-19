<?php
class Users

{
    function __construct($db)
    {
        $this->db = $db;
    }

    public function registration($login, $hash, $name)
    {
        $user = $this->db->getUser($login);
        if (!$user &&
            strlen($login) > 5 &&
            strlen($hash) == 32 &&
            strlen($name) < 50
        ) {
            return $this->db->registration($login, $hash, $name);
        }
    }

    public function login($login, $hash, $rand)
    {
        $user = $this->db->getUser($login);
        if ($user && md5($user->login . $rand) == $hash) {
            return array(
                'name' => $user->name,
                'avatar' => $user->avatar,
                'login' => $user->login,
                'description' => $user->description,
            );
        }
    }

    public function isUserFollowed($user_login, $follower_login)
    {
        $user = $this->db->getUser($user_login);
        $follower = $this->db->getUser($follower_login);
        if ($user && $follower)
            return $this->db->isUserFollowed($user->id, $follower->id);
    }

    public function follow($user_login, $follower_login)
    {
        $user = $this->db->getUser($user_login);
        $follower = $this->db->getUser($follower_login);
        if ($user && $follower)
            return $this->db->follow($user->id, $follower->id);
    }

    public function unfollow($user_login, $follower_login)
    {
        $user = $this->db->getUser($user_login);
        $follower = $this->db->getUser($follower_login);
        if ($user && $follower)
            return $this->db->unfollow($user->id, $follower->id);
    }

    public function getUsers($login)
    {
        return $this->db->getUsers($login);
    }
}