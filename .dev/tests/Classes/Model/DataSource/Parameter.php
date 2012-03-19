<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\DataSource\Parameter class tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.17
 */

class XLite_Tests_Model_DataSource_Parameter extends XLite_Tests_TestCase
{
    protected $entityData = array(
        'id'    => 999,
        'name'  => 'info',
        'value' => array(
            'type'    => 'Ecwid',
            'storeid' => 1003,
        ),
    );

    /**
     * testCreate
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function testCreate()
    {
        $p = new \XLite\Model\DataSource\Parameter();

        foreach ($this->entityData as $field => $testValue) {
            $setterMethod = 'set' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $p->$setterMethod($testValue);
            $value = $p->$getterMethod();
            $this->assertEquals($testValue, $value, 'Creation checking (' . $field . ')');
        }
        $em = \XLite\Core\Database::getEM();
        $em->persist($p);
        $em->flush();

        $this->assertTrue(0 < $p->getId(), 'check parameter id');
        $em->remove($p);
        $em->flush($p);
    }

}
