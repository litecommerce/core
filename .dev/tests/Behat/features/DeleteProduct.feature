# @resource product
# @resource order
Feature: Delete products
    @javascript
    Background:
        And I am logged in as admin

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
        And I should see products in top sellers

    @javascript
    Scenario: Delete second
        When I delete second
        Then I should see valid order info
        And I should see products in top sellers

    @javascript
    Scenario: Declined order
        Given I am on order page
        When I change status to "Declined"
        Then I should see valid order info
        And I should not see products in top sellers

