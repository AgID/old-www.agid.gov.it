Feature: Access to event detail as anonymous

  As anonymous user
  I want to see detail page of event content type.

@api
  Scenario: Anonymous should see event detail page.
    Given I am an anonymous user
    And I am viewing an "event" content:
      | title | What an event? |
      | nid   | 111111 |
      | field_event_description | Lorem ipsum dolor sit amet |
    Then I should get a 200 HTTP response
    And print current URL
    And I should see "What an event?" in the "h1.u-text-h2" element
    And I should see the text "Lorem ipsum dolor sit amet"
