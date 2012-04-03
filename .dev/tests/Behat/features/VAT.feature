Feature: VAT taxes

    Background:
        Given I am on "admin.php?target=taxes&page=vatTax"

    Scenario Outline: Add tax rate
        Given I deleted all rates
        When I click "New rate"
        And I fill "rates[-1][value]" with "<rate>"
        Then I should see "<message>"
        And I should see product rate <rate>
        And I should see cart rate <rate>
        And I should see checkout rate <rate>
    Examples:
        | rate | message |



    Scenario: Disable tax
        When I press "Tax enabled"
        Then I should not see VAT on product page
        And I should not see VAT in cart
        And I should not see VAT on checkout

    Scenario: Tax with classes
        Given there are products with classes:
        |  name  | class | price |
        When I create rates:
        | class | rate |
        And I buy product ""
        And I buy product ""
        Then I should see rate "" on "" product page
        And I should see rate "" on "" product page
        And I should see vat "" in cart

    Scenario Outline: Tax for membership
        When I create rates:
        | membership   | rate   |
        | <membership> | <rate> |
        And I set user membership to "Gold"
        And I am logged in
        Then I <should_see> product rate <rate>
        And I <should_see> cart rate <rate>
        And I <should_see> checkout rate <rate>
    Examples:
        | rate | membership | should_see |

    Scenario Outline: Tax for zone
        When I create rates:
            | zone   | rate   |
            | <zone> | <rate> |
        And I set zone to <zone>
        And I am logged in
        Then I <should_see> product rate <rate>
        And I <should_see> cart rate <rate>
        And I <should_see> checkout rate <rate>
        Examples:
            | rate | zone | should_see |

    Scenario: Inc ex VAT
        Given I deleted all rates
        When I click "New rate"
        And I fill "rates[-1][value]" with "<rate>"
        And I set including as "<inc>"
        Then I should see <inc> label
        And I should see <price> price
        And I should see <vat> tax value

