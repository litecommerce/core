<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Core\Converter and \Includes\Utils\Converter classes tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.3
 */

class XLite_Tests_Core_EventDriver_AMQP extends XLite_Tests_TestCase
{
    public function testIsValid()
    {
        $this->assertTrue(\XLite\Core\EventDriver\AMQP::isValid(), 'RabbitMQ is running');
    }

    public function testGetCode()
    {
        $this->assertEquals('amqp', \XLite\Core\EventDriver\AMQP::getCode(), 'check driver code');
    }

    public function testFire()
    {
        $driver = new \XLite\Core\EventDriver\AMQP;
        $driver->fire('test', array(1, 2, 3));
        $driver->fire('test2', array(4, 5, 6));
    }
}
