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

class XLite_Tests_Model_Session extends XLite_Tests_TestCase
{
    protected $testSession = array(
        'sid'    => '12345678901234567890123456789012',
        'expiry' => 1602488245, // 2020 year
    );

    public function testCreate()
    {
        $session = $this->getTestSession();

        $this->assertTrue(0 < $session->getId(), 'check session id');

        foreach ($this->testSession as $k => $v) {
            $m = 'get' . \XLite\Core\Converter::convertToCamelCase($k);
            $this->assertEquals($v, $session->$m(), 'Check ' . $k);
        }
    }

    public function testUpdate()
    {
        $session = $this->getTestSession();

        $session->setSid('a2345678901234567890123456789012');

        \XLite\Core\Database::getEM()->persist($session);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        $session = \XLite\Core\Database::getRepo('XLite\Model\Session')->find($session->getId());

        $this->assertEquals('a2345678901234567890123456789012', $session->getSid(), 'check sid');
    }

    public function testDelete()
    {
        $session = $this->getTestSession();

        $id = $session->getId();

        \XLite\Core\Database::getEM()->remove($session);
        \XLite\Core\Database::getEM()->flush();

        $session = \XLite\Core\Database::getRepo('XLite\Model\Session')
            ->find($id);

        $this->assertNull($session, 'check removed session');
    }

    public function testUpdateExpiry()
    {
        $session = $this->getTestSession();

        $session->updateExpiry();

        $this->assertNotEquals($session->getExpiry(), $this->testSession['expiry'], 'check new expiry');
        $this->assertTrue(time() < $session->getExpiry(), 'check new expiry #2');
    }

    public function testGetCellByName()
    {
        $session = $this->getTestSession();

        $cell = $session->getCellByName('zzz');

        $this->assertNull($cell, 'empty cell');

        $session->zzz = 123;

        $cell = $session->getCellByName('zzz');
        $this->assertTrue($cell instanceof \XLite\Model\SessionCell, 'exists cell');
        $this->assertEquals(123, $cell->getValue(), 'chec cell value');

        unset($session->zzz);

        $cell = $session->getCellByName('zzz');
        $this->assertNull($cell, 'empty cell #2');
    }

    public function testCells()
    {
        $session = $this->getTestSession();

        $this->assertNull($session->zzz, 'empty cell');

        $session->zzz = 999;
        $this->assertEquals(999, $session->zzz, 'exist cell');

        $session->zzz = array(1, 2, 3);
        $this->assertEquals(array(1, 2, 3), $session->zzz, 'exist cell #2');

        $this->assertTrue(isset($session->zzz), 'check isset');

        unset($session->zzz);;

        $this->assertFalse(isset($session->zzz), 'check isset #2');

        $session->zzz = array(1, 2, 3);
        $this->assertEquals(array(1, 2, 3), $session->zzz, 'exist cell #3');

        $session->zzz = null;
        $this->assertNull($session->zzz, 'empty cell #2');
    }

    protected function getTestSession()
    {
        foreach (\XLite\Core\Database::getRepo('XLite\Model\Session')->findAll() as $s) {
            \XLite\Core\Database::getEM()->remove($s);
        }
        \XLite\Core\Database::getEM()->flush();

        $session = new \XLite\Model\Session();

        $session->map($this->testSession);

        \XLite\Core\Database::getEM()->persist($session);
        \XLite\Core\Database::getEM()->flush();

        return $session;
    }
}
