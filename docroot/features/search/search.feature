Feature: Search feature tests

  As a anonymous user
  I want to test site search functionalities.

  @api
  Scenario: Test search form presence.
    Given I am an anonymous user
    And I am on the homepage
    Then I should see an ".Header div.search-block-form" element
    And I should see "Cerca nel sito" in the ".Header .search-block-form .Form-label" element
    Then I should see an ".Footer div.search-block-form" element
    And I should see "Cerca nel sito" in the ".Footer .search-block-form .Form-label" element

  @api
  Scenario: Test that search form is working.
    Given I am an anonymous user
    And I am on the homepage
    Then I should see an "form#search-block-form" element
    Then I fill in "edit-keys" with "testononpresentedanessunaparte"
    When I press "Cerca"
    Then I should get a 200 HTTP response
    And I should see "Cerca testononpresentedanessunaparte"
    And I should see "La ricerca non ha prodotto risultati."
    Then I should see an ".Header div.search-block-form" element
    And I should see "Cerca nel sito" in the ".Header .search-block-form .Form-label" element
    Then I should see an ".Footer div.search-block-form" element
    And I should see "Cerca nel sito" in the ".Footer .search-block-form .Form-label" element
