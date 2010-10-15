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

class XLite_Tests_Model_Repo_Session extends XLite_Tests_TestCase
{
    protected $testSession = array(
        'sid'    => '12345678901234567890123456789012',
        'expiry' => 1602488245, // 2020 year
    );

    public function testRemoveExpired()
    {
        $session = $this->getTestSession();

        $id = $session->getId();

        $session->setExpiry(time() - 1000);
        \XLite\Core\Database::getEM()->persist($session);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        \XLite\Core\Database::getRepo('XLite\Model\Session')->removeExpired();

        $session = \XLite\Core\Database::getRepo('XLite\Model\Session')->find($id);

        $this->assertNull($session, 'check expired session');
    }

    public function testCountBySid()
    {
        $session = $this->getTestSession();

        $sid = $session->getSid();

        $this->assertEquals(1, \XLite\Core\Database::getRepo('XLite\Model\Session')->countBySid($sid), 'checko count');

        \XLite\Core\Database::getEM()->remove($session);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(0, \XLite\Core\Database::getRepo('XLite\Model\Session')->countBySid($sid), 'checko count #2');
    }

    public function testGeneratePublicSessionId()
    {
        $sid = \XLite\Core\Database::getRepo('XLite\Model\Session')->generatePublicSessionId();

        $this->assertEquals(32, strlen($sid), 'check length');
        $this->assertEquals(0, \XLite\Core\Database::getRepo('XLite\Model\Session')->countBySid($sid), 'checko unique');
    }

    public function testIsPublicSessionIdValid()
    {
        $sid = \XLite\Core\Database::getRepo('XLite\Model\Session')->generatePublicSessionId();

        $this->assertTrue(\XLite\Core\Database::getRepo('XLite\Model\Session')->isPublicSessionIdValid($sid), 'check validation');

        $sid = array($sid);
        $this->assertFalse(\XLite\Core\Database::getRepo('XLite\Model\Session')->isPublicSessionIdValid($sid), 'check validation (fail)');

        $sid = substr(\XLite\Core\Database::getRepo('XLite\Model\Session')->generatePublicSessionId(), 1);
        $this->assertFalse(\XLite\Core\Database::getRepo('XLite\Model\Session')->isPublicSessionIdValid($sid), 'check validation (fail) #2');

        $sid .= '!';
        $this->assertFalse(\XLite\Core\Database::getRepo('XLite\Model\Session')->isPublicSessionIdValid($sid), 'check validation (fail) #3');
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
