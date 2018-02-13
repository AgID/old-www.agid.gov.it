Feature: Content type Event backend

  As an administrator user
  I want to test access to creation content type event.

  @api
  Scenario: Test administrator can access node creation
    Given I am logged in as a administrator
    When I visit "node/add/event"
    Then I should get a 200 HTTP response
    And I should see a "#edit-field-event-location-wrapper" element
    And I should not see a "#edit-field-event-geolocation-wrapper" element
    And I should see a "#edit-field-event-start-date-wrapper" element
    And I should see a "#edit-field-event-end-date-wrapper" element
    And I should see a "#edit-field-event-description-wrapper" element
    And I should see a "#edit-field-image-wrapper" element
    And I should see a "#edit-field-related-content-wrapper" element
    And I should see a "#edit-field-repository-files-wrapper" element
    And I should see a "#edit-field-arguments-wrapper" element
    And I should see a "#edit-field-profiles-wrapper" element
    And I should see a "#edit-field-offices-wrapper" element
