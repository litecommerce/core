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
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_SessionCell extends XLite_Tests_TestCase
{
    protected $testSession = array(
        'sid'    => '12345678901234567890123456789012',
        'expiry' => 1602488245, // 2020 year
    );

    public function testCreate()
    {
        $session = $this->getTestSession();

        $this->assertEquals(1, $session->aaa, 'test aaa');
        $this->assertEquals(2, $session->bbb, 'test bbb');
        $this->assertEquals(3, $session->ccc, 'test ccc');

        $cell = \XLite\Core\Database::getRepo('XLite\Model\SessionCell')->findOneByIdAndName($session->getId(), 'aaa');

        $this->assertTrue(0 < $cell->getCellId(), 'test cell id');
        $this->assertEquals($session->getId(), $cell->getId(), 'test sid');
        $this->assertEquals('aaa', $cell->getName(), 'test name');
        $this->assertEquals(1, $cell->getValue(), 'test value');
        $this->assertEquals('integer', $cell->getType(), 'test type');
    }

    public function testUpdate()
    {
        $session = $this->getTestSession();

        $session->aaa = 4;
        $this->assertEquals(4, $session->aaa, 'test aaa');

        $cell = \XLite\Core\Database::getRepo('XLite\Model\SessionCell')->findOneByIdAndName($session->getId(), 'aaa');
        $this->assertEquals('integer', $cell->getType(), 'test type');

        $cell->setType('boolean');
        $this->assertEquals('integer', $cell->getType(), 'test type');
    }

    public function testDelete()
    {
        $session = $this->getTestSession();

        $session->aaa = null;
        unset($session->bbb);

        $this->assertNull($session->aaa, 'empty aaa');
        $this->assertNull($session->bbb, 'empty bbb');

        $cell = \XLite\Core\Database::getRepo('XLite\Model\SessionCell')->findOneByIdAndName($session->getId(), 'aaa');
        $this->assertNull($cell, 'not exists cell');
    }

    public function testGetterSetter()
    {
        $session = $this->getTestSession();

        $cell = \XLite\Core\Database::getRepo('XLite\Model\SessionCell')->findOneByIdAndName($session->getId(), 'aaa');

        $this->assertEquals('integer', $cell->getType(), 'test type');

        $cell->setValue('1');
        $this->assertEquals('string', $cell->getType(), 'test type #2');
        $this->assertEquals('1', $cell->getValue(), 'test value #2');

        $cell->setValue(array(1, 2, 3));
        $this->assertEquals('array', $cell->getType(), 'test type #3');
        $this->assertEquals(array(1, 2, 3), $cell->getValue(), 'test value #3');

        $cell->setValue(1.99);
        $this->assertEquals('double', $cell->getType(), 'test type #4');
        $this->assertEquals(1.99, $cell->getValue(), 'test value #4');

        $cell->setValue(null);
        $this->assertEquals('', $cell->getType(), 'test type #5');
        $this->assertNull($cell->getValue(), 'test value #5');

        $cell->setValue(true);
        $this->assertEquals('boolean', $cell->getType(), 'test type #6');
        $this->assertTrue($cell->getValue(), 'test value #6');
    }

    protected function getTestSession()
    {
        $id = \XLite\Core\Session::getInstance()->getID();
        foreach (\XLite\Core\Database::getRepo('XLite\Model\Session')->findAll() as $s) {
            if ($s->getSid() != $id) {
                \XLite\Core\Database::getEM()->remove($s);
            }
        }
        \XLite\Core\Database::getEM()->flush();

        $session = new \XLite\Model\Session();

        $session->map($this->testSession);

        \XLite\Core\Database::getEM()->persist($session);
        \XLite\Core\Database::getEM()->flush();

        $session->aaa = 1;
        $session->bbb = 2;
        $session->ccc = 3;


        return $session;
    }
}
