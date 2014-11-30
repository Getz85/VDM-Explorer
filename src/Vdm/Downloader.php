<?php
/**
 * Created by PhpStorm.
 * User: Gaëtan
 * Date: 28/11/2014
 * Time: 21:59
 */

namespace Vdm;


class Downloader {

    /**
     * @var string
     */
    private $url;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function __construct($url){
        $this->url = $url;
    }

    /**
     * @param int $page
     * @throws \Exception
     * @return \SimpleHtmlDom\simple_html_dom
     */
    public function call($page = 0){
        $url = sprintf($this->url, $page);
        $page_content = \SimpleHtmlDom\file_get_html($url);
        if (!$page_content) {
            throw new \Exception("Impossible de récuperer la page à partir de l'url " . $url);
        }
        return $page_content;
    }
} 