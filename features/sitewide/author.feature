@author-role @permissions
Feature: Author Role Permissions

  As an user with the author role
  I want to test that I have the needed permissions.

  We test for permissions on the following content types:
    - Page
    - News
    - Press
    - Event
    - PagoPA
    - Person
    - Software
    - Accredited Subject

  Background:
    Given users:
      | name            | mail            | pass                              | status |  uid  | roles   |
      | testauthor      | tt@example.com  | 0f0375aae096ef0424f247132cb95ac6  | 1      |  1000 | author  |
      | testauthor2     | tt2@example.com | dd38626a8fdd8ce42f4c488195fc1a46  | 1      |  1001 | author  |




  # Tests on the Page Content Type

  @api @ct-page
  Scenario: Test that users with author role can create a new Page CT.
    Given I am logged in as a user with the "author" role
    When I go to "/node/add/page"
    Then I should get a 403 HTTP response

  @api @ct-page
  Scenario: Test that users with author role can edit and translate its own Page CT, but not delete them.
    Given I am logged in as "testauthor"
    And I am viewing an "page" content:
      | title               | This is a test                   |
      | nid                 | 111111                           |
      | uid                 | 1000                             |
      | status              | 1                                |
      | field_page_abstract | Lorem ipsum dolor sit amet       |
      | field_page_content  | Aenean vulputate eleifend tellus |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response

  @api @ct-page
  Scenario: Test that users with author role cannot edit or delete other users Page CT, but can translate them.
    Given I am logged in as "testauthor"
    And I am viewing an "page" content:
      | title               | This is a test                   |
      | nid                 | 111111                           |
      | uid                 | 1001                             |
      | status              | 1                                |
      | field_page_abstract | Lorem ipsum dolor sit amet       |
      | field_page_content  | Aenean vulputate eleifend tellus |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response




  # Tests on the News Content Type

  @api @ct-news
  Scenario: Test that users with author role can create a new News CT.
    Given I am logged in as a user with the "author" role
    When I go to "/node/add/news"
    Then I should get a 200 HTTP response

  @api @ct-news
  Scenario: Test that users with author role can edit its own News CT.
    Given I am logged in as "testauthor"
    And I am viewing an "news" content:
      | title               | This is a test                    |
      | nid                 | 111111                            |
      | uid                 | 1000                              |
      | status              | 1                                 |
      | field_news_abstract | Lorem ipsum dolor sit amet        |
      | field_body          | Aenean vulputate eleifend tellus  |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response

  @api @ct-news
  Scenario: Test that users with author role cannot edit other users News CT.
    Given I am logged in as "testauthor"
    And I am viewing an "news" content:
      | title               | This is a test                    |
      | nid                 | 111111                            |
      | uid                 | 1001                              |
      | status              | 1                                 |
      | field_news_abstract | Lorem ipsum dolor sit amet        |
      | field_body          | Aenean vulputate eleifend tellus  |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response




  # Tests on the Press Content Type

  @api @ct-press
  Scenario: Test that users with author role can create a new Press CT.
    Given I am logged in as a user with the "author" role
    When I go to "/node/add/press"
    Then I should get a 200 HTTP response

  @api @ct-press
  Scenario: Test that users with author role can edit its own Press CT.
    Given I am logged in as "testauthor"
    And I am viewing an "press" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1000                             |
      | status                  | 1                                |
      | field_press_description | Lorem ipsum dolor sit amet       |
      | field_body              | Aenean vulputate eleifend tellus |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response

  @api @ct-press
  Scenario: Test that users with author role cannot edit other users Press CT.
    Given I am logged in as "testauthor"
    And I am viewing an "press" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1001                             |
      | status                  | 1                                |
      | field_press_description | Lorem ipsum dolor sit amet       |
      | field_body              | Aenean vulputate eleifend tellus |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response




  # Tests on the Event Content Type

  @api @ct-event
  Scenario: Test that users with author role can create a new Event CT.
    Given I am logged in as a user with the "author" role
    When I go to "/node/add/event"
    Then I should get a 200 HTTP response

  @api @ct-event
  Scenario: Test that users with author role can edit its own Event CT.
    Given I am logged in as "testauthor"
    And I am viewing an "event" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1000                             |
      | status                  | 1                                |
      | field_event_description | Lorem ipsum dolor sit amet       |
      | field_body              | Aenean vulputate eleifend tellus |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response

  @api @ct-event
  Scenario: Test that users with author role cannot edit other users Event CT.
    Given I am logged in as "testauthor"
    And I am viewing an "event" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1001                             |
      | status                  | 1                                |
      | field_event_description | Lorem ipsum dolor sit amet       |
      | field_body              | Aenean vulputate eleifend tellus |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response




  # Tests on the PagoPA Content Type

  @api @ct-pagopa
  Scenario: Test that users with author role can create a new PagoPA CT.
    Given I am logged in as a user with the "author" role
    When I go to "/node/add/pagopa"
    Then I should get a 403 HTTP response

  @api @ct-pagopa
  Scenario: Test that users with author role can edit its own PagoPA CT.
    Given I am logged in as "testauthor"
    And I am viewing an "pagopa" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1000                             |
      | status                  | 1                                |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response

  @api @ct-pagopa
  Scenario: Test that users with author role cannot edit other users PagoPA CT.
    Given I am logged in as "testauthor"
    And I am viewing an "pagopa" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1001                             |
      | status                  | 1                                |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response




  # Tests on the Person Content Type

  @api @ct-person
  Scenario: Test that users with author role can create a new Person CT.
    Given I am logged in as a user with the "author" role
    When I go to "/node/add/person"
    Then I should get a 403 HTTP response

  @api @ct-person
  Scenario: Test that users with author role can edit its own Person CT.
    Given I am logged in as "testauthor"
    And I am viewing an "person" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1000                             |
      | status                  | 1                                |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response

  @api @ct-person
  Scenario: Test that users with author role cannot edit other users Person CT.
    Given I am logged in as "testauthor"
    And I am viewing an "person" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1001                             |
      | status                  | 1                                |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response




  # Tests on the Software Content Type

  @api @ct-software
  Scenario: Test that users with author role can create a new Software CT.
    Given I am logged in as a user with the "author" role
    When I go to "/node/add/software"
    Then I should get a 200 HTTP response

  @api @ct-software
  Scenario: Test that users with author role can edit its own Software CT.
    Given I am logged in as "testauthor"
    And I am viewing an "software" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1000                             |
      | status                  | 1                                |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response

  @api @ct-software
  Scenario: Test that users with author role cannot edit other users Software CT.
    Given I am logged in as "testauthor"
    And I am viewing an "software" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1001                             |
      | status                  | 1                                |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response




  # Tests on the Accredited Subject Content Type

  @api @ct-acc_subj
  Scenario: Test that users with author role can create a new Accredited Subject CT.
    Given I am logged in as a user with the "author" role
    When I go to "/node/add/acc_subj"
    Then I should get a 200 HTTP response

  @api @ct-acc_subj
  Scenario: Test that users with author role can edit its own Accredited Subject CT.
    Given I am logged in as "testauthor"
    And I am viewing an "acc_subj" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1000                             |
      | status                  | 1                                |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response

  @api @ct-acc_subj
  Scenario: Test that users with author role cannot edit other users Accredited Subject CT.
    Given I am logged in as "testauthor"
    And I am viewing an "acc_subj" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1001                             |
      | status                  | 1                                |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response




  # Tests on the Frequently Asked Question Content Type

  @api @ct-faq
  Scenario: Test that users with author role can create a new Frequently Asked Question CT.
    Given I am logged in as a user with the "author" role
    When I go to "/node/add/faq"
    Then I should get a 200 HTTP response

  @api @ct-faq
  Scenario: Test that users with author role can edit its own Frequently Asked Question CT.
    Given I am logged in as "testauthor"
    And I am viewing an "faq" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1000                             |
      | status                  | 1                                |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response

  @api @ct-faq
  Scenario: Test that users with author role cannot edit other users Frequently Asked Question CT.
    Given I am logged in as "testauthor"
    And I am viewing an "faq" content:
      | title                   | This is a test                   |
      | nid                     | 111111                           |
      | uid                     | 1001                             |
      | status                  | 1                                |
    Then I should get a 200 HTTP response
    And I go to "/node/111111/edit"
    Then I should get a 403 HTTP response
    And I go to "/node/111111/translations/add/it/en"
    Then I should get a 200 HTTP response
    And I go to "/node/111111/delete"
    Then I should get a 403 HTTP response
