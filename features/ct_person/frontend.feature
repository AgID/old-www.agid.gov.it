Feature: Content type Persona frontend

  As an anonymouse user
  I want to test Persona content type.

  @api
  Scenario: Anonymous should see person detail page.
    Given I am an anonymous user
    And I am viewing an "person" content:
      | title | Mario Bianchi |
      | nid   | 111111 |
      | field_last_name | Bianchi |
      | field_first_name  | Mario |
      | field_e_mail      | mario.bianchi@example.com |
      | field_pec         | mario.bianchi@pec.example.com |
      | field_phone_number | 06123456                     |
    Then I should get a 200 HTTP response
    And print current URL
    And I should see the text "Mario Bianchi"
    And I should see the text "mario.bianchi@example.com"
    And I should see the text "06123456"

