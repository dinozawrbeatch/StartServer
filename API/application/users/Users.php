<?php
class Users 
{
    function __construct($db)
    {
        $this->db = $db;
    }

    public function registration($login, $hash, $name)
    {
        if(strlen($login) > 5 && 
           strlen($hash) == 32 && 
           strlen($name) < 50
        ){
            return $this->db->registration($login, $hash, $name);
        }
    }

    public function login($login, $hash, $rand)
    {
        $user = $this->db->getUser($login);
        if($user){
            return array(
                'avatar' => $user->avatar,
                'login' => $user->login,
                'description' => $user->description,
            );
        }
    }

    public function getUsers($login){
        return $this->db->getUsers($login);
    }
}