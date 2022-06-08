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
        if($result->rowCount() == 0)
            return false;
        return true;
    }

    public function getUser($login)
    {
        $query = "SELECT * FROM `users` 
                WHERE login= '$login'";
        return $this->db->query($query)
            ->fetchObject();
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM `users`
                 WHERE id= $id";
        return $this->db->query($query)
            ->fetchObject();
    }

    private function getComments($post_id){
        $query = "SELECT comments.id,
                comments.comment,
                users.login,
                users.avatar
                FROM comments 
                INNER JOIN users 
                ON comments.user_id = users.id
                WHERE post_id= $post_id";
        return $this->db->query($query)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addComment($user_id, $post_id, $text)
    {
        $query = "INSERT INTO `comments`
                (user_id, post_id, comment)
                VALUES ($user_id, $post_id, '$text')";
        $result = $this->db->query($query);
        if($result->rowCount() == 0)
            return false;
        return true;
    }

    public function getPosts($user, $requestor_id)
    {
        $query = "SELECT * FROM `posts`
                WHERE user_id= $user->id";
        $posts = $this->db->query($query)
            ->fetchAll(PDO::FETCH_ASSOC);
        $new_posts = array();
        foreach ($posts as $post) {
            $comments = $this->getComments($post['id']);
            $arr = array(
                'login' => $user->login,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'comments' => $comments,
                'isUserLiked' => $this->isUserLiked($requestor_id, $post['id'])
            );
            $post = array_merge($post, $arr);
            array_push($new_posts, $post);
        }
        return $new_posts;
    }

    public function isUserLiked($requestor_id, $post_id)
    {
        $query = "SELECT * FROM `likes`
                WHERE user_id= $requestor_id 
                AND post_id= $post_id";
        if ($this->db->query($query)->fetchObject())
            return true;
        return false;
    }

    public function isUserFollowed($user_id, $follower_id)
    {
        $query = "SELECT * FROM `follows`
                WHERE user_id= $user_id 
                AND follower_id= $follower_id";
        if ($this->db->query($query)->fetchObject())
            return true;
        return false;
    }

    public function uploadPost($login, $audio, $video, $image, $text)
    {
        $audioVariable = ($audio == '') ? '' : "$this->siteLink/audio/$audio.mp3";
        $videoVariable = ($video == '') ? '' : "$this->siteLink/videos/$video.mp4";
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
        $query = "SELECT user_id FROM `follows`
                WHERE follower_id= $user_id";
        $follows_ids = $this->db->query($query)
            ->fetchAll(PDO::FETCH_ASSOC);
        $arr = array();
        foreach ($follows_ids as $follow_id) {
            $user_obj = $this->getUserById($follow_id['user_id']);
            $user_posts = $this->getPosts($user_obj, $user_id);
            array_push($arr, $user_posts);
        }
        $posts = array();
        foreach ($arr as $user_posts) {
            foreach ($user_posts as $user_post) {
                array_push($posts, $user_post);
            }
        }
        return $posts;
    }

    public function dislike($user_id,$post_id)
    {
        $query = "UPDATE `posts` SET likes = likes - 1
                WHERE id= $post_id;
                DELETE FROM `likes`
                WHERE post_id= $post_id
                AND user_id= $user_id";
        $result = $this->db->query($query);
        if($result->rowCount() == 0)
            return false;
        return true;
    }

    public function like($user_id, $post_id)
    {
        $query = "UPDATE `posts` SET likes = likes + 1
                WHERE id= $post_id;
                INSERT INTO `likes` (post_id, user_id)
                VALUES ($post_id, $user_id)";
        $result = $this->db->query($query);
        if($result->rowCount() == 0){
            return false;
        }
        return true;
    }

    public function getPost($post_id)
    {
        $query = "SELECT * FROM `posts`
                WHERE id= $post_id";
        return $this->db->query($query)
            ->fetchObject();
    }


    public function getUsers()
    {
        $query = "SELECT * FROM `users`";
        return $this->db->query($query)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function follow($user_id, $follower_id)
    {
        $query = "INSERT INTO `follows`
                (user_id, follower_id)
                VALUES ($user_id, $follower_id)";
        $result = $this->db->query($query);
        if($result->rowCount() == 0)
            return false;
        return true;
    }

    public function unfollow($user_id, $follower_id)
    {
        $query = "DELETE FROM `follows`
                WHERE user_id= $user_id
                AND follower_id= $follower_id";
        $result = $this->db->query($query);
        if($result->rowCount() == 0)
            return false;
        return true;
    }

    public function updateDescription($user_id, $description)
    {
        $query = "UPDATE `users`
                SET description= '$description'
                WHERE id= $user_id";
        $result = $this->db->query($query);
        if($result->rowCount() == 0)
            return false;
        return true;
    }

    public function updateAvatar($user_id, $avatar)
    {
        $query = "UPDATE `users`
                SET avatar= '$avatar'
                WHERE id= $user_id";
        $this->db->query($query);
        return $avatar;
    }

    public function updateName($user_id, $name)
    {
        $query = "UPDATE `users`
                SET name= '$name'
                WHERE id= $user_id";
        $result = $this->db->query($query);
        if($result->rowCount() == 0)
            return false;
        return true;
    }


    public function deletePost($post_id){
        $query = "DELETE FROM `posts`
                WHERE id= $post_id";
        $result = $this->db->query($query);
        if($result->rowCount() == 0)
            return false;
        return true;
    }

}