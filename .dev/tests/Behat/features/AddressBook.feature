# @resource user
# @resource admin
Feature: Admin address book
    @javascript
    Background:
        Given I am logged in as admin
        And I am on "admin.php?target=address_book"



    @javascript
    Scenario: Look at address book
        Given I am on "admin.php?target=address_book"
        And I deleted all addresses
        Then I should see "Address book"

    @javascript
    Scenario: Add address
        And I press "Add new address"
        And I should see "Address details"
        When I fill in the following:
            | _firstname | firstname-test |
            | _lastname  | lastname-test  |
            | _street    | address-test   |
            | _city      | city-test      |
            | _zipcode   | 111111         |
            | _phone     | 111111         |
        And I select "Alabama" from "_state_id"
        And I press "Save changes"
        Then There is "test" address

    @javascript
    Scenario: Edit address
        Given There is "test" address
        And I press "Change"
        And I should see "Address details"
        When I fill address with following:
             | <id>_firstname | firstname-test2 |
             | <id>_lastname  | lastname-test2  |
             | <id>_street    | address-test2   |
             | <id>_city      | city-test2      |
        And I press "Save changes"
        Then There is "test2" address

    @javascript
    Scenario: Delete address
        Given There is "test" address
        Given I should see an ".delete-address" element
        When I click ".delete-address"
        And I pass confirmation
        Then I should see "Address has been deleted"
        And I reload the page
        And I should not see "-test"
