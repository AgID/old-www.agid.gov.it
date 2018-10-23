Feature: Content type FAQ backend

  As an administrator user
  I want to test access to creation content type faq.

@api
  Scenario: Test CM can access node creation
    Given I am logged in as a administrator
    When I visit "node/add/faq"
    Then I should get a 200 HTTP response
    # Check if we are using ckeditor and maxlenght js seeing if relative JS files are loaded.
    And the response should contain "core/assets/vendor/ckeditor/ckeditor.js"
    And the response should contain "modules/contrib/maxlength/js/maxlength.js"
    # Check presence of ct fields.
    And I should see an "[name^=field_arguments]" element
    And I should see an "[name^=field_faq_question_full]" element
    And I should see an "[name^=field_faq_answer]" element
    And I should see an "[name^=field_profiles]" element

#@api
#  Scenario: Test existance of an arguments term in a specific faq
#    Given I am logged in as a administrator
#    When I visit "/admin/content?title=Come+declinare+il+concetto+di+pubblica+amministrazione%3F&type=faq&status=All&langcode=All"
#    Then I should get a 200 HTTP response
#    And I follow "Come declinare il concetto di pubblica amministrazione?"
#    Then I should get a 200 HTTP response
#    And print current URL
#    And I should see the text "pagamenti elettronici"
#    And I follow "pagamenti elettronici"
#    Then I should get a 200 HTTP response
#    And print current URL
#    And I should see "pagamenti elettronici" in the "h1.u-text-h2" element
