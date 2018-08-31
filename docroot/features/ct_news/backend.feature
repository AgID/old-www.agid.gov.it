Feature: Content type News backend

  As an administrator user
  I want to test access to creation content type news.

@api
  Scenario: Test administrator can access node creation
    Given I am logged in as a administrator
    When I visit "node/add/news"
    Then I should get a 200 HTTP response
    And I should see a "#edit-field-news-type-wrapper" element
    And I should see a "#edit-field-news-date-wrapper" element
    And I should see a "#edit-body-wrapper" element
    And I should see a "#edit-field-image-wrapper" element
    And I should see a "#edit-field-related-content-wrapper" element
    And I should see a "#edit-field-repository-files-wrapper" element
    And I should see a "#edit-field-arguments-wrapper" element
    And I should see a "#edit-field-profiles-wrapper" element
    And I should see a "#edit-field-offices-wrapper" element
    And I should see a "#edit-field-iconfont-wrapper" element
    And I should see a "#edit-field-news-link-wrapper" element
    And I should see a "#edit-field-news-abstract-wrapper" element
