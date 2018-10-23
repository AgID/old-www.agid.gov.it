Feature: Homepage elements

  As a anonymous user
  I want to test the homepage.

  @api
  Scenario: Test anonymous can access homepage.
    Given I am an anonymous user
    And I am on the homepage
    Then I should get a 200 HTTP response

  @api
  Scenario: Test anonymous can access homepage.
    Given I am an anonymous user
    And I am on the homepage
    Then I should see an "#block-views-block-home-page-news-block-1" element
    And I should see an "#block-views-block-home-page-highlight-block-1" element
    And I should see an "#block-views-block-home-page-last-updates-block-1" element
    And I should see an "#block-homepageplatforms" element
