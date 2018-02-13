Feature: Access to press detail as anonymous

  As anonymous user
  I want to see detail page of faq content type.

@api
  Scenario: Anonymous should see faq detail page.
    Given I am an anonymous user
    And I am viewing an "faq" content:
      | title | What a question? |
      | nid   | 111111 |
      | field_faq_question_full | Lorem ipsum dolor sit amet |
      | field_faq_answer | Donec quam felis ultricies nec pellentesque eu |
    Then I should get a 200 HTTP response
    And print current URL
    And I should see "What a question?" in the "h1.u-text-h2" element
    And I should see the text "Lorem ipsum dolor sit amet"
    And I should see the text "Donec quam felis ultricies nec pellentesque eu"
