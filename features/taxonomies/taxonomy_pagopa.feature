Feature: Check the pagopa_type taxonomy

  As a administrator or an anonymous user
  I want to test if "pagopa_type" taxonomy vocabulary is properly configured and populated.

  @api
  Scenario: Test if "pagopa_type" taxonomy exists.
    Given I am an anonymous user
    Then check if bundle "pagopa_type" of entity type "taxonomy_term" exists

  @api
  Scenario: Test if administrator can see "pagopa_type" taxonomy admin page.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy/manage/pagopa_type/overview"
    Then I should get a 200 HTTP response
    When I go to "/admin/structure/taxonomy/manage/pagopa_type/add"
    Then I should get a 200 HTTP response

  @api
  Scenario: Test if needed taxonomy terms exist.
    Given I am an anonymous user
    Then check if taxonomy term with name "Enti creditori" exists in taxonomy vocabulary with vid "pagopa_type"
    And check if taxonomy term with name "Intermediari" exists in taxonomy vocabulary with vid "pagopa_type"
    And check if taxonomy term with name "PSP" exists in taxonomy vocabulary with vid "pagopa_type"

  @api
  Scenario: Test if "pagopa_type" taxonomy term works correctly.
    Given I am logged in as a user with the "administrator" role
    Given "pagopa_type" terms:
      | name           | description          | format     | language |
      | Test term one  | term one description | plain_text | en       |
    When I go to "/admin/structure/taxonomy/manage/pagopa_type/overview"
    Then I should see the link "Test term one"
    When I follow "Test term one"
    Then I should get a 200 HTTP response
    And I should see the link "Modifica"
    And I should see the link "Traduci"


