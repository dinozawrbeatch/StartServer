<?php
class DB
{
    function __construct()
    {
        $host = 'localhost';
        $port = '3306';
        $name = 'start';
        $user = 'root';
        $pass = '';
        $this->siteLink = 'http://startserver';
        try {
            $this->db = new PDO(
                'mysql:' .
                'host=' . $host . ';' .
                'port=' . $port . ';' .
                'dbname=' . $name,
                $user,
                $pass
                );
        }
        catch (Exception $e) {
            print_r($e->getMessage());
            die();
        }
    }

    public function registration($login, $hash, $name)
    {
        $query = "INSERT INTO `users`
                (login, hash, name) 
                VALUES ('$login', '$hash', '$name')";
        $result = $this->db->query($query);
        if ($result)
            return true;
        return false;
    }

    public function getUser($login)
    {
        $query = "SELECT * FROM `users` 
                WHERE login= '$login'";
        return $this->db->query($query)
            ->fetchObject();
    }

    public function getLoginById($id){
        $query = "SELECT login FROM `users` WHERE id= $id";
        return $this->db->query($query)
            ->fetchObject();
    }

    public function getPosts($login)
    {
        $user = $this->getUser($login);
        $user_id = $user->id;
        $query = "SELECT * FROM `posts`
                WHERE user_id= $user_id";
        return $this->db->query($query)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostsById($id)
    {
        $query = "SELECT * FROM `posts`
                WHERE user_id= $id";
        return $this->db->query($query)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function uploadPost($login, $audio, $video, $image, $text)
    {
        $audioVariable = ($audio == '') ? '' : "$this->siteLink/audio/$audio.mp3";
        $videoVariable = ($video == '') ? '' : "$this->siteLink/video/$video.mp4";
        $imageVariable = ($image == '') ? '' : "$this->siteLink/images/$image.png";
        $user = $this->getUser($login);
        $user_id = $user->id;
        $query = "INSERT INTO `posts`
                (user_id, text, audio, video, image)
                VALUES (
                $user_id,
                '$text', 
                '$audioVariable',
                '$videoVariable',
                '$imageVariable')";
        if ($this->db->query($query))
            return true;
        return false;
    }

    public function getNewsFeed($login)
    {
        $user = $this->getUser($login);
        $user_id = $user->id;
        $query = "SELECT * FROM `follows`
                WHERE follower_id= $user_id";
        $follows_id = $this->db->query($query)
            ->fetchAll(PDO::FETCH_ASSOC);
        $arr = array();
        foreach ($follows_id as $follow) {
            $user_posts=$this->getPostsById($follow['user_id']);
            array_push($arr, $user_posts);
        }
        $posts = array();
        foreach($arr as $user_posts){
            foreach($user_posts as $user_post){
                array_push($posts, $user_post);
            }
        }
        return $posts;
    }

    public function dislike($post_id)
    {
        $query = "UPDATE `posts` SET likes = likes - 1
                WHERE id= $post_id";
        if ($this->db->query($query))
            return true;
        return false;
    }

    public function like($post_id)
    {
        $query = "UPDATE `posts` SET likes = likes + 1
                WHERE id= $post_id";
        if ($this->db->query($query))
            return true;
        return false;
    }

    public function getUsers()
    {
        $query = "SELECT * FROM `users`";
        return $this->db->query($query)
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}