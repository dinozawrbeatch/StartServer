<?php
header('Access-Control-Allow-Origin: *');
header("Content-type: application/json; charset=utf-8");

error_reporting(-1);
require_once('application/Application.php');

function router($params){
    $method = $params['method'];
    if($method){
        $app = new Application();
        switch($method){
            case 'registration': return $app->registration($params);
            case 'login': return $app->login($params);
            case 'getProfile': return $app->getProfile($params);
            case 'getNewsFeed': return $app->getNewsFeed($params);
            case 'getUsers': return $app->getUsers();
            case 'getPosts': return $app->getPosts($params);
            case 'uploadPost': return $app->uploadPost($params);
            case 'like': return $app->like($params);
            case 'dislike': return $app->dislike($params);
            case 'follow': return $app->follow($params);
            case 'unfollow': return $app->unfollow($params);
            case 'updateDescription': return $app->updateDescription($params);
            case 'updateAvatar': return $app->updateAvatar($params);
            case 'updateName': return $app->updateName($params);
            case 'deletePost': return $app->deletePost($params);
            case 'addComment': return $app->addComment($params);
        }
        return false;
    }
}

function answer($data){
    if($data){
        return array(
            'result' => 'ok',
            'data' => $data
        );
    } else {
        return array('result' => 'error');
    }
}

echo json_encode(answer(router(array_merge($_GET,$_POST))));

