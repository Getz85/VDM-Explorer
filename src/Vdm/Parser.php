<?php
/**
 * Created by PhpStorm.
 * User: Gaëtan
 * Date: 26/11/2014
 * Time: 22:36
 */

namespace Vdm;

/**
 * Class Parser
 * @package Vdm
 */
class Parser
{

    /**
     * @var Downloader
     */
    private $downloader;

    /**
     * @var int
     */
    private $first_page;

    /**
     * @var int
     */
    private $current_page;

    /**
     * @param Downloader $downloader
     * @param int $first_page
     */
    public function __construct($downloader, $first_page = 0)
    {
        $this->downloader = $downloader;
        $this->first_page = $first_page;
        $this->current_page = $first_page;
    }

    /**
     * Get posts from the specified url, limited by $max_posts
     * @param int $max_posts
     * @return Post[]
     * @throws \Exception
     */
    public function getPosts($max_posts = 200)
    {
        $posts = [];
        while (sizeof($posts) < $max_posts) {
            $articles = $this->callNextPage();
            foreach ($articles as $article) {
                array_push($posts, $this->extractInfos($article));
                if(sizeof($posts) >= $max_posts){
                    break;
                }
            }
        }
        return $posts;
    }

    /**
     *
     * @return mixed
     * @throws \Exception
     */
    private function callNextPage()
    {
        $page_content = $this->downloader->call($this->current_page++);
        $articles = $page_content->find('div.article');
        if (!$articles) {
            throw new \Exception("La page à l'url  ne contient pas d'articles");
        }
        return $articles;
    }

    /**
     * Extract the informations of an articles page
     * @param $article
     * @return Post
     */
    private function extractInfos($article)
    {
        $content = html_entity_decode($article->find('p', 0)->plaintext);
        $id = $article->find('div.date div.left_part a.jTip', 0)->plaintext;
        $infos = $article->find('div.date div.right_part p', 1)->plaintext;
        $id = str_replace("#", "", $id);
        $splitted_infos = explode(' - ', $infos);
        if (sizeof($splitted_infos) > 2) {
            //Force Europe timestamp in chain instead of using the third parameter DateTimeZone which is overided
            $date = \DateTime::createFromFormat("Le d/m/Y à H:i T", $splitted_infos[0] . " +0100");
            //Remove "par "on the beginning of the string
            $author = substr($splitted_infos[2],3, strlen($splitted_infos[2]));
            //If the author has one or many dash in his name, concat the others field
            for($i=3; $i < sizeof($splitted_infos); $i++){
                $author .= $splitted_infos[$i];
            }
            //Remove the gender if present at the end of the chain
            // (check if parenthesis is present at the end of the string, a space end the string if the gender is not set)
            if(strpos($author, ")", strlen($author) - strlen(")")) !== false){
                $author = html_entity_decode(substr($author,0, strrpos($author,"(")));
            }
            $author = trim($author);
        } else {
            $date = "";
            $author = "";
        }
        return new Post(intval($id),$content,$date,$author);

    }


}