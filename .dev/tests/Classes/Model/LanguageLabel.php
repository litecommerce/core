<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\Category class tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

class XLite_Tests_Model_LanguageLabel extends XLite_Tests_TestCase
{
    protected $entityData = array(
        'name'  => 'test name',
        'label' => 'test label',
    );

    /**
     * testCreate
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testCreate()
    {
        $c = new \XLite\Model\LanguageLabel();

        foreach ($this->entityData as $field => $testValue) {
            $setterMethod = 'set' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $c->$setterMethod($testValue);
            $value = $c->$getterMethod();
            $this->assertEquals($testValue, $value, 'Creation checking (' . $field . ')');
        }

        \XLite\Core\Database::getEM()->persist($c);
        \XLite\Core\Database::getEM()->flush();

        $this->assertTrue(0 < $c->getLabelId(), 'check language label id');
        \XLite\Core\Database::getEM()->remove($c);
        \XLite\Core\Database::getEM()->flush();
    }
}
