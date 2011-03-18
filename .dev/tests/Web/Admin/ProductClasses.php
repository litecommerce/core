<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Web
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

require_once __DIR__ . '/AAdmin.php';

class XLite_Web_Admin_ProductClasses extends XLite_Web_Admin_AAdmin
{
    const NEW_PRODUCT_CLASS_LABEL = '//div[@class="advanced-input-text"]/div[@class="original-label" and text()="New product class"]';

    const NEW_PRODUCT_CLASS_INPUT = '//div[@class="advanced-input-text"]/div[@class="original-input"]/input[@type="text" and @id="posteddata-new-name" and @name="postedData[new_name]"]';

    const PC_PAGE = 'admin.php?target=product_classes';

    /**
     * Test of product classes modify page
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testProductClassesModify()
    {
        $this->logIn();

        $this->open(self::PC_PAGE);

        // ADD NEW Element

        $this->assertElementPresent(
            self::NEW_PRODUCT_CLASS_LABEL,
            'No new product class label'
        );

        $this->click(self::NEW_PRODUCT_CLASS_LABEL);

        $this->assertVisible(
            self::NEW_PRODUCT_CLASS_INPUT,
            'No input for new product class'
        );

        $this->addNewProductClass('test1');
        $this->addNewProductClass('test2');

        $this->checkAdvancedInput('test1', 'test11');
        $this->checkAdvancedInput('test2', 'test22');

        $this->removeInput('test11');
        $this->removeInput('test22');
    }


    /**
     * Test of product modify page (product classes only)
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testProductPage()
    {
        $this->logIn();

        $productPage = 'admin.php?target=product&page=info&product_id=5';

        $this->open($productPage);

        $this->assertElementPresent(
            '//td/span[@class="empty-list"]/a[@href="admin.php?target=product_classes" and text()="Define classes"]',
            'No "define classes" link'
        );

        $this->open(self::PC_PAGE);

        $classes = array(
            'test1',
            'test2',
            'test3',
        );

        foreach ($classes as $class) {

            $this->addNewProductClass($class);
        }

        $this->open($productPage);

        foreach ($classes as $class) {
            $this->assertElementPresent(
                '//div[@class="select-classes"]/select[@id="posteddata-class-ids-" and @name="postedData[class_ids][]" and @multiple="multiple"]/option[text()="' . $class . '"]',
                'No "' . $class . '" class option'
            );
        }

        $this->open(self::PC_PAGE);

        foreach ($classes as $class) {

            $this->removeInput($class);
        }

    }

    /**
     * Procedure to add new product class
     * 
     * @param string $name New product class name
     *  
     * @return void
     * @access private
     * @see    ____func_see____
     * @since  3.0.0
     */
    private function addNewProductClass($name)
    {
        $count = $this->getTableLength();

        $this->type(self::NEW_PRODUCT_CLASS_INPUT, $name);
        $this->keyPress(self::NEW_PRODUCT_CLASS_INPUT, '\\13');

        $count++;

        $this->waitForLocalCondition(
            'jQuery(".product-classes-list tr").length == ' . $count,
            30000,
            'check add new product class entry "' . $name . '"'
        );
    }


    /**
     * Return number of rows in the product classes table
     * 
     * @return integer
     * @access private
     * @see    ____func_see____
     * @since  3.0.0
     */
    private function getTableLength()
    {
        return $this->getJSExpression('jQuery(".product-classes-list tr").length');
    }

    /**
     * Procedure to use remove link functionality
     * 
     * @param mixed $name ____param_comment____
     *  
     * @return void
     * @access private
     * @see    ____func_see____
     * @since  3.0.0
     */
    private function removeInput($name)
    {
        $remove = '//div[@class="advanced-input-text"]/div[@class="original-label" and text()="' . $name . '"]/../../../td[@class="remove-product-class"]/a[@class="remove"]';

        $count = $this->getTableLength();

        $this->click($remove);

        $count--;

        $this->waitForLocalCondition(
            'jQuery(".product-classes-list tr").length == ' . $count,
            30000,
            'check remove product class entry'
        );
    }


    /**
     * Procedure to check main advanced input element
     * 
     * @param string $name    Product class name in the input 
     * @param string $newName New product class name (if there is some). Check to update.
     *  
     * @return void
     * @access private
     * @see    ____func_see____
     * @since  3.0.0
     */
    private function checkAdvancedInput($name, $newName = '')
    {
        $label = '//div[@class="advanced-input-text"]/div[@class="original-label" and text()="' . $name . '"]';
        $input = '//div[@class="advanced-input-text"]/div[@class="original-label" and text()="' . $name . '"]/../div[@class="original-input"]/input';

        $this->assertElementPresent(
            $label,
            'No label for "' . $name . '"'
        );

        $inputJS = 'jQuery(".original-input input", selenium.browserbot.getCurrentWindow().jQuery(".product-classes-list tr div.original-label:contains(\'' . $name . '\')").parent()).eq(0)';

        $this->assertTrue(
            0 < $this->getJSExpression($inputJS . '.length'),
            'No input for "' . $name . '"'
        );

        $this->assertVisible(
            $label,
            'Not visible label for "' . $name . '"'
        );

        $this->assertTrue(
            'none' == $this->getJSExpression($inputJS . '.parent().css("display")'),
            'Visible input for "' . $name . '"'
        );

        $this->click($label);

        $this->assertTrue(
            'block' == $this->getJSExpression($inputJS . '.parent().css("display")'),
            'NOT visible input for "' . $name . '"'
        );

        $this->assertNotVisible(
            $label,
            'Visible label for "' . $name . '"'
        );

        if ('' !== $newName) {

            $this->type($input, $newName);
            $this->keyPress($input, '\\13');

            $this->waitForLocalCondition(
                'jQuery(".product-classes-list img.ajax-progress").length == 0',
                30000,
                'Check update element to "' . $newName . '"'
            );

            $this->checkAdvancedInput($newName);
        }
    }
}
