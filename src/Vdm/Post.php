<?php
/**
 * Created by PhpStorm.
 * User: Gaëtan
 * Date: 26/11/2014
 * Time: 18:07
 */

namespace Vdm;

/**
 * Describe an article on viedemerde.fr
 * @package Vdm
 */
class Post implements \JsonSerializable {

    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $content;

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param int $id
     * @param string $content
     * @param \DateTime $date
     * @param string $author
     */
    public function  __construct($id, $content, $date, $author){
        $this->id = $id;
        $this->content = $content;
        $this->date = $date;
        $this->author = $author;
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return array(
            "id" => $this->getId(),
            "content" => $this->getContent(),
            "author" => $this->getAuthor(),
            "date" => $this->getDate()->format('Y-m-d H:m:s')
        );
    }
}