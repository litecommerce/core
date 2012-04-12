Feature: VAT taxes

    Background:
        Given I am on "admin.php?target=taxes&page=vatTax"

    Scenario Outline: Add tax rate
        Given I deleted all rates
        When I click "New rate"
        And I fill in "rates[-1][value]" with "<rate>"
        Then I should see "<message>"
        And I should see VAT "<rate>" on product page
        And I should see VAT "<rate>" in cart
        And I should see VAT "<rate>" on checkout
    Examples:
        | rate | message |
        | -1   | error   |

    Scenario: Disable tax
        Given VAT is enabled
        When I press "Tax enabled"
        Then I should not see VAT "" on product page
        And I should not see VAT "" in cart
        And I should not see VAT "" on checkout

    Scenario: Tax with classes
        Given there are products with classes:
        |  name  | class | price |
        When I create rates:
            | class | rate |
        And I buy products
        Then I should see VAT "" on "" product page
        And I should see VAT "" on "" product page
        And I should see VAT "" in cart

    Scenario Outline: Tax for membership
        When I create rates:
            | membership   | rate   |
            | <membership> | <rate> |
        And I set user membership to <membership>
        And I am logged in
        Then I <should_see> VAT "<rate>" on product page
        And I <should_see> VAT "<rate>" in cart
        And I <should_see> VAT "<rate>" on checkout
    Examples:
        | rate | membership | should_see |
        | 12   | "Gold"     | should see |

    Scenario Outline: Tax for zone
        When I create rates:
            | zone   | rate   |
            | <zone> | <rate> |
        And I set zone to <zone>
        And I am logged in
        Then I <should_see> VAT "<rate>" on product page
        And I <should_see> VAT "<rate>" in cart
        And I <should_see> VAT "<rate>" on checkout
        Examples:
            | rate | zone      | should_see |
            | 12   | "Default" | should see |

    Scenario Outline: Inc ex VAT
        Given I deleted all rates
        When I click "New rate"
        And I fill in "rates[-1][value]" with "<rate>"
        And I set including as "<inc>"
        Then I should see VAT "<rate>" on product page
        And I should see "<inc>" label
        And I should see "<price>" price
    Examples:
        | inc | price | rate |
        | inc | 12    | 12   |
        | exc | 12    | 12   |
