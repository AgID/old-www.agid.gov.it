Feature: Check the profile taxonomy

  As a administrator
  I want to test if there are taxonomies elements.

@api
  Scenario: Test if Profili taxonomy exists.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy"
    Then I should get a 200 HTTP response
    And I should see the text "Profili"
    When I go to "/admin/structure/taxonomy/manage/profiles/overview"
    Then I should get a 200 HTTP response
    And I should see the text "Name"
    And I should see the text "Cittadini"

@api
  Scenario: Check if the "Cittadini" term has some tagged content.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy/manage/profiles/overview"
    Then I should get a 200 HTTP response
    And I should see the text "Cittadini"
    And I follow "Cittadini"
    Then I should get a 200 HTTP response
    And print current URL
    And I should see "Cittadini" in the "h1.u-text-h2" element
    # We should have at least one content tagged.
    And I should see an "article" element
