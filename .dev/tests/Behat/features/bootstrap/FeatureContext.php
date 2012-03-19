<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\Behat\Context\Step\Given,
    Behat\Behat\Context\Step\Then;

use Behat\Mink\Behat\Context\MinkContext,
    Behat\Mink\Element\Element,
    Behat\Mink\Element\NodeElement,
    Behat\Mink\Exception\ElementNotFoundException;

//
// Require 3rd-party libraries here:
//
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class FeatureContext extends \Behat\Mink\Behat\Context\MinkContext implements ClosuredContextInterface
{

    protected static $parameters;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param   array   $parameters     context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
    }
    /**
     * Clicks link with specified css_path.
     *
     * @When /^(?:|I )click "(?P<locator>(?:[^"]|\\")*)"$/
     */
    public function click($locator)
    {
        $world = $this;
        $this->withSpeed(1000, function() use($locator, $world){$world->find('css', $locator)->click();});
    }

    /**
     * @When /^(?:|I )change focus to "(?P<locator>(?:[^"]|\\")*)"$/
     */
    public function focus($locator)
    {
        $world = $this;
        $this->withSpeed(1000, function() use($locator, $world){$world->find('css', $locator)->focus();});
    }

    /**
     *
     * @Then /^(?:|I )page url should contain "(?P<page>[^"]+)"$/
     */
    public function assertPageAddressContains($page){
        $actual = $this->getSession()->getCurrentUrl();

        try {
            assertContains($page, $actual);
        } catch (AssertException $e) {
            $message = sprintf('Current page is "%s", but "%s" expected', $actual, $page);
            throw new ExpectationException($message, $this->getSession(), $e);
        }
    }

    /**
     * Checks, that element with specified CSS contains specified text.
     *
     * @Then /^(?:|I )should see "(?P<text>(?:[^"]|\\")*)" in any "(?P<element>[^"]*)" element$/
     */
    public function assertAnyElementContainsText($element, $text)
    {
        $text = str_replace('\\"', '"', $text);
        $elementsText = $this->getAllElementsText($element);
        try {
            assertContains($text, $elementsText);
        } catch (AssertException $e) {
            $message = sprintf('The text "%s" was not found in the text of any element matching css "%s"', $text, $element);
            throw new \Behat\Mink\Exception\ExpectationException($message, $this->getSession(), $e);
        }
    }

    /**
     * Checks, that no elements with specified CSS contains specified text.
     *
     * @Then /^(?:|I )should not see "(?P<text>(?:[^"]|\\")*)" in any "(?P<element>[^"]*)" element$/
     */
    public function assertNoElementContainsText($element, $text)
    {
        $text = str_replace('\\"', '"', $text);
        $elementsText = $this->getAllElementsText($element);

        try {
            assertNotContains($text, $elementsText);
        } catch (AssertException $e) {
            $message = sprintf('The text "%s" was found in the text of any element matching css "%s"', $text, $element);
            throw new \Behat\Mink\Exception\ExpectationException($message, $this->getSession(), $e);
        }
    }


    /**
     * @Given /^I am logged in as admin$/
     */
    public function iAmLoggedInAsAdmin()
    {
        $url = $this->getSession()->getCurrentUrl();

        if (strpos($url, 'admin.php') === false){
            $this->visit('admin.php');
        }
        if($this->isPageContainsText('Please identify yourself')){
            //$this->visit('admin.php?target=login&action=logoff');
            $this->fillField('login', 'rnd_tester@cdev.ru');
            $this->fillField('password', 'master');
            $this->pressButton('Log in');
            $this->visit('admin.php');
        }
        if(!$this->isPageContainsText('Sign out')){
            throw new Exception('Failed to log in as admin');
        }
    }

    /**
     * @When /^I pass confirmation$/
     */
    public function passConfirmation(){
        return $this->getSession()->getDriver()->getBrowser()->getConfirmation();
    }

    protected function isPageContainsText($text){
        $pos = strpos($this->getSession()->getPage()->getText(), $text);
        //echo PHP_EOL."POS of string: " . $pos;
        return $pos !== false;
    }

    public function pressButton($button){

        return $this->withSpeed(1000, array('parent::pressButton', $button), true);
    }
    public function clickLink($link){

        return $this->withSpeed(1000, array('parent::clickLink', $link), true);
    }

    protected function withSpeed($speed, $callback, $isMethod = false){
        if (is_array($callback)){
            $func = $callback[0];
            array_shift($callback);
            $params = isset($callback['params']) ? $callback['params'] : $callback;
        }
        else{
            $params = array();
            $func = $callback;
        }
        if($isMethod){
            $func = array($this, $func);
        }

        $speed = $this->getSession()->getDriver()->setSpeed($speed);
        $result = call_user_func_array($func, $params);
        $this->getSession()->getDriver()->setSpeed($speed);
        return $result;
    }

    /**
     * @param $selector
     * @param $locator
     * @param bool $throwException
     * @return NodeElement
     * @throws ElementNotFoundException
     */

    public function find($selector, $locator, $throwException = true){
        $world = $this;
        return $this->withSpeed(0, function() use($selector, $locator, $world, $throwException){
            $element = $world->getSession()->getPage()->find($selector, $locator);

            if (null === $element && $throwException) {
                throw new ElementNotFoundException(
                    $world->getSession(), null, $selector, $locator
                );
            }
            return $element;
        });
    }

    /**
     * @param $selector
     * @param $locator
     * @param bool $throwException
     * @return NodeElement[]
     * @throws ElementNotFoundException
     */

    public function findAll($selector, $locator, $throwException = true){
        $world = $this;
        return $this->withSpeed(0, function() use($selector, $locator, $world,$throwException){
            $result = $world->getSession()->getPage()->findAll($selector, $locator);
            if (empty($result) && $throwException){
                throw new ElementNotFoundException(
                    $world->getSession(), null, $selector, $locator
                );
            }
            return $result;
        });
    }

    private function getAllElementsText($element){
        $nodes = $this->findAll('css', $element, false);

        return empty($nodes) ? "" : array_reduce($nodes, function($text, $node) {return $text . $node->getText();}, '');

    }

    /**
     * Returns all added subcontexts.
     *
     * @return  array
     */
    function getSubcontexts()
    {
        return array(); // TODO: Implement getSubcontexts() method.
    }

    /**
     * Finds subcontext by it's name.
     *
     * @return  Behat\Behat\Context\ContextInterface
     */
    function getSubcontextByClassName($className)
    {
        return array();// TODO: Implement getSubcontextByClassName() method.
    }

    /**
     * Returns array of step definition files (*.php).
     *
     * @return  array
     */
    function getStepDefinitionResources()
    {
        $steps = array("addressBook", "countries", "coupons");
        return array_map(function($name){return __DIR__.'/../steps/'.$name.'.php';}, $steps);
    }

    /**
     * Returns array of hook definition files (*.php).
     *
     * @return  array
     */
    function getHookDefinitionResources()
    {
        return array();
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getParameter($name){
        self::$parameters = parent::getParameters();
        return isset(self::$parameters[$name]) ? self::$parameters[$name] : null;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed|null Previous value of parameter, null if not set
     */
    public function setParameter($name, $value){
        $param = $this->getParameter($name);
        self::$parameters[$name] = $value;
        return $param;
    }

    /** @AfterStep */
    public function after(\Behat\Behat\Event\StepEvent $event)
    {
        if($event->getResult() == \Behat\Behat\Event\StepEvent::FAILED){
            $screenshot_path = $this->getParameter('screenshots_path');
            if ($screenshot_path){
                $fileName = uniqid() . '.png';
                $path = rtrim($screenshot_path, '/') . '/' . $fileName;
                $this->getSession()->getDriver()->capturePageScreenshot($path);
                $screenshot_url = $this->getParameter('screenshots_url');
                echo "Captured screenshot: " . ($screenshot_url ? ($screenshot_url . '/' . $fileName) : $path)  . PHP_EOL;
            }
        }
    }
}