<?php


$steps->Given('/^there are (\d+) products with enabled inventory$/', function(FeatureContext $world, $count) {
    $world->visit('admin.php?target=product_list&mode=search');

    $productIds = array();
    $rows = $world->findAll('xpath', '//tr[contains(@class, "entity-")]');

    foreach($rows as $row){
        $quantity = $row->find('css', 'td.cell.qty');
        if (strpos($quantity->getAttribute('class'), 'infinity') !== false){
            preg_match('entity-(d+)', $row->getAttribute('class'), $id);
            if (isset($id[0])){
                $productIds[] = $id[1][0];
                if (count($productIds) == $count){
                    return;
                }
            }
        }
    }

});

$steps->When('/^I buy products$/', function(FeatureContext $world) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I delete first$/', function(FeatureContext $world) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Then('/^I should see valid order info$/', function(FeatureContext $world) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I am on order page$/', function(FeatureContext $world) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->When('/^I change status to "([^"]*)"$/', function(FeatureContext $world, $arg1) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I should see valid top sellers$/', function(FeatureContext $world) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I should see valis statistics$/', function(FeatureContext $world) {
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->When('/^I delete second$/', function(FeatureContext $world) {
    throw new \Behat\Behat\Exception\PendingException();
});