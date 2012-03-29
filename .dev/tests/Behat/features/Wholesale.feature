# @resource product
Feature: Wholesale
    @javascript
    Background:
        Given I am logged in as admin
        And there is product
        Given I am on admin product page
        And I follow "Wholesale pricing"
    @javascript
    Scenario: Wholesale table create validation
        When I press "New tier"
        And I fill in "new[-1][quantityRangeBegin]" with "-3"
        And I fill in "new[-1][price]" with "-1"
        And I press "Save changes"
        Then I should see "Minimum value is 0"
        And I should see "Minimum value is 1"
    @javascript
    Scenario: Wholesale table create
        When I press "New tier"
        And I fill in "new[-1][quantityRangeBegin]" with "3"
        And I fill in "new[-1][price]" with "1"
        And I press "Save changes"
        Then I should see "1 entity has been created"
        And I should see "from 3"
        And I should see "$ 1.00"
    @javascript
    Scenario: Simple wholesale table
        When i create tiers:
            | range | price | membership |
            | 1     | 10    | All customers |
            | 3     | 8     | All customers |
            | 7     | 5     | All customers |
            | 10    | 3     | All customers |
        Then I should see price table:
            | range   | price | save     |
            | 1-3     | 10.00 |          |
            | 3-6     | 8.00  | 20% |
            | 7-9     | 5.00  | 50% |
            | 10 more | 3.00  | 70% |
    @javascript
    Scenario: Wholesale with minimum quantity
        When i create tiers:
            | range | price | membership |
            | 1     | 10    | All customers |
            | 3     | 8     | All customers |
            | 7     | 5     | All customers |
            | 10    | 3     | All customers |
        And I set minimum quantity to 4
        Then I should see price "8.00"
        And I should see minimum quantity 4
        And I should see price table:
            | range   | price | save     |
            | 4-6     | 8.00  |          |
            | 7-9     | 5.00  | 37% |
            | 10 more | 3.00  | 62% |
    @javascript
    Scenario: Wholesale with low stock
       When i create tiers:
           | range | price | membership |
           | 1     | 10    | All customers |
           | 3     | 8     | All customers |
           | 7     | 5     | All customers |
           | 10    | 3     | All customers |
       And I set minimum quantity to 11
       And I set product quantity to 10
       Then I should see price "8.00"
       And I should see minimum quantity 4
       And I should see "Out of stock"
       And I should not see price table

    @javascript
    Scenario: Wholesale with memberhsip
        When i create tiers:
            | range | price | membership |
            | range | price | membership |
            | range | price | membership |
            | range | price | membership |
        And I set minimum quantity to 1
        Then I should see price table:
            | range | price | membership |
            | range | price | membership |
            | range | price | membership |
            | range | price | membership |