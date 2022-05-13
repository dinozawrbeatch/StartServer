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
        if($result) return true;
        return false;
    }

    public function getUser($login)
    {
        $query = "SELECT * FROM `users` 
                WHERE login= '$login'";
        return $this->db->query($query)
            ->fetchObject();
    }

    public function getPosts($login)
    {
        $user = $this->getUser($login);
        $user_id = $user['id'];
        $query = "SELECT * FROM `posts`
                WHERE user_id= $user_id";
        return $this->db->query($query)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function uploadPost($login, $audio, $video, $image, $text)
    {
        $user = $this->getUser($login);
        $user_id = $user->id;
        $query = "INSERT INTO `posts`
                (user_id, text, audio, video, image)
                VALUES ($user_id, '$text', '$audio', '$video', '$image')"; 
        if($this->db->query($query)) return true;
        return false;
    }

    public function getNewsFeed($login)
    {
        $user = $this->getUser($login);
        $user_id = $user->id;
        $query  = "SELECT ";
        $arr = array(
                array(
                'avatar' => 'http://startserver/images/2.jpg',
                'name' => 'Имя юзверя',
                'text' => 'Текст поста',
                'postImage' => 'http://startserver/images/2.jpg',
                'video' => 'http://startserver/videos/meme.mp4',
                'audio' => 'http://startserver/audios/audio.mp3'
            ),
                array(
                'avatar' => 'http://startserver/images/2.jpg',
                'name' => 'Имя юзверя',
                'text' => 'Текст поста',
                'postImage' => 'http://startserver/images/2.jpg',
                'video' => 'http://startserver/videos/meme.mp4',
                'audio' => 'http://startserver/audios/audio.mp3'
            ),
                array(
                'avatar' => 'http://startserver/images/2.jpg',
                'name' => 'Имя юзверя',
                'text' => 'Текст поста',
                'postImage' => 'http://startserver/images/2.jpg',
                'video' => 'http://startserver/videos/meme.mp4',
                'audio' => 'http://startserver/audios/audio.mp3'
            ),
                array(
                'avatar' => 'http://startserver/images/2.jpg',
                'name' => 'Имя юзверя',
                'text' => 'Текст поста',
                'postImage' => 'http://startserver/images/2.jpg',
                'video' => 'http://startserver/videos/meme.mp4',
                'audio' => 'http://startserver/audios/audio.mp3'
            ),
                array(
                'avatar' => 'http://startserver/images/2.jpg',
                'name' => 'Имя юзверя',
                'text' => 'Текст поста',
                'postImage' => 'http://startserver/images/2.jpg',
                'video' => 'http://startserver/videos/meme.mp4',
                'audio' => 'http://startserver/audios/audio.mp3'
            ),
        );
        return $arr;
    }

    public function like($post_id){
        $query = "UPDATE `posts` SET likes = likes + 1";
        if($this->db->query($query)) return true;
        return false;
    }

    public function getUsers()
    {
        $query = "SELECT * FROM `users`";
        return $this->db->query($query)
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}