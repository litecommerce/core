# @resource coupons
Feature: Discount coupons admin page
    @javascript
    Background:
         And I am logged in as admin
         And I am on "admin.php?target=promotions&page=coupons"
         And I should see "Coupons"

    @javascript
    Scenario: Add new coupon
        Given I deleted all coupons
        And I press "New discount coupon"
        And I should see "Coupon"
        And I should see "Create"
        When I fill in the following:
            | code  | first_coupon |
            | value | 20           |
        And I press "Create"
        Then I should see "first_coupon" in any ".code" element
        And I should see "20%" in any ".value" element

    @javascript
    Scenario Outline: Add coupon validation
        Tests coupon form fields and validation
        #Incorrect data
        Given I press "New discount coupon"
        When I fill in "code" with <code>
        And I fill in "value" with <value>
        And I press "Create"
        Then page url should contain <url>
        Then I should see <message_value>
        And I should see <message_code>
        And I <should> see coupon <result_code>

        Examples:
            | code                 | value  | should     | result_code | url             | message_code                            | message_value                           |
            | ""                   | ""     | should     | "Coupon"    | "target=coupon" | "This field is required"                | "This field is required"                |
            | 123                  | "-1"   | should     | "Coupon"    | "target=coupon" | "Minimum 4 characters allowed"          | "Minimum value is 0"                    |
            | 12345678901234567890 | "10"   | should     | "Coupon"    | "target=coupon" | "Maximum 16 characters allowed"         | ""                                      |
            | 2345                 | "1000" | should     | "Coupon"    | "target=coupon" | ""                                      | "The discount should be less than 100%" |
            | 12345                | "50"   | should     | "12345"     | "page=coupons"  | "The coupon has been added"             | ""                                      |
            | 12345                | "50"   | should     | "Coupon"    | "target=coupon" | "code is already used for other coupon" | ""                                      |

    @javascript
    Scenario Outline: Edit coupon validation
        Tests coupon form fields and validation
        #Incorrect data
        Given I am on "admin.php?target=promotions&page=coupons"
        And I should see coupon "12345"
        And I follow "12345"
        When I fill in "code" with <code>
        And I fill in "value" with <value>
        And I press "Update"
        Then page url should contain <url>
        Then I should see <message_value>
        And I should see <message_code>
        And I should see coupon <result_code>

        Examples:
            | code                   | value  | result_code | url             | message_code                            | message_value                           |
            | ""                     | ""     | "12345"     | "target=coupon" | "This field is required"                | "This field is required"                |
            | "123"                  | "-1"   | "12345"     | "target=coupon" | "Minimum 4 characters allowed"          | "Minimum value is 0"                    |
            | "12345678901234567890" | "10"   | "12345"     | "target=coupon" | "Maximum 16 characters allowed"         | ""                                      |
            | "2345"                 | "1000" | "12345"     | "target=coupon"  | ""                                      | "The discount should be less than 100%" |
            | "first_coupon"         | "50"   | "12345"     | "target=coupon" | "code is already used for other coupon" | ""                                      |
            | "12346"                | "50"   | "12346"     | "page=coupons"  | "The coupon has been updated"           | ""                                      |




    #Date validation
    @javascript
    Scenario: Date validation error
        Given I press "New discount coupon"
        When I fill in "dateRangeBegin" with "03/05/2012"
        And I fill in "dateRangeEnd" with "01/05/2012"
        And I press "Create"
        Then I should see "Period start date must be sooner than period end date"
        And I should see "Period end date must be later than period start date"

    @javascript
    Scenario: Date validation ok
        Given I press "New discount coupon"
        When I fill in "dateRangeBegin" with "03/05/2012"
        And I fill in "dateRangeEnd" with "05/05/2012"
        And I press "Create"
        Then I should not see "Period start date must be sooner then period end date"
        And I should not see "Period end date must be later then period start date"

    #Range validation
    @javascript
    Scenario: Range validation error
        Given I press "New discount coupon"
        When I fill in "totalRangeBegin" with 200
        And I fill in "totalRangeEnd" with 100
        And I press "Create"
        Then I should see "Minimum order subtotal must be less than maximum order subtotal"
        And I should see "Maximum order subtotal must be greater than minimum order subtotal"

    @javascript
    Scenario: Range validation ok
        Given I press "New discount coupon"
        When I fill in "totalRangeBegin" with 100
        And I fill in "totalRangeEnd" with 200
        And I press "Create"
        Then I should not see "Minimum order subtotal must be less than maximum order subtotal"
        And I should not see "Maximum order subtotal must be greater than minimum order subtotal"

    @javascript
    Scenario: Coupon enable/disable
        Tests enabling/disabling coupons from coupons list
        Given there are coupons:
            | name  | enabled |
            | 4567  | enabled |
            | 12345 | enabled |
            | some  | enabled |
            | other | enabled |
        When I toggle following:
            | name  | enabled |
            | 4567  | disabled |
            | some  | disabled |
        Then I should see coupons:
            | name  | enabled  |
            | 4567  | disabled |
            | 12345 | enabled  |
            | some  | disabled |
            | other | enabled  |

    @javascript
    Scenario: Coupon enable/disable
        Tests enabling/disabling coupons from coupons list
        Given there are coupons:
            | name  |
            | 4567  |
            | 12345 |
            | some  |
            | other |
        When I delete following:
            | name  |
            | 4567  |
            | some  |
        Then I should see coupons:
            | name  |
            | 12345 |
            | other |

#    @javascript, @client
#    Scenario: Coupon value
#        Tests correctness of coupon discount
#        depending on product classes
#
#    @javascript, @client
#    Scenario: Coupon ranges
#        Tests applicability of coupon date
#        and subtotal ranges
#
#    @javascript, @client
#    Scenario: Coupon uses count
#
#    @javascript, @client
#    Scenario: Coupon membership






    