<?php


$steps->Given('/^there is product$/', function(FeatureContext $world) {

    $world->visit('admin.php?target=product_list&mode=search');

    $rows = $world->findAll('xpath', '//tr[contains(@class, "entity-")]');

    foreach($rows as $row){
        $switcherClass = $row->find('css', '.input-checkbox-switcher')->getAttribute('class');
        if (strpos($switcherClass, 'disabled') !== false){
            continue;
        }


        $name = trim($row->find('css', '.cell.name a')->getText());
        $price = trim($row->find('css', '.cell.price .value')->getText());
        $sku = trim($row->find('css', '.cell.sku')->getText());

        if (preg_match('/entity-([0-9]+)$/', $row->getAttribute('class'), $matches) && isset($matches[1])) {
            $product = array('id' => $matches[1], 'name' =>$name, 'price' =>$price, 'sku' =>$sku);
            $world->setParameter('product', $product);
            return;
        }
    }
});

$steps->When('/^i create tiers:$/', function(FeatureContext $world, \Behat\Gherkin\Node\TableNode $table) {
    $rows = $table->getHash();

    $world->clearList();

    $i = -1;
    foreach($rows as $row){
        //| range | price | membership |

        $world->pressButton('New tier');
        $world->fillField("new[$i][quantityRangeBegin]", $row['range']);
        $world->fillField("new[$i][price]", $row['price']);
        $world->selectOption("new[$i][membership]", $row['membership']);
        $i--;
    }
    $world->pressButton('Save changes');
});

$steps->Given('/^I set minimum quantity to (\d+)$/', function(FeatureContext $world, $qty) {
    $world->clickLink('Wholesale pricing');
    $world->clickLink('Minimum purchase quantity');
    $world->fillField('postedData[minQuantity][0]', $qty);
    $world->pressButton('Save changes');
});

$steps->Given('/^I set product quantity to (\d+)$/', function(FeatureContext $world, $qty) {
    $world->clickLink('Inventory tracking');
    $world->fillField('postedData[amount]', $qty);
    $world->selectOption('postedData[enabled]', '1');
    $world->pressButton('Update');
});

$steps->Then('/^I should see price table:$/', function(FeatureContext $world, \Behat\Gherkin\Node\TableNode $table) {
    $world->visitProductCustomer();
    $world->assertElementOnPage('.wholesale-price-header');
    $world->assertElementOnPage('.wholesale-prices-product-block');
    $rows = $table->getHash();
    foreach($rows as $row){
        $world->assertAnyElementContainsText('.items-range', $row['range']);
        $world->assertAnyElementContainsText('.price-value', $row['price']);
        if ($row['save']){
            $world->assertAnyElementContainsText('.save-price-value', $row['save']);
        }
        else{
            $world->assertElementOnPage('.save-price-value-null');
        }
    }
});


$steps->Then('/^I should see price "([^"]*)"$/', function(FeatureContext $world, $price) {
    $world->visitProductCustomer();
    $world->assertElementContainsText('.product-price', $price);
});

$steps->Given('/^I should see minimum quantity (\d+)$/', function(FeatureContext $world, $qty) {
    $world->visitProductCustomer();
    $world->assertElementContainsText(',wholesale-minimum-quantity', $qty);
});

$steps->Given('/^I should not see price table$/', function(FeatureContext $world) {
    $world->visitProductCustomer();
    $world->assertElementNotOnPage('.wholesale-price-header');
    $world->assertElementNotOnPage('.wholesale-prices-product-block');
});

