# features/getVDM/features
Feature: API
  In order to read articles from vdm
  As an api user
  I need to be able to call the api

  Scenario: Get all posts
    Given I have access to the api located on "http://127.0.0.1/iAdvize"
    When I access to "/api/posts"
    Then I should get a status 200 as http code
    And I should get a result with 200 results

  Scenario: Get post with author
    Given I have access to the api located on "http://127.0.0.1/iAdvize"
    When I access to "/api/posts?author=test"
    Then I should get a status 200 as http code

  Scenario: Get post with from and to date parameters
    Given I have access to the api located on "http://127.0.0.1/iAdvize"
    When I access to "/api/posts?from=2014-11-12&to=2014-11-20"
    Then I should get a status 200 as http code

  Scenario: Get post with id which not exist
    Given I have access to the api located on "http://127.0.0.1/iAdvize"
    When I access to "/api/posts/65489735231568945"
    Then I should get a status 404 as http code

  Scenario: Get post with id with a malformed parameter
    Given I have access to the api located on "http://127.0.0.1/iAdvize"
    When I access to "/api/posts/test"
    Then I should get a status 400 as http code