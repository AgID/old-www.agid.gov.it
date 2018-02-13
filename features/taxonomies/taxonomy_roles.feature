Feature: Check the roles taxonomy

  As a administrator or an anonymous user
  I want to test if "roles" taxonomy vocabulary is properly configured.

  @api
  Scenario: Test if "roles" taxonomy exists.
    Given I am an anonymous user
    Then check if bundle "roles" of entity type "taxonomy_term" exists

  @api
  Scenario: Test if administrator can see "roles" taxonomy admin page.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy/manage/roles/overview"
    Then I should get a 200 HTTP response
    When I go to "/admin/structure/taxonomy/manage/roles/add"
    Then I should get a 200 HTTP response

  @api
  Scenario: Test if "roles" taxonomy term works correctly.
    Given I am logged in as a user with the "administrator" role
    Given "roles" terms:
      | name           | description          | format     | language |
      | Test term one  | term one description | plain_text | en       |
    When I go to "/admin/structure/taxonomy/manage/roles/overview"
    Then I should see the link "Test term one"
    When I follow "Test term one"
    Then I should get a 200 HTTP response
    And I should see the link "Modifica"
    And I should see the link "Traduci"
