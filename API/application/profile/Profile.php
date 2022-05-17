<?php
class Profile

{
    function __construct($db, $posts)
    {
        $this->db = $db;
        $this->posts = $posts;
    }

    public function getProfile($login, $requestor_login)
    {
        $user = $this->db->getUser($login);
        $requestor = $this->db->getUser($requestor_login);
        if ($user) {
            $posts = $this->posts->getPosts($login, $requestor_login);
            return array(
                'name' => $user->name,
                'avatar' => $user->avatar,
                'login' => $user->login,
                'description' => $user->description,
                'posts' => $posts,
                'isFollowed' => $this->db->isUserFollowed($user->id, $requestor->id)
            );
        }
    }

    public function getNewsFeed($login)
    {
        return $this->db->getNewsFeed($login);
    }

    public function updateDescription($login, $description)
    {
        $user = $this->db->getUser($login);
        if ($user && strlen($description) <= 300)
            return $this->db->updateDescription($user->id, $description);
    }

    public function updateAvatar($login, $avatar)
    {
        $user = $this->db->getUser($login);
        $avatarName = md5(md5($avatar['name'] . rand(0, 1000)) . rand(0, 1000));
        if ($user && $avatar) {
            move_uploaded_file($avatar['tmp_name'], '../images/' . $avatarName . '.png');
            $avatarName = $this->db->siteLink . "/images/$avatarName.png";
            return $this->db->updateAvatar($user->id, $avatarName);
        }
    }

    public function updateName($login, $name)
    {
        $user = $this->db->getUser($login);
        if ($user && strlen($name) <= 40)
            return $this->db->updateName($user->id, $name);
    }
}