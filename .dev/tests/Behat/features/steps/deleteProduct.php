<?php
use \Behat\Behat\Context\Step\When;

$steps->Given('/^there are (\d+) products with enabled inventory$/', function(FeatureContext $world, $count)
{
    $world->visit($world::$adminUrl . 'admin.php?target=product_list&mode=search');

    $products = array();
    $rows = $world->findAll('xpath', '//tr[contains(@class, "entity-")]');

    foreach ($rows as $row) {
        $quantity = $row->find('css', 'td.cell.qty');

        if (strpos($quantity->getAttribute('class'), 'infinity') !== false) {
            continue;
        }

        $name = $row->find('css', '.cell.name a')->getText();
        $price = $row->find('css', '.cell.price')->getText();
        $sku = $row->find('css', '.cell.sku')->getText();

        if (preg_match('/entity-([0-9]+)$/', $row->getAttribute('class'), $matches) && isset($matches[1])) {
            $products[] = array('id' => $matches[1], 'name' =>$name, 'price' =>$price, 'sku' =>$sku);
        }


        if (count($products) == $count) {
            $world->setParameter('products', $products);

            return;
        }
    }
});

$steps->When('/^I buy products$/', function(FeatureContext $world)
{
    $products = $world->getParameter('products');
    $urls = array_map(function($product) use($world)
    {
        return $world::$clientUrl . "store/product/0/product_id-" . $product['id'];
    }, $products);

    return array(
        //new When('I am on "'. $world::$clientUrl . "store/cart/" . '"'),
        new When('I am logged in'),
        new When('I am on "'.$urls[0].'"'),
        new When('I press "Add to Bag"'),
        new When('I am on "'.$urls[1].'"'),
        new When('I press "Add to Bag"'),
        new When('I pass checkout')
    );
});

$steps->Given('/^I delete first$/', function(FeatureContext $world)
{
    $products = $world->getParameter('products');
    $world->deleteProduct($products[0]['id']);
});
$steps->When('/^I delete second$/', function(FeatureContext $world)
{
    $products = $world->getParameter('products');
    $world->deleteProduct($products[1]['id']);
});

$steps->Then('/^I should see valid order info$/', function(FeatureContext $world)
{
    $checkInvoice = function(FeatureContext $world){
        $products = $world->getParameter('products');
        $subtotal = 0;
        foreach($products as $product){
            $world->assertAnyElementContainsText('td.name', $product['name']);
            $world->assertAnyElementContainsText('td.price', $product['price']);
            $world->assertAnyElementContainsText('td.sku', $product['sku']);
            $subtotal += $product['price'];
        }
        $world->assertAnyElementContainsText('.totals .value', $subtotal);
    };
    #client
    $orderId = $world->getParameter('orderId');
    $world->logIn();
    $world->clickLink('My account');
    $world->clickLink('Orders');
    $world->visit($world->getSession()->getCurrentUrl() . '/' . $orderId);
    call_user_func($checkInvoice, $world);
    #admin
    $world->visit($world::$adminUrl . 'admin.php?target=order&order_id=' . $orderId);
    call_user_func($checkInvoice, $world);
});

$steps->Given('/^I am on order page$/', function(FeatureContext $world)
{
    $orderId = $world->getParameter('orderId');
    $world->visit($world::$adminUrl . 'admin.php?target=order&order_id=' . $orderId);
});

$steps->When('/^I change status to "([^"]*)"$/', function(FeatureContext $world, $status)
{
    $world->selectOption('status', $status);
    $world->pressButton('Submit');
});

$steps->Then('/^I should see products in top sellers$/', function(FeatureContext $world)
{
    $world->visit($world::$adminUrl . 'admin.php?target=top_sellers');
    $products = $world->getParameter('products');
    foreach($products as $product){
        $world->assertPageContainsText($product['name']);
    }
});

$steps->Then('/^I should not see products in top sellers$/', function(FeatureContext $world)
{
    $world->visit($world::$adminUrl . 'admin.php?target=top_sellers');
    $products = $world->getParameter('products');
    foreach($products as $product){
        $world->assertPageNotContainsText($product['name']);
    }
});

