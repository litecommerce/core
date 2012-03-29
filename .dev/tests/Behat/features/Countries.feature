# @resource countries
Feature: Admin countries manage
    @javascript
    Background:
        Given I am logged in as admin
        And I am on "admin.php?target=countries"
        And I should see "Countries"
        And I should see "TH" country
        And I check "TH"


    @javascript
    Scenario: Add country
        Given I should see "Add new country"
        When I fill in "code" with "ZZ"
        And I fill in "country" with "Test country"
        And I press "Add new"
        Then I should see "ZZ" country
    @javascript
    Scenario: Update country
        Given I should see "ZZ" country
        When I fill in "countries[ZZ][country]" with "Test country2"
        And I press "Update"
        And the "countries[ZZ][country]" field should contain "Test country2"
    @javascript
    Scenario: Delete country
        Given I should see "ZZ" country
        When I check "ZZ"
        And I press "Delete selected"
        Then I should not see "ZZ" country

    