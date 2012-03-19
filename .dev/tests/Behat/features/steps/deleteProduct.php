<?php
use \Behat\Behat\Context\Step\When;

$steps->Given('/^there are (\d+) products with enabled inventory$/', function(FeatureContext $world, $count)
{
    $world->visit('admin.php?target=product_list&mode=search');

    $productIds = array();
    $rows = $world->findAll('xpath', '//tr[contains(@class, "entity-")]');

    foreach ($rows as $row) {
        $quantity = $row->find('css', 'td.cell.qty');

        if (strpos($quantity->getAttribute('class'), 'infinity') !== false) {
            continue;
        }
        if (preg_match('/entity-([0-9]+)$/', $row->getAttribute('class'), $matches) && isset($matches[1])) {
            $productIds[] = $matches[1];
            if (count($productIds) == $count) {
                $world->setParameter('productIds', $productIds);
                return;
            }
        }
    }
});

$steps->When('/^I buy products$/', function(FeatureContext $world)
{
    $urls = array_map($world->getParameter('productIds'), function($productId) use($world)
    {
        return $world::$clientUrl . "store/product/0/product_id-" . $productId;
    });

    return array(
        new When('I visit "'. $world::$clientUrl . "store/cart/" . '"'),
        new When('I follow "Clear bag"'),
        new When('I visit "'.$urls[0].'"'),
        new When('I press "Add to cart"'),
        new When('I visit "'.$urls[1].'"'),
        new When('I press "Add to cart"')
    );
});

$steps->Given('/^I delete first$/', function(FeatureContext $world)
{

});

$steps->Then('/^I should see valid order info$/', function(FeatureContext $world)
{
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I am on order page$/', function(FeatureContext $world)
{
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->When('/^I change status to "([^"]*)"$/', function(FeatureContext $world, $arg1)
{
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I should see valid top sellers$/', function(FeatureContext $world)
{
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->Given('/^I should see valis statistics$/', function(FeatureContext $world)
{
    throw new \Behat\Behat\Exception\PendingException();
});

$steps->When('/^I delete second$/', function(FeatureContext $world)
{
    throw new \Behat\Behat\Exception\PendingException();
});