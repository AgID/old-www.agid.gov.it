Feature: Content type acc_subj backend

  As an administrator user
  I want to test access to creation content type acc_subj.

@api
  Scenario: Test admin can access node creation and there are all fields
    Given I am logged in as a administrator
    When I visit "node/add/acc_subj"
    Then I should get a 200 HTTP response
    And I should see an "[name^=field_acc_subj_type]" element
    And I should see an "[name^=field_acc_subj_fc]" element
    And I should see an "[name^=field_acc_subj_vat]" element
    And I should see an "[name^=field_acc_subj_logo]" element
    And I should see an "[name^=field_acc_subj_service_logo]" element
    And I should see an "[name^=field_acc_subj_registered_office]" element
    And I should see an "[name^=field_acc_subj_attorney]" element
    And I should see an "[name^=field_acc_subj_website]" element
    And I should see an "[name^=field_acc_subj_pec]" element
    And I should see an "[name^=field_acc_subj_phone]" element
    And I should see an "[name^=field_acc_subj_state]" element
    And I should see an "[name^=field_acc_subj_acc_date]" element
    And I should see an "[name^=field_acc_subj_end_date]" element
    And I should see an "[name^=field_acc_subj_last_update_date]" element
    And I should see an "[name^=field_acc_subj_service_name]" element
    And I should see an "[name^=field_repository_files]" element
    And I should see an "[name^=field_acc_subj_external_link]" element
    And I should see an "[name^=field_acc_subj_info]" element
    And I should see an "[name^=field_arguments]" element
    And I should see an "[name^=field_offices]" element

@api
  Scenario: Test "field_acc_subj_type" field for acc_subj content type is set to unlimited.
    Given I am logged in as a administrator
    When I visit "admin/structure/types/manage/acc_subj/fields/node.acc_subj.field_acc_subj_type/storage"
    Then I should get a 200 HTTP response
    # Limited to 1
    And the "cardinality_number" field should contain "1"

@api
  Scenario: Test the "field_arguments" field for acc_subj content type is set to unlimited.
    Given I am logged in as a administrator
    When I visit "admin/structure/types/manage/acc_subj/fields/node.acc_subj.field_arguments/storage"
    Then I should get a 200 HTTP response
    # unlimited
    And the "cardinality" field should contain "-1"

@api
  Scenario: Test repository files field for acc_subj content type is set to unlimited.
    Given I am logged in as a administrator
    When I visit "admin/structure/types/manage/acc_subj/fields/node.acc_subj.field_repository_files/storage"
    Then I should get a 200 HTTP response
    # unlimited
    And the "cardinality" field should contain "-1"

@api
  Scenario: Test the "field_acc_subj_info" field for acc_subj content type is set to unlimited.
    Given I am logged in as a administrator
    When I visit "admin/structure/types/manage/acc_subj/fields/node.acc_subj.field_acc_subj_info/storage"
    Then I should get a 200 HTTP response
    # unlimited
    And the "cardinality" field should contain "-1"

@api
  Scenario: Test the "field_acc_subj_external_link" field for acc_subj content type is set to unlimited.
    Given I am logged in as a administrator
    When I visit "admin/structure/types/manage/acc_subj/fields/node.acc_subj.field_acc_subj_external_link/storage"
    Then I should get a 200 HTTP response
    # unlimited
    And the "cardinality" field should contain "-1"
