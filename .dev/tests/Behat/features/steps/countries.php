<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 29.02.12
 * Time: 17:23
 * To change this template use File | Settings | File Templates.
 */

use Behat\Behat\Context\Step\Then;

$steps->Then('/^I should see "([^"]*)" country$/', function($world, $country) {
    return new Then('I should see "'.$country.'" in any ".highlight a" element');
});

$steps->Then('/^I should not see "([^"]*)" country$/', function($world, $country) {
    return new Then('I should not see "'.$country.'" in any ".highlight a" element');
});

