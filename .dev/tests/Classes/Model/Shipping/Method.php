<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * \XLite\Model\Shipping\Method class tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_Shipping_Method extends XLite_Tests_TestCase
{
    /**
     * testCreate
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCreate()
    {
        $newMethod = new \XLite\Model\Shipping\Method();

        $newMethod->setName('Test');
        $newMethod->setProcessor('offline');
        $newMethod->setCarrier('ups');
        $newMethod->setEnabled(1);
        $newMethod->setPosition(888);

        \XLite\Core\Database::getEM()->persist($newMethod);
        \XLite\Core\Database::getEM()->flush();

        $methodId = $newMethod->getMethodId();

        $this->assertTrue(isset($methodId), 'Object could not be created');
        
        if (isset($methodId)) {
            $method = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->find($methodId);

            $this->assertEquals('Test', $method->getName(), 'Wrong method name');
            $this->assertEquals('offline', $method->getProcessor(), 'Wrong processor');
            $this->assertEquals('ups', $method->getCarrier(), 'Wrong carrier');
            $this->assertEquals(1, $method->getEnabled(), 'Wrong status');
            $this->assertEquals(888, $method->getPosition(), 'Wrong position');
        }
    }

}
