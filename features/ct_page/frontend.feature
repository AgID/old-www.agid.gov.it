Feature: Access to page detail as anonymous

  As anonymous user
  I want to see detail page of page content type.

@api
  Scenario: Anonymous should see event detail page.
    Given I am an anonymous user
    And I am viewing an "page" content:
      | title | What a nice page? |
      | nid   | 111111 |
      | field_page_abstract | Lorem ipsum dolor sit amet |
      | field_page_content  | Aenean vulputate eleifend tellus |
    Then I should get a 200 HTTP response
    And print current URL
    And I should see "What a nice page?" in the "h1.u-text-h2" element
    And I should see the text "Lorem ipsum dolor sit amet"
    And I should see the text "Aenean vulputate eleifend tellus"
