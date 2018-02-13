@menu
Feature: Menu elements

  As a anonymous user
  I want to test site menu.

  @api
  Scenario: Test main menu links.
    Given I am an anonymous user
    And I am on the homepage
    Then I should see the link "L'Agenzia"
    And I should see the link "Piattaforme"
    And I should see the link "Infrastrutture"
    And I should see the link "Sicurezza"
    And I should see the link "Dati"

  @api
  Scenario: Test agenzia menu link.
    Given I am an anonymous user
    And I am on the homepage
    Then I should not visibly see the link "Organi"
    And I should not visibly see the link "Competenze e funzioni"
    And I follow "L'Agenzia"
    Then I should get a 200 HTTP response
    And I should see a "#block-navigazionesecondaria" element
    And I should see the link "Organi"
    And I should see the link "Competenze e funzioni"

  @api
  Scenario: Test Piattaforme menu link.
    Given I am an anonymous user
    And I am on the homepage
    Then I should not visibly see the link "SPID"
    And I should not visibly see the link "PagoPA"
    And I follow "Piattaforme"
    Then I should get a 200 HTTP response
    And I should see a "#block-navigazionesecondaria" element
    And I should see the link "SPID"
    And I should see the link "PagoPA"

  @api
  Scenario: Test Infrastrutture menu link.
    Given I am an anonymous user
    And I am on the homepage
    And I follow "Infrastrutture"
    Then I should get a 200 HTTP response
    And I should see a "#block-navigazionesecondaria" element

  @api
  Scenario: Test Sicurezza menu link.
    Given I am an anonymous user
    And I am on the homepage
    And I follow "Sicurezza"
    Then I should get a 200 HTTP response
    And I should see a "#block-navigazionesecondaria" element

  @api
  Scenario: Test Dati menu link.
    Given I am an anonymous user
    And I am on the homepage
    And I follow "Dati"
    Then I should get a 200 HTTP response
    And I should see a "#block-navigazionesecondaria" element

#  @api
#  Scenario: Test Norme e regole tecniche menu link.
#    Given I am an anonymous user
#    And I am on the homepage
#    And I follow "Norme e regole tecniche"
#    Then I should get a 200 HTTP response

#  @api
#  Scenario: Test per controllare che la sidebar delle pagine di secondo livello
#    non è mostrato nel caso in cui non ci siano pagine figlie
#    Given I am logged in as a user with the "administrator" role
#    And I am on "/it/agenzia/monitoraggio-contratti"
#    Then I should not see "Monitoraggio" in the "#block-navigazioneprincipale" element

  @api
  Scenario: Test per controllare che la sidebar delle pagine di secondo livello
  è mostrata nel caso in cui ci siano pagine figlie
    Given I am logged in as a user with the "administrator" role
    And I am on "/it/agenzia/organi"
    Then I should see "Organi" in the "#block-navigazioneprincipale" element
    And I should see "Comitato di indirizzo" in the "#block-navigazioneprincipale" element
