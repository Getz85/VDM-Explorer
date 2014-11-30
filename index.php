<?php
/**
 * Created by PhpStorm.
 * User: Gaëtan
 * Date: 26/11/2014
 * Time: 17:48
 */
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->contentType('text/html; charset=utf-8');
$app->response->headers->set('Content-Type', 'application/json');

$app->get('/api/posts', function() use($app){
    $posts = new \Vdm\Posts();
    $posts->loadFromFile(dirname(__FILE__)."/data/data.json");
    $get_values = $app->request->get();
    if(array_key_exists("author", $get_values)){
        $posts = $posts->filterByAuthor($get_values["author"]);
    }
    if(array_key_exists("from", $get_values) || array_key_exists("to", $get_values)){
        $from = array_key_exists("from", $get_values) ? \DateTime::createFromFormat('Y-m-d', $get_values["from"]) : null;
        $to = array_key_exists("to", $get_values) ? \DateTime::createFromFormat('Y-m-d', $get_values["to"]) : null;
        $posts = $posts->filterByDate($from,$to);
    }
    $app->response->status(200);
    $app->response->setBody(json_encode(
        array(
            "posts" => $posts->getArrayCopy(),
            "count" => $posts->count()
        )
    ));
});

$app->get('/api/posts/:id', function($id) use($app){
    $posts = new \Vdm\Posts();
    $posts->loadFromFile(dirname(__FILE__)."/data/data.json");
    $id = ctype_digit($id) ? intval($id) : null;
    if ($id === null){
        $app->response->status(400);
    }else{
        $post = $posts->filterByID(intval($id));
        if($post === false){
            $app->response->status(404);
        }else{
            $app->response->status(200);
            $app->response->setBody(json_encode(
                array(
                    "post" => $post->jsonSerialize()
            )));
        }
    }

});

$app->run();