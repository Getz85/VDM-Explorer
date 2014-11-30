<?php
/**
 * Created by PhpStorm.
 * User: Gaëtan
 * Date: 28/11/2014
 * Time: 22:21
 */

namespace Vdm;


class ParserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Downloader
     */
    public $downloader;

    const NUMBER_OF_POSTS_TEST_1 = 15;

    const NUMBER_OF_POSTS_TEST_2 = 2;


    public function setUp(){
        $this->downloader = new Downloader(dirname(__FILE__). "/ressource/vdm_page_%s.html");
    }

    /**
     * Test getting  posts by parsing them form multiple html pages
     * Get 15 articles: 12 from the first page, 3 from the second one
     * @test
     */
    public function testGetPostsOnMultiplePages(){
        $parser = new Parser($this->downloader,1);
        $articles = $parser->getPosts(self::NUMBER_OF_POSTS_TEST_1);
        $this->assertEquals(self::NUMBER_OF_POSTS_TEST_1, sizeof($articles), "The number of posts retrieved is incorrect");
    }

    /**
     * Test getting some post and verify the informations retrieved
     * @test
     */
    public function testGetPostsCheckInformations(){
        $parser = new Parser($this->downloader,1);
        $articles = $parser->getPosts(self::NUMBER_OF_POSTS_TEST_2);
        $this->assertEquals(self::NUMBER_OF_POSTS_TEST_2, sizeof($articles), "The number of posts retrieved is incorrect");
        $article_expected1 = new Post(8433017,
            "Aujourd'hui, en rentrant de soirée, je réalise que j'ai oublié ma clé. Mon colocataire dort tellement profondément qu'après une heure de lancer de cailloux et de coups de sonnette, j'ai dû me résigner à prendre une chambre à l'hôtel d'en face. Pour 85€ j'ai vue sur ma chambre. VDM",
            new \DateTime("2014/11/28 12:19", new \DateTimeZone("Europe/Paris")),
            "Lady-Grey");

        $article_expected2 = new Post(8432995,
            "Aujourd'hui, je prends le tram. Je bouscule sans faire exprès une petite fille de plus ou moins 5 ans, et je m'excuse aussitôt. J'avance un peu et prends place sur un siège de libre. J'entends alors cette même petite fille dire à sa mère : \"Maman, y'a un bougnoule qui m'a poussée.\" VDM",
            new \DateTime("2014/11/28 10:54", new \DateTimeZone("Europe/Paris")),
            "IcedGifted"
        );
        $this->assertEquals($article_expected1->getId(),$articles[0]->getId(), "The 'id' of the first post is incorrect");
        $this->assertEquals($article_expected1->getContent(),$articles[0]->getContent(), "The 'content' of the first post is incorrect");
        $this->assertEquals($article_expected1->getAuthor(),$articles[0]->getAuthor(), "The 'author' of the first post is incorrect");
        $this->assertEquals($article_expected1->getDate(),$articles[0]->getDate(), "The 'date' of the first post is incorrect");

        $this->assertEquals($article_expected2->getId(),$articles[1]->getId(), "The 'id' of the second post is incorrect");
        $this->assertEquals($article_expected2->getContent(),$articles[1]->getContent(), "The 'content' of the second post is incorrect");
        $this->assertEquals($article_expected2->getAuthor(),$articles[1]->getAuthor(), "The 'author' of the second post is incorrect");
        $this->assertEquals($article_expected2->getDate(),$articles[1]->getDate(), "The 'date' of the second post is incorrect");
    }
}
 