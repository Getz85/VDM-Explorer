<?php
/**
 * Created by PhpStorm.
 * User: Gaëtan
 * Date: 30/11/2014
 * Time: 14:31
 */

namespace Vdm;


class PostsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Posts
     */
    public static $test_posts;

    public static function setUpBeforeClass(){
        self::$test_posts = new Posts(array(
            new Post(
                8433124,
                "Aujourd'hui, j'accompagne mon grand-père de 76 ans à son footing matinal. J'ai abandonné au bout de dix minutes. Lui est revenu une heure plus tard, en pleine forme. VDM",
                \DateTime::createFromFormat('Y-m-d H:m:s', "2014-11-28 16:11:00"),
                "Anonyme"),
            new Post(
                8433017,
                "Aujourd'hui, en rentrant de soirée, je réalise que j'ai oublié ma clé. Mon colocataire dort tellement profondément qu'après une heure de lancer de cailloux et de coups de sonnette, j'ai dû me résigner à prendre une chambre à l'hôtel d'en face. Pour 85€ j'ai vue sur ma chambre. VDM",
                \DateTime::createFromFormat('Y-m-d H:m:s', "2014-11-28 12:11:00"),
                "LadyGrey"
            ),
            new Post(
                8430907,
                "Aujourd'hui, j'ai commenté la vidéo d'un contact suédois sur Facebook. Je lui écris que je ne comprenais rien au suédois mais que la vidéo respirait la joie. C'était un hommage funéraire. VDM",
                \DateTime::createFromFormat('Y-m-d H:m:s', "2014-11-25 07:11:00"),
                "Anonyme"
            ),
            new Post(
                8430550,
                "Aujourd'hui, c'était séance de saut en longueur. Je me lance, et c'est là que mon entraineur crie : \"Allez Maxime, saute aussi loin que tu es con.\" J'ai battu le record. VDM",
                \DateTime::createFromFormat('Y-m-d H:m:s', "2014-11-24 19:11:00"),
                "Dragon52000"
            )
        ));
    }

    /**
     * Test to load posts from file
     * @test
     */
    public function testLoadFromFile(){
        $posts = new Posts();
        $posts->loadFromFile(dirname(__FILE__)."/ressource/data.json");
        $this->assertEquals(6, $posts->count(), "The number of posts retrieved from the file is incorrect");
    }

    /**
     * Test filtering the posts by author only
     * @test
     */
    public function testFilterByAuthor(){
        $filtered_posts = self::$test_posts->filterByAuthor('Anonyme');
        $this->assertEquals(2,$filtered_posts->count(), "The number of posts filtered by author 'Anonyme' is incorrect");
        $filtered_posts = self::$test_posts->filterByAuthor('Unknown Author');
        $this->assertEquals(0,$filtered_posts->count(), "The number of posts filtered by author 'Unknown Author' is incorrect");
    }

    /**
     * Test filtering by date: only 'from' date, only 'to' date and both
     * @test
     */
    public function testFilterByDate(){
        $filtered_posts = self::$test_posts->filterByDate(new \DateTime("2014-11-26"), null);
        $this->assertEquals(2,$filtered_posts->count(), "The number of posts filtered by date from '2014-11-26' is incorrect");
        $filtered_posts = self::$test_posts->filterByDate(null, new \DateTime("2014-11-24"));
        $this->assertEquals(1,$filtered_posts->count(), "The number of posts filtered by date to '2014-11-24' is incorrect");
        $filtered_posts = self::$test_posts->filterByDate(new \DateTime("2014-11-25"), new \DateTime("2014-11-30"));
        $this->assertEquals(3,$filtered_posts->count(), "The number of posts filtered by date between '2014-11-25'  and '2014-11-30' is incorrect");
        $filtered_posts = self::$test_posts->filterByDate(new \DateTime("2014-11-21"), new \DateTime("2014-11-23"));
        $this->assertEquals(0,$filtered_posts->count(), "The number of posts filtered by date between '2014-11-21'  and '2014-11-23' is incorrect");
        $filtered_posts = self::$test_posts->filterByDate(new \DateTime("2014-11-28"), new \DateTime("2014-11-28"));
        $this->assertEquals(2,$filtered_posts->count(), "The number of posts filtered by date between '2014-11-28'  and '2014-11-28' is incorrect");
    }

    /**
     * Test filtering posts by id
     * @test
     */
    public function testFilterById(){
        $post = self::$test_posts->filterById(8430550);
        $this->assertEquals(8430550,$post->getId(), "The id of post filtered by id '8430550' is incorrect");
        $this->assertEquals("Aujourd'hui, c'était séance de saut en longueur. Je me lance, et c'est là que mon entraineur crie : \"Allez Maxime, saute aussi loin que tu es con.\" J'ai battu le record. VDM",
            $post->getContent(),
            "The content of post filtered by id '8430550' is incorrect");
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:m:s', "2014-11-24 19:11:00")->getTimestamp(),
            $post->getDate()->getTimestamp(),
            "The date of post filtered by id '8430550' is incorrect");
        $this->assertEquals("Dragon52000", $post->getAuthor(),
            "The author of post filtered by id '8430550' is incorrect");
        $no_post = self::$test_posts->filterByID(84305508);
        $this->assertFalse($no_post, "The return of an unkonw id must be false");
    }

}
 