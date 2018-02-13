Feature: Content type "software" backend

  As an administrator user
  I want to test access to creation content type software.

@api
  Scenario: Test admin can access node creation
    Given I am logged in as a administrator
    When I visit "node/add/software"
    Then I should get a 200 HTTP response
    And I should see a "[name^=field_software_government]" element
    And I should see a "[name^=field_software_year]" element
    And I should see a "[name^=field_software_type]" element
    And I should see a "[name^=field_repository_file]" element
    And I should not see a "[name^=field_software_number]" element

@api
  Scenario: Test repository files field for software content type is limited to one item.
    Given I am logged in as a administrator
    When I visit "admin/structure/types/manage/software/fields/node.software.field_repository_file/storage"
    Then I should get a 200 HTTP response
    And the "cardinality_number" field should contain "1"

@api
  Scenario: Test as admin we have some migrated content
    Given I am logged in as a administrator
    When I visit "/admin/content?title=&type=software&status=All&langcode=All"
    Then I should get a 200 HTTP response
    # After migration we should have at least 282 elements.
    And I should see a ".views-table tbody tr:nth-last-child(n+50)" element
    And I should see a "nav.pager" element

@api
  Scenario: Test existance of a specific node of type software
    Given I am logged in as a administrator
    When I visit "/admin/content?title=CRPNet+e-democracy&type=software&status=All&langcode=All"
    Then I should get a 200 HTTP response
    And I follow "CRPNet e-democracy"
    Then I should get a 200 HTTP response
    And print current URL
    And I should see "CRPNet e-democracy" in the "h1.u-text-h2" element
    And I should see the text "Regione Piemonte"
    And I should see the text "2012"
    And I should see the text "Gestione flussi documentali"
    And I should see the text "33"
