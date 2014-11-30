<?php
/**
 * Created by PhpStorm.
 * User: Gaëtan
 * Date: 27/11/2014
 * Time: 22:31
 */

namespace Vdm;


class Posts extends \ArrayObject
{

    public function __construct($array = array())
    {
        parent::__construct($array, \ArrayObject::ARRAY_AS_PROPS);
    }

    public function __ToString()
    {
        return 'Array';
    }

    /**
     * Load posts from json file
     * @param string $file_path path to json file
     * @throws \Exception
     */
    public function loadFromFile($file_path)
    {
        if (is_file($file_path)) {
            $posts = json_decode(file_get_contents($file_path), true);
            foreach ($posts as $post) {
                $this->append(new Post(intval($post['id']), $post['content'], \DateTime::createFromFormat('Y-m-d H:m:s', $post['date']), $post['author']));
            }
        } else {
            throw new \Exception(sprintf("Le fichier %s est introuvable", $file_path));
        }
    }

    /**
     * Return a Posts Object filtered by the author post
     * @param string $author
     * @return Posts
     */
    public function filterByAuthor($author)
    {
        return new Posts(
            array_filter($this->getArrayCopy(), function ($item) use ($author) {
                /** @var $item Post */
                return $item->getAuthor() === $author;
            }));
    }

    /**
     * Return a Posts Object filtered between $from and $to
     * @param \DateTime $from
     * @param \DateTime $to
     * @return Posts
     */
    public function filterByDate($from, $to)
    {
        //Only check nullness for $to, $from can be used with a null value
        if(empty($to)){
            //Use the current datetime to determine max date
            $to = new \DateTime();
        }
        return new Posts(
            array_filter($this->getArrayCopy(), function ($item) use ($from, $to) {
                /** @var $item Post */
                //Set time to 0 to compare only date
                $item_date = clone $item->getDate();
                $item_date->setTime(0,0,0);
                return ($item_date >= $from && $item_date <= $to);
            }));
    }

    /**
     * Return the post object identified by id, false if not found
     * @param int $id
     * @return Post|false
     */
    public function filterByID($id)
    {
           $filtered_array =  array_values(array_filter($this->getArrayCopy(), function ($item) use ($id) {
                /** @var $item Post */
                return $item->getId() === $id;
            }));
        return (sizeof($filtered_array) > 0)? $filtered_array[0] : false;
    }

    /**
     * Override ArrayCopy to always return correctly indexed array
     * @return array
     */
    public function getArrayCopy(){
        $array = parent::getArrayCopy();
        return array_values($array);
    }

} 