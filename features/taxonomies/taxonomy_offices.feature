Feature: Check the agid_offices taxonomy

  As a administrator or an anonymous user
  I want to test if "agid_offices" taxonomy vocabulary is properly configured.

  @api
  Scenario: Test if "agid_offices" taxonomy exists.
    Given I am an anonymous user
    Then check if bundle "agid_offices" of entity type "taxonomy_term" exists
    And check if bundle "agid_offices" of entity type "taxonomy_term" has field "field_e_mail"

  @api
  Scenario: Test if administrator can see "agid_offices" taxonomy admin page.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy/manage/agid_offices/overview"
    Then I should get a 200 HTTP response
    When I go to "/admin/structure/taxonomy/manage/agid_offices/add"
    Then I should get a 200 HTTP response

  @api
  Scenario: Test if "agid_offices" taxonomy term works correctly.
    Given I am logged in as a user with the "administrator" role
    Given "agid_offices" terms:
      | name           | description          | format     | language |
      | Test term one  | term one description | plain_text | en       |
    When I go to "/admin/structure/taxonomy/manage/agid_offices/overview"
    Then I should see the link "Test term one"
    When I follow "Test term one"
    Then I should get a 200 HTTP response
    And I should see the link "Modifica"
    And I should see the link "Traduci"
