<?php
/**
 * Created by PhpStorm.
 * User: Gaëtan
 * Date: 30/11/2014
 * Time: 16:37
 */

use Behat\Behat\Context\BehatContext;

class FeatureContext extends BehatContext
{

    public $base_url;

    public $result;

    public $http_code;
    /**
     * @Given /^I am in the directory src\\batch$/
     */
    public function iAmInTheDirectorySrcBatch()
    {
        chdir(dirname(__DIR__). "/../../src/batch/");
    }


    /**
     * @When /^I run "([^"]*)"$/
     */
    public function iRun($command)
    {
        exec($command);
    }


    /**
     * @Then /^I should get a non\-empty file "([^"]*)"$/
     */
    public function iShouldGetANonEmptyFile($filename)
    {
        $file = dirname(__DIR__). "/../../data/" . $filename;
        if(!file_exists($file)){
            throw new Exception("The file doesn't exist");
        }
        if(filesize($file) == 0){
            throw new Exception("The file is empty");
        }
    }

    /**
     * @Given /^I have access to the api located on "([^"]*)"$/
     */
    public function iHaveAccessToTheApiLocatedOn($base_url)
    {
            $this->base_url = $base_url;
    }

    /**
     * @When /^I access to "([^"]*)"$/
     */
    public function iAccessTo($api)
    {
        $curl = curl_init($this->base_url . $api);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if(!curl_errno($curl))
        {
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE );
            $this->http_code = intval($http_code);
        }else{
            throw new Exception(curl_error($curl));
        }
        $this->result = json_decode($result, true);

        curl_close($curl);
    }

    /**
     * @Then /^I should get a result with (\d+) results$/
     */
    public function iShouldGetAResultWithResults($number_of_results)
    {
        $number_of_results = intval($number_of_results);
        if($this->result['count'] !== $number_of_results || sizeof($this->result['posts']) !== $number_of_results){
            throw new Exception("The api call didn't send " . $number_of_results . " results");
        }
    }

    /**
     * @Then /^I should get a status (\d+) as http code$/
     */
    public function iShouldGetAStatusAsHttpCode($http_code)
    {
        if(intval($http_code) !== $this->http_code){
            throw new Exception("The http code returns by the api is " . $this->http_code . " instead of " . $http_code);
        }
    }


}