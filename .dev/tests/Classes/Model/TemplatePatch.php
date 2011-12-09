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

class XLite_Tests_Model_TemplatePatch extends XLite_Tests_TestCase
{
    protected $entityData = array(
        'zone'              => 'admin',
        'lang'              => 'zz',
        'tpl'               => 'test.tpl',
        'patch_type'        => 'test',
        'xpath_query'       => '//*',
        'xpath_insert_type' => 'after',
        'xpath_block'       => 'test block',
        'regexp_pattern'    => '/.+/Ss',
        'regexp_replace'    => '$0',
        'custom_callback'   => 'callback',
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
        $c = new \XLite\Model\TemplatePatch();

        foreach ($this->entityData as $field => $testValue) {
            $setterMethod = 'set' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $c->$setterMethod($testValue);
            $value = $c->$getterMethod();
            $this->assertEquals($testValue, $value, 'Creation checking (' . $field . ')');
        }

        $em =\XLite\Core\Database::getEM();
        $em->persist($c);
        $em->flush();

        $this->assertTrue(0 < $c->getPatchId(), 'check patch id');
        $em->remove($c);
        $em->flush();
    }
}
