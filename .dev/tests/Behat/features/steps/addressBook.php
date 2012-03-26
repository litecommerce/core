<?php


use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\Behat\Context\Step\Given;
use Behat\Behat\Definition\Loader\ClosuredDefinitionLoader;

use Behat\Mink\Behat\Context\MinkContext;

//
// Require 3rd-party libraries here:
//
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

$steps->Given('/^I deleted all addresses$/',
    function(FeatureContext $world){
        $node = $world->find('css', '.delete-address', false);
        while($node !== null){
            $node->click();
            $world->passConfirmation();
            $node = $world->find('css', '.delete-address', false);
        }
    });

$steps->Given('/^There is "([^"]*)" address$/',
    function(FeatureContext $world, $suffix){
        $world->assertAnyElementContainsText('.address-box', $suffix);
    });

$steps->When('/^I fill address with following:$/',
    function(FeatureContext $world, TableNode $table){
        $idField = $world->find('css','input[name="address_id"]');
        if($idField === null){
            throw new \Behat\Mink\Exception\ElementNotFoundException($world->getSession(), 'input', 'name', 'address_id');
        }
        $id = $idField->getValue();
        $table->replaceTokens(array('id' => $id));
        $world->fillFields($table);
    });
