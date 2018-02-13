Feature: Sitewide elements

  As a anonymous user
  I want to test if there are base site informations.

@api
  Scenario: Test anonymous can see agid site name.
    Given I am an anonymous user
    And I am on the homepage
    Then I should get a 200 HTTP response
    And I should see the text "Agenzia per l'Italia Digitale"

@api
  Scenario: Admin can see backend theme.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/content"
    And I should see a ".agid-backend" element

@api
  Scenario: in the Appearance settings AgID should be the default theme.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/appearance"
    Then I should get a 200 HTTP response
    And I should see "AgID" in the ".theme-default .theme-info__header" element

@api
  Scenario: Test anonymous can see 404 and 403 pages.
    Given I am an anonymous user
    # 404 Page.
    When I go to "/page-not-real-only-to-test-404-page"
    Then I should get a 404 HTTP response
    Then I should see a ".ErrorPage" element
    Then I should see "404" in the ".ErrorPage-title" element
    Then I should see a ".ErrorPage .ErrorPage-subtitle" element
    # 403 Page.
    When I go to "/user/1"
    Then I should get a 403 HTTP response
    Then I should see a ".ErrorPage" element
    Then I should see "403" in the ".ErrorPage-title" element
    Then I should see a ".ErrorPage .ErrorPage-subtitle" element

@api
  Scenario: Test "Sidebar Left" region is present
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/block/list/agid"
    Then I should see "Sidebar Left"
