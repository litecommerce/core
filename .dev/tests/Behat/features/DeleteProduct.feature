# @resource product
# @resource order
Feature: Delete products
    @javascript
    Scenario: Add to cart
        Given there are 2 products with enabled inventory
        When I buy products
        And I delete first
        Then I should see valid order info

    @javascript
    Scenario: Completed order
        Given I am on order page
        When I change status to "Completed"
        Then I should see valid order info
        And I should see valid top sellers
        And I should see valis statistics

    @javascript
    Scenario: Delete second
        When I delete second
        Then I should see valid order info
        And I should see valid top sellers
        And I should see valis statistics

    @javascript
    Scenario: Declined order
        Given I am on order page
        When I change status to "Declined"
        Then I should see valid order info
        And I should see valid top sellers
        And I should see valis statistics

