Feature: Content type page backend

  As an administrator user
  I want to test access to creation content type page.


  @api
  Scenario: Test administrator can access node creation
    Given I am logged in as a administrator
    When I visit "node/add/page"
    Then I should get a 200 HTTP response
    And I should see an "[name^=field_page_abstract]" element
    And I should see an "[name^=field_arguments]" element
    And I should see an "[name^=field_cad]" element
    And I should see an "[name^=field_related_content]" element
    And I should see an "[name^=field_repository_files]" element
    And I should see an "[name^=field_image]" element
    And I should see an "[name^=field_profiles]" element
    And I should see an "[name^=field_page_content]" element
    And I should see an "[name^=field_page_source]" element
    And I should see an "[name^=field_offices]" element


  @api
  Scenario: Test existence of some migrated content
    Given I am logged in as a administrator
    When I visit "admin/content?title=Struttura+Organigramma&type=page&status=All&langcode=All"
    Then I should get a 200 HTTP response
    And I follow "Struttura Organigramma"
    Then I should get a 200 HTTP response
    And I should see "Struttura Organigramma" in the "h1.u-text-h2" element
    And I should see the text "Profili"
    And I should see the link "PA"

  @api
  Scenario: Test existence of some migrated content for 2nd level pages.
    Given I am logged in as a administrator
    When I visit "admin/content?title=Sistema+pubblico+di+connettività&type=page&status=All&langcode=All"
    Then I should get a 200 HTTP response
    And I follow "Sistema pubblico di connettività"
    Then I should get a 200 HTTP response
    And I should see "Sistema pubblico di connettività" in the "h1.u-text-h2" element
    And I should see the link "PA"

#  @api
#  @javascript
#  Scenario: Test administrator has IMCE buttons on edit
#    Given I am logged in as a administrator
#    When I visit "node/add/page"
#    Then I wait for AJAX to finish
#    And I should see an ".cke_button__imcelink" element
#    And I should see an ".cke_button__linkit" element

