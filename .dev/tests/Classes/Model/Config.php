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
 *
 * @resource config
 */

class XLite_Tests_Model_Config extends XLite_Tests_TestCase
{
    protected $entityData = array(
        'name'           => 'test name',
        'category'       => 'test category',
        'type'           => 'test type',
        'orderby'        => 100,
        'value'          => 'test value',
        'option_name'    => 'test option name',
        'option_comment' => 'test option comment',
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
        $c = new \XLite\Model\Config();

        foreach ($this->entityData as $field => $testValue) {
            $setterMethod = 'set' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $c->$setterMethod($testValue);
            $value = $c->$getterMethod();
            $this->assertEquals($testValue, $value, 'Creation checking (' . $field . ')');
        }

        $em = \XLite\Core\Database::getEM();
        $em->persist($c);
        $em->flush();

        $this->assertTrue(0 < $c->getConfigId(), 'check config id');
        $em->remove($c);
        $em->flush();

    }
}
