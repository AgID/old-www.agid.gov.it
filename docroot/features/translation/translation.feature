Feature: Basic check for translation.

  Check basic traslation settings.

  @api @translation
  Scenario: Anonymous should see login page in italian.
  Given I am an anonymous user
  When I go to "/user/login"
  Then I should get a 200 HTTP response
  And print current URL
  And I should see the text "Accedi"
  And I should see the text "Nome utente"
  And I should see the link "Reimposta la tua password"

  @api @translation
  Scenario: Administrator should be able to translate "Comunicato stampa" content type.
    Given I am logged in as a user with the "administrator" role
    When I go to "/it/admin/content?title=Il+pin+unico+raggiunge+quota+un+milione&type=press&status=All&langcode=All"
    Then I should get a 200 HTTP response
    And I follow "Il pin unico raggiunge quota un milione"
    Then I should get a 200 HTTP response
    And I follow "Traduci"
    Then I should get a 200 HTTP response
    And I should see "Inglese" in the "content" region
    And I should see the link "Aggiungi" in the "content" region
    And I follow "Aggiungi" in the "content" region
    Then I should get a 200 HTTP response
    And I should see "Create English translation of" in the "div.block-page-title-block" element

  @api @translation
  Scenario: Administrator should be able to translate "Domanda frequente" content type.
    Given I am logged in as a user with the "administrator" role
    When I go to "/it/admin/content?title=Come+gestire+un+eventuale+errore+in+una+registrazione+di+protocollo&type=faq&status=All&langcode=All"
    Then I should get a 200 HTTP response
    And I follow "Come gestire un eventuale errore in una registrazione di protocollo"
    Then I should get a 200 HTTP response
    And I follow "Traduci"
    Then I should get a 200 HTTP response
    And I should see "Inglese" in the "content" region
    And I should see the link "Aggiungi" in the "content" region
    And I follow "Aggiungi" in the "content" region
    Then I should get a 200 HTTP response
    And I should see "Create English translation of" in the "div.block-page-title-block" element

  @api @translation
  Scenario: Administrator should be able to translate "Evento" content type.
    Given I am logged in as a user with the "administrator" role
    When I go to "/it/admin/content?title=Digital+Design+Days&type=event&status=All&langcode=All"
    Then I should get a 200 HTTP response
    And I follow "Digital Design Days"
    Then I should get a 200 HTTP response
    And I follow "Traduci"
    Then I should get a 200 HTTP response
    And I should see "Inglese" in the "content" region
    And I should see the link "Aggiungi" in the "content" region
    And I follow "Aggiungi" in the "content" region
    Then I should get a 200 HTTP response
    And I should see "Create English translation of" in the "div.block-page-title-block" element

  @api @translation
  Scenario: Administrator should be able to translate "Notizia" content type.
    Given I am logged in as a user with the "administrator" role
    When I go to "/it/admin/content?title=Avviso+per+personale+in+comando+per+il+settore+Affari+giuridici&type=news&status=All&langcode=All"
    Then I should get a 200 HTTP response
    And I follow "Avviso per personale in comando per il settore Affari giuridici"
    Then I should get a 200 HTTP response
    And I follow "Traduci"
    Then I should get a 200 HTTP response
    And I should see "Inglese" in the "content" region
    And I should see the link "Aggiungi" in the "content" region
    And I follow "Aggiungi" in the "content" region
    Then I should get a 200 HTTP response
    And I should see "Create English translation of" in the "div.block-page-title-block" element

  @api @translation
  Scenario: Administrator should be able to translate "Pagina" content type.
    Given I am logged in as a user with the "administrator" role
    When I go to "/it/admin/content?title=Riduzione+produzione+fanghi+biologici+-+Pagina+Informativa&type=page&status=All&langcode=All"
    Then I should get a 200 HTTP response
    And I follow "Riduzione produzione fanghi biologici - Pagina Informativa"
    Then I should get a 200 HTTP response
    And I follow "Traduci"
    Then I should get a 200 HTTP response
    And I should see "Inglese" in the "content" region
    And I should see the link "Aggiungi" in the "content" region
    And I follow "Aggiungi" in the "content" region
    Then I should get a 200 HTTP response
    And I should see "Create English translation of" in the "div.block-page-title-block" element

#  @api @translation
#  Scenario: Administrator should be able to translate "Software riusabile" content type.
#    Given I am logged in as a user with the "administrator" role
#    When I go to "/it/admin/content?title=Portale+Amministrazione+Trasparente+-+AgID+e+modulo+whistleblowing&type=software&status=All&langcode=All"
#    Then I should get a 200 HTTP response
#    And I follow "Portale Amministrazione Trasparente - AgID e modulo whistleblowing"
#    Then I should get a 200 HTTP response
#    And I follow "Traduci"
#    Then I should get a 200 HTTP response
#    And I should see "Inglese" in the "content" region
#    And I should see the link "Aggiungi" in the "content" region
#    And I follow "Aggiungi" in the "content" region
#    Then I should get a 200 HTTP response
#    And I should see "Create English translation of" in the "div.block-page-title-block" element

  @api @translation
  Scenario: Administrator should be able to translate "Soggetto accreditato" content type.
    Given I am logged in as a user with the "administrator" role
    When I go to "/it/admin/content?title=Regione+Basilicata&type=acc_subj&status=All&langcode=All"
    Then I should get a 200 HTTP response
    And I follow "Regione Basilicata"
    Then I should get a 200 HTTP response
    And I follow "Traduci"
    Then I should get a 200 HTTP response
    And I should see "Inglese" in the "content" region
    And I should see the link "Aggiungi" in the "content" region
    And I follow "Aggiungi" in the "content" region
    Then I should get a 200 HTTP response
    And I should see "Create English translation of" in the "div.block-page-title-block" element
