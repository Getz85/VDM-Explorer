# features/getVDM/features
Feature: getVDM
  In order to read articles from vdm
  As a user
  I need to get them from the site viedemerde.fr

  Scenario: Get posts from VDM
    Given I am in the directory src\batch
    When I run "php getVDM.php"
    Then I should get a non-empty file "data.json"