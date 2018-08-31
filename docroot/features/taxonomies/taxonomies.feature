Feature: Taxonomies elements

  As a administrator
  I want to test if there are taxonomies elements.

  @api
  Scenario: Test if CAD taxonomy exists.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy"
    Then I should get a 200 HTTP response
    And I should see the text "Codice di Amministrazione digitale"
    When I go to "/admin/structure/taxonomy/manage/cad/overview"
    Then I should get a 200 HTTP response
    And I should see the text "Art. 1 - Definizioni"

  @api
  Scenario: Test if Argomenti taxonomy exists.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy"
    Then I should get a 200 HTTP response
    And I should see the text "Argomenti"
    When I go to "/admin/structure/taxonomy/manage/arguments/overview"
    Then I should get a 200 HTTP response
    And I should see the text "accessibilità"

  @api
  Scenario: Test if Tipologia ente accreditato taxonomy exists.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy"
    Then I should get a 200 HTTP response
    And I should see the text "Tipologia ente accreditato"
    When I go to "/admin/structure/taxonomy/manage/acc_subj_type/overview"
    Then I should get a 200 HTTP response
    And I should see the text "Accessibilità"
    And I should see the text "SPID"

  @api
  Scenario: Test if Uffici taxonomy exists.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy"
    Then I should get a 200 HTTP response
    And I should see the text "Uffici"
    When I go to "/admin/structure/taxonomy/manage/agid_offices/overview"
    Then I should get a 200 HTTP response
    And I should see the text "Name"

  @api
  Scenario: Test if "Tipologia Notizia" taxonomy exists.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy"
    Then I should get a 200 HTTP response
    And I should see the text "Tipologia Notizia"
    When I go to "/admin/structure/taxonomy/manage/news_type/overview"
    Then I should get a 200 HTTP response
    And I should see the text "Pubblicazioni"

  @api
  Scenario: Test if "Tipologia File" taxonomy exists.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy"
    Then I should get a 200 HTTP response
    And I should see the text "Tipologia File"
    When I go to "/admin/structure/taxonomy/manage/file_type/overview"
    Then I should get a 200 HTTP response
    And I should see the text "Circolari e deliberazioni"

  @api
  Scenario: Test if "Contenuto di origine del file" taxonomy exists.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/taxonomy"
    Then I should get a 200 HTTP response
    And I should see the text "Contenuto di origine del file"
    When I go to "/admin/structure/taxonomy/manage/original_file_source/overview"
    Then I should get a 200 HTTP response
    And I should see the text "Pagina di II livello"
