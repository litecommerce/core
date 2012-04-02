<?php

use Behat\Behat\Context\Step\When,
    Behat\Behat\Context\Step\Given;

$steps->Given('/^there are "([^"]*)" admin with no roles$/', function(FeatureContext $world, $email){
    $world->logInAsAdmin();
    return array(
        new Given('I open "'.$email.'" user page'),
        new Given('option "Admin" is selected in "access_level"'),
        new When('I press "Select options"'),
        new When('I follow "Uncheck all"'),
        new When('I press "Update"')
    );
});

$steps->Given('/^I logged in as no permission$/', function(FeatureContext $world){
    $world->visit('admin.php');
    if($world->isPageContainsText('Please identify yourself')){
        $world->fillField('login', 'rnd_tester@rrf.ru');
        $world->fillField('password', 'master');
        $world->pressButton('Log in');
        $world->visit('admin.php');
    }
    if(!$world->isPageContainsText('Sign out')){
        throw new Exception('Failed to log in as admin');
    }
});

$steps->Then('/^I should see Access denied in all controllers$/', function(FeatureContext $world){
    $controllers = array(
        'product_list',
        'product',
        'product_classes',
        'categories',
        'import_export',
        'profile_list',
        'memberships',
        'recent_orders',
        'order_list',
        'orders_stats',
        'top_sellers',
        'shipping_methods',
        'states',
        'taxes',
        'countries',
        'settings',
        'currences',
        'payment_methods',
        'languages',
        'sitemap',
        'db_backup',
        'cache_management&action=rebuild',
        'upgrade',
        'addons_list_installed',
        'addons_list_marketplace',
        'storefront',
    );

    foreach($controllers as $controller){
        $world->visit("admin.php?target=".$controller);
        $world->assertPageContainsText("Access denied");
    }
});