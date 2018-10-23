Feature: Access to acc_subj detail as anonymous

  As anonymous user
  I want to see detail page of news content type.

@api
  Scenario: Anonymous should see acc_subj detail page.
    Given I am an anonymous user
    And I am viewing an "acc_subj" content:
      | title | Accreditated subject test 1 |
      | nid   | 111111 |
    Then I should get a 200 HTTP response
    And print current URL

    And I should see "Accreditated subject test 1" in the "h1 span" element
