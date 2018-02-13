Feature: Access to news detail as anonymous

  As anonymous user
  I want to see detail page of news content type.

@api
  Scenario: Anonymous should see news detail page.
    Given I am an anonymous user
    And I am viewing an "news" content:
      | title | What a news? |
      | nid   | 111111 |
      | body | Lorem ipsum dolor sit amet |
    Then I should get a 200 HTTP response
    And print current URL
    And I should see "What a news?" in the "h1.u-text-h2" element
    And I should see the text "Lorem ipsum dolor sit amet"
