<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 4/12/12
 * Time: 1:55 PM
 * To change this template use File | Settings | File Templates.
 */




$steps->Given('/^I deleted all rates$/', function(FeatureContext $world) {
    throw new \Behat\Behat\Exception\PendingException();
    while($delete = $world->find('css', '.rate-remove')){
        $delete->click();
    }
});

$steps->Given('/^VAT is enabled$/', function(FeatureContext $world) {
    throw new \Behat\Behat\Exception\PendingException();
    if ($world->find('css', '.button.disabled')){
        $world->pressButton('Tax disabled');
    }
    if ($world->find('css', '.button.enabled') === null){
        throw new \Behat\Mink\Exception\ExpectationException('Failed to enable tax', $world->getSession());
    }
});

$steps->Given('/^there are products with classes:$/', function(FeatureContext $world, \Behat\Gherkin\Node\TableNode $table) {
    throw new \Behat\Behat\Exception\PendingException();
    $world->createProductClasses($table);

    $rows = $table->getHash();

    $world->visit('admin.php?target=product_list');
    $world->selectOption('itemsPerPage', '100');
    foreach($rows as $row){
        foreach($world->findAll('css', '.cell.name a') as $product_link){
            if (strpos($product_link->getText(), $row['name'])){
                $product_link->click();
                $world->selectOption('postedData[class_ids][]', $row['class']);
                $world->fillField('postedData[price]', $row['price']);
                $world->pressButton('Update product');
                break 2;
            }
        }
        throw new \Behat\Mink\Exception\ExpectationException('Product ' .$row['name'] . ' not found', $world->getSession());
    }

});

$steps->When('/^I create rates:$/', function(FeatureContext $world, \Behat\Gherkin\Node\TableNode $table) {
    throw new \Behat\Behat\Exception\PendingException();
    $rows = $table->getHash();
    $i = 0;
    foreach($rows as $row){
        $world->pressButton("New rate");
        $world->fillField('rates[-'.$i.'][value]', $row['rate']);
        if (isset($row['membership'])){
            $world->selectOption('rates[-'.$i.'][membership]', $row['membership']);
        }
        if (isset($row['zone'])){
            $world->selectOption('rates[-'.$i.'][zone]', $row['zone']);
        }
        if (isset($row['class'])){
            $world->selectOption('rates[-'.$i.'][productClass]', $row['class']);
        }
        $i++;
    }
    $world->pressButton('Save');


});

$steps->Given('/^I set zone to "([^"]*)"$/', function(FeatureContext $world, $zone) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I set including as "([^"]*)"$/', function(FeatureContext $world, $inc) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I should see "([^"]*)" label$/', function(FeatureContext $world, $label) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I should see "([^"]*)" price$/', function(FeatureContext $world, $price) {
    throw new \Behat\Behat\Exception\PendingException();
    $world->visitProductCustomer();
    $world->assertElementContains('.price.product-price', $price);
});

$steps->Given('/^I should see VAT "([^"]*)" on product page$/', function(FeatureContext $world, $rate) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I should see VAT "([^"]*)" in cart$/', function(FeatureContext $world, $rate) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I should see VAT "([^"]*)" on checkout$/', function(FeatureContext $world, $rate) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Then('/^I should see VAT "([^"]*)" on "([^"]*)" product page$/', function(FeatureContext $world, $rate, $product_name) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Then('/^I should not see VAT "([^"]*)" on product page$/', function(FeatureContext $world, $rate) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I should not see VAT "([^"]*)" in cart$/', function(FeatureContext $world, $rate) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I should not see VAT "([^"]*)" on checkout$/', function(FeatureContext $world, $rate) {
    throw new \Behat\Behat\Exception\PendingException();
});
