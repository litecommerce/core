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
 * @resource country
 */

class XLite_Tests_Model_Country extends XLite_Tests_TestCase
{
    protected $entityData = array(
        'country'   => 'test country',
        'code'      => 'ZZ',
        'enabled'   => false,
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
        $c = new \XLite\Model\Country();

        foreach ($this->entityData as $field => $testValue) {

            $setterMethod = 'set' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);

            $c->$setterMethod($testValue);

            $value = $c->$getterMethod();

            $this->assertEquals($testValue, $value, 'Creation checking (' . $field . ')');
        }

        $s = new \XLite\Model\State;

        $s->setState('test state');
        $s->setCode('ttt');
        $c->addStates($s);

        $em = \XLite\Core\Database::getEM();
        $em->persist($c);
        $em->flush();
        $em->clear();

        $c = \XLite\Core\Database::getEM()->merge($c);

        foreach ($this->entityData as $field => $testValue) {

            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);

            $this->assertEquals($testValue, $c->$getterMethod(), 'Creation checking (' . $field . ') #2');
        }

        $this->assertEquals($s->getStateId(), $c->getStates()->get(0)->getStateId(), 'check state');
        
        $em->remove($c);
        $em->flush();

    }
}
