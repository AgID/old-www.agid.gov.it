Feature: Content type PagoPA backend

  As an administrator user
  I want to test PagoPA content type funcionalities.

  @api
  Scenario: Test PagoPA Content Type fields
    Given I am logged in as a administrator
    Then check if bundle "pagopa" of entity type "node" exists
    And check if bundle "pagopa" of entity type "node" has field "field_atm"
    And check if bundle "pagopa" of entity type "node" has field "field_active_mod3"
    And check if bundle "pagopa" of entity type "node" has field "field_active"
    And check if bundle "pagopa" of entity type "node" has field "field_active_mod12"
    And check if bundle "pagopa" of entity type "node" has field "field_key"
    And check if bundle "pagopa" of entity type "node" has field "field_abi_code"
    And check if bundle "pagopa" of entity type "node" has field "field_bic_code"
    And check if bundle "pagopa" of entity type "node" has field "field_fiscal_code"
    And check if bundle "pagopa" of entity type "node" has field "field_gs1gln_code"
    And check if bundle "pagopa" of entity type "node" has field "field_interbank_code"
    And check if bundle "pagopa" of entity type "node" has field "field_ipa_code"
    And check if bundle "pagopa" of entity type "node" has field "field_seller_bank_code"
    And check if bundle "pagopa" of entity type "node" has field "field_name"
    And check if bundle "pagopa" of entity type "node" has field "field_partner_mediator"
    And check if bundle "pagopa" of entity type "node" has field "field_mobile_banking"
    And check if bundle "pagopa" of entity type "node" has field "field_active_authority_number"
    And check if bundle "pagopa" of entity type "node" has field "field_online"
    And check if bundle "pagopa" of entity type "node" has field "field_phone_banking"
    And check if bundle "pagopa" of entity type "node" has field "field_help_desk"
    And check if bundle "pagopa" of entity type "node" has field "field_pagopa_type"
    And check if bundle "pagopa" of entity type "node" has field "field_istat_type"

  @api
  Scenario: Test if administrator can see "pagopa" content type admin page.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/types/manage/pagopa"
    Then I should get a 200 HTTP response

  @api
  Scenario: Test if administrator can see "pagopa" content type translation page.
    Given I am logged in as a user with the "administrator" role
    When I go to "/admin/structure/types/manage/pagopa/translate"
    Then I should get a 200 HTTP response

