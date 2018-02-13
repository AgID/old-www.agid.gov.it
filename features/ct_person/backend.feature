Feature: Content type Persona backend

  As an administrator user
  I want to test Persona content type funcionalities.

  @api
  Scenario: Test Persona Content Type fields
    Given I am logged in as a administrator
    Then check if bundle "person" of entity type "node" exists
    And check if bundle "person" of entity type "node" has field "field_last_name"
    And check if bundle "person" of entity type "node" has field "field_e_mail"
    And check if bundle "person" of entity type "node" has field "field_photo"
    And check if bundle "person" of entity type "node" has field "field_first_name"
    And check if bundle "person" of entity type "node" has field "field_pec"
    And check if bundle "person" of entity type "node" has field "field_position"
    And check if bundle "person" of entity type "node" has field "field_phone_number"
    And check if bundle "person" of entity type "node" has field "field_cv"

  @api
  Scenario: Test if administrator can see "person" content type admin page.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/types/manage/person"
    Then I should get a 200 HTTP response

