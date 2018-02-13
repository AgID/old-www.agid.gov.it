Feature: Content type Press backend

  As an administrator user
  I want to test access to creation content type press.

@api
  Scenario: Test CM can access node creation
    Given I am logged in as a administrator
    When I visit "node/add/press"
    Then I should get a 200 HTTP response
    And I should see a "[name^=field_arguments]" element
    And I should see a "[name^=field_repository_file]" element
    And I should see a "[name^=field_repository_files]" element
    And I should see a "[name^=field_related_content]" element
    And I should see a "[name^=field_press_publish_date]" element
    And I should see a "[name^=field_press_description]" element
    And I should not see a "[name^=field_press_number]" element

@api
  Scenario: Test repository files field for press content type is limited to one item.
    Given I am logged in as a administrator
    When I visit "admin/structure/types/manage/press/fields/node.press.field_repository_file/storage"
    Then I should get a 200 HTTP response
    And the "cardinality_number" field should contain "1"

@api
  Scenario: Test repository files field for press content type is set to unlimited.
    Given I am logged in as a administrator
    When I visit "admin/structure/types/manage/press/fields/node.press.field_repository_files/storage"
    Then I should get a 200 HTTP response
      # unlimited
    And the "cardinality" field should contain "-1"
