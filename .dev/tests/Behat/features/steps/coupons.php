<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 01.03.12
 * Time: 13:00
 * To change this template use File | Settings | File Templates.
 */

use \Behat\Gherkin\Node\TableNode,
\Behat\Behat\Context\Step\Then,
\Behat\Behat\Context\Step\Given,
\Behat\Behat\Context\Step\When;

$steps->Given('/^I deleted all coupons$/',
    'deleteAllCoupons');

function deleteAllCoupons(FeatureContext $world)
{
    $nodes = $world->findAll('css', '.remove', false);
    if (!empty($nodes)) {
        foreach ($nodes as $node) {
            if (strpos($node->getAttribute('class'), 'marked') === false)
                $node->click();
        }
        $world->pressButton("Save changes");
    }
}

$steps->Then('/^I should (?:|not )see coupon "(?P<code>(?:[^"]|\\")*)"$/',
    function(FeatureContext $world, $code)
    {

        if (isset($code)) {
            $expectation = (strlen(trim($code)) === 0) ? "should not" : "should";
        }
        else {
            $expectation = "should not";
            $code = "";
        }

        $url = $world->getSession()->getCurrentUrl();
        if (strpos($url, "target=coupon") !== false) {
            return new Then("I $expectation see \"$code\" in the \".title\" element");
        }
        else {
            return new Then("I $expectation see \"$code\" in any \".code\" element");
        }

    });



$steps->Given('/^there are coupons:$/', function(FeatureContext $world, TableNode $table)
{
    deleteAllCoupons($world);
    $hash = $table->getHash();
    foreach ($hash as $row) {
        $world->pressButton("New discount coupon");
        $world->fillFields(new \Behat\Gherkin\Node\TableNode(
            '| code  | '.$row['name'].' |
             | value | 50           |'));
        $world->pressButton("Create");
    }
    toggleCoupons($world, $table);
});

$steps->Given('/^I should see coupons:$/', function(FeatureContext $world, TableNode $table)
{
    $hash = $table->getHash();
    foreach ($hash as $row) {
        // $row['name'], $row['email'], $row['phone']
        new Given('I should see "' . $row['name'] . '" in any "a.link" element');
        if (isset($row['enabled'])) {
            $switcher = $world->find('xpath', '//a[text()="' . $row['name'] . '"]/ancestor::tr//span[contains(@class, "input-checkbox-switcher")]');
            $class = $switcher->getAttribute('class');
            try {
                assertContains($row['enabled'], $class);
            }
            catch (Exception $e) {
                throw new \Behat\Mink\Exception\ExpectationException('Coupon is not ' . $row['enabled'], $world->getSession(), $e);
            }
        }
    }
});



function toggleCoupons(FeatureContext $world, TableNode $table)
{
    $hash = $table->getHash();
    foreach ($hash as $row) {
        if (isset($row['enabled'])){
            $switcher = $world->find('xpath', '//a[text()="' . $row['name'] . '"]/ancestor::tr//span[contains(@class, "input-checkbox-switcher")]');
            $class = $switcher->getAttribute('class');
            if (strpos($class, $row['enabled']) === false){
                $switcher->find('css', '.widget')->click();
            }
        }
    }
    $world->pressButton("Save changes");
};


$steps->When('/^I toggle following:$/', function(FeatureContext $world, TableNode $table)
{
    toggleCoupons($world, $table);
});

$steps->When('/^I delete following:$/', function(FeatureContext $world, TableNode $table)
{
    $hash = $table->getHash();
    foreach ($hash as $row) {
        // $row['name'], $row['email'], $row['phone']
        $switcher = $world->find('xpath', '//a[text()="' . $row['name'] . '"]/ancestor::tr//button[contains(@class, "remove")]');
        $switcher->click();
    }
    return new When('I press "Save changes"');
});

