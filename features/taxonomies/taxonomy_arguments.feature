Feature: Check the arguments taxonomy

  As a administrator or an anonymous user
  I want to test if "arguments" taxonomy vocabulary is properly configured.

  @api
  Scenario: Test if "arguments" taxonomy exists.
    Given I am an anonymous user
    Then check if bundle "arguments" of entity type "taxonomy_term" exists

  @api
  Scenario: Test if administrator can see "agid_arguments" taxonomy admin page.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy/manage/arguments/overview"
    Then I should get a 200 HTTP response
    When I go to "/admin/structure/taxonomy/manage/arguments/add"
    Then I should get a 200 HTTP response

  @api
  Scenario: Test if "arguments" taxonomy term works correctly.
    Given I am logged in as a user with the "administrator" role
    Given "arguments" terms:
      | name           | description          | format     | language |
      | AAAATest term one  | term one description | plain_text | en       |
    When I go to "/admin/structure/taxonomy/manage/arguments/overview"
    Then I should see the link "AAAATest term one"
    When I follow "AAAATest term one"
    Then I should get a 200 HTTP response
    And I should see the link "Modifica"
    And I should see the link "Traduci"

  @api
  @ClearRedirectsSetting
  Scenario: Test if "arguments" taxonomy redirect works.
    Given I am an anonymous user
    Given "arguments" terms:
      | name           | description          | format     | language |
      | AAAATest term one  | term one description | plain_text | en       |
    When I am on "/argomenti/aaaatest-term-one"
    Then I should get a 200 HTTP response
#    When I do not follow redirects
#    And I am on "/tags/aaaatest-term-one"
#    Then I am redirected to "/argomenti/aaaatest-term-one"
#    When I am on "/it/tags/aaaatest-term-one"
#    Then I am redirected to "/it/argomenti/aaaatest-term-one"
#    And I am on "/en/tags/aaaatest-term-one"
#    Then I am redirected to "/en/argomenti/aaaatest-term-one"
  