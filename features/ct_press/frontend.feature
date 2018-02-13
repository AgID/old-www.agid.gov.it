Feature: Access to press detail as anonymous

  As anonymous user
  I want to see detail page of press content type.

@api
  Scenario: Anonymous should see press detail page.
    Given I am an anonymous user
    And I am viewing an "press" content:
      | title | Press test 1 |
      | nid   | 111111 |
      | field_press_description  | A placeholder |
      | field_press_publish_date | 2020-01-05 |
    Then I should get a 200 HTTP response
    And print current URL

    And I should see "Press test 1" in the "h1 span" element
    And I should see "2020-01-05" in the "time" element
    And I should see the text "A placeholder"

    And I should see the text "Numero comunicato"
