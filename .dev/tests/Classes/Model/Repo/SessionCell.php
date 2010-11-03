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

class XLite_Tests_Model_Repo_SessionCell extends XLite_Tests_TestCase
{
    protected $testSession = array(
        'sid'    => '12345678901234567890123456789012',
        'expiry' => 1602488245, // 2020 year
    );

    public function testFindOneByIdAndName()
    {
        $session = $this->getTestSession();

        $cell = \XLite\Core\Database::getRepo('XLite\Model\SessionCell')->findOneBy(array('id' => $session->getId(), 'name' => 'aaa'));

        $this->assertNotNull($cell, 'check cell');
        $this->assertEquals(1, $cell->getValue(), 'check value');

        $cell = \XLite\Core\Database::getRepo('XLite\Model\SessionCell')->findOneBy(array('id' => $session->getId(), 'name' => 'bbb'));
        $this->assertNotNull($cell, 'check cell #2');
        $this->assertEquals(2, $cell->getValue(), 'check value #2');

        $cell = \XLite\Core\Database::getRepo('XLite\Model\SessionCell')->findOneBy(array('id' => 0, 'name' => 'aaa'));
        $this->assertNull($cell, 'check empty cell #1');

        $cell = \XLite\Core\Database::getRepo('XLite\Model\SessionCell')->findOneBy(array('id' => $session->getId(), 'name' => 'zzz'));
        $this->assertNull($cell, 'check empty cell #2');
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
