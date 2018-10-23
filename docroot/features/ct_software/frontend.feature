Feature: Access to software detail as anonymous

  As anonymous user
  I want to see detail page of software content type.

  @api
  Scenario: Anonymous should see software detail page.
    Given I am an anonymous user
    And I am viewing an "software" content:
      | title                     | Software test 1                           |
      | nid                       | 111111                                       |
      | field_software_government | The quick brown fox jumps over a lazy dog |
      | field_software_year       | 2017                                      |
    Then I should get a 200 HTTP response
    And print current URL

    And I should see "software test 1" in the "h1 span" element
    And I should see the text "The quick brown fox jumps over a lazy dog"
    And I should see the text "2017"
    And I should see the text "Numero identificativo"
