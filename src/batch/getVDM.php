<?php
/**
 * Created by PhpStorm.
 * User: Gaëtan
 * Date: 26/11/2014
 * Time: 18:00
 */

require_once __DIR__. '/../../vendor/autoload.php';

try {
    $parser = new \Vdm\Parser("http://www.viedemerde.fr/?page=%s");
    $posts = $parser->getPosts();
    $json_posts = json_encode($posts, JSON_UNESCAPED_UNICODE);
    file_put_contents( __DIR__. '/../../data/data.json', $json_posts);
    echo "Les articles ont été correctement récuperés";
    return;
}catch(Exception $ex){
    echo $ex->getMessage();
}



