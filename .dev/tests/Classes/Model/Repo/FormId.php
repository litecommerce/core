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

class XLite_Tests_Model_Repo_FormId extends XLite_Tests_TestCase
{
    protected $testSession = array(
        'sid'    => '12345678901234567890123456789012',
        'expiry' => 1602488245, // 2020 year
    );

    public function testCountByFormIdAndSessionId()
    {
        list($formId, $session) = $this->getTestFormId();
        $repo = \XLite\Core\Database::getRepo('XLite\Model\FormId');

        $this->assertEquals(
            0,
            $repo->countByFormIdAndSessionId(str_repeat('a', 32), $session->getId()),
            'check count - wrong form and right session'
        );
        $this->assertEquals(
            0,
            $repo->countByFormIdAndSessionId(str_repeat('a', 32), 999999),
            'check count - wrong form and wrong session'
        );
        $this->assertEquals(
            0,
            $repo->countByFormIdAndSessionId($formId->getFormId(), 999999),
            'check count - right form and wrong session'
        );
        $this->assertEquals(
            1,
            $repo->countByFormIdAndSessionId($formId->getFormId(), $session->getId()),
            'check count - right form and right session'
        );
        $this->assertEquals(
            0,
            $repo->countByFormIdAndSessionId($formId->getFormId()),
            'check count - right form and current session'
        );
    }

    public function testGenerateFormId()
    {
        list($formId, $session) = $this->getTestFormId();
        $repo = \XLite\Core\Database::getRepo('XLite\Model\FormId');

        $this->assertRegExp('/^[a-z0-9]{32}$/Ssi', $repo->generateFormId($session->getId()), 'cehck format');

        $this->assertEquals(
            0,
            $repo->countByFormIdAndSessionId($repo->generateFormId($session->getId()), $session->getId()),
            'check unique'
        );

        $this->assertNotequals(
            $repo->generateFormId($session->getId()),
            $repo->generateFormId($session->getId()),
            'check duplicate'
        );

        $this->assertNotequals(
            $repo->generateFormId($session->getId()),
            $repo->generateFormId(),
            'check duplicate (without session id)'
        );

    }

    public function testRemoveExpired()
    {
        list($formId, $session) = $this->getTestFormId();
        $repo = \XLite\Core\Database::getRepo('XLite\Model\FormId');

        $limit = 200;
        for ($i = 0; $i < $limit; $i++) {
            $formId = new \XLite\Model\FormId;
            $formId->setSessionId($session->getId());

            \XLite\Core\Database::getEM()->persist($formId);
        }

        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        $list = $repo->findBy(array('session_id' => $session->getId()));
        $this->assertEquals($limit + 1, count($list), 'check length');

        \XLite\Core\Database::getEM()->clear();

        $repo->removeExpired();
        $list = $repo->findBy(array('session_id' => $session->getId()));
        $this->assertEquals($limit + 1, count($list), 'check length with current session');

        \XLite\Core\Database::getEM()->clear();

        $repo->removeExpired($session->getId());

        $list = $repo->findBy(array('session_id' => $session->getId()));

        $this->assertEquals(101, count($list), 'check length after remove');
    }

    protected function getTestFormId()
    {
        $old = \XLite\Core\Database::getRepo('XLite\Model\Session')->findOneBy(
            array(
                'sid' => $this->testSession['sid'],
            )
        );
        if ($old) {
            \XLite\Core\Database::getEM()->remove($old);
            \XLite\Core\Database::getEM()->flush();
        }

        $session = new \XLite\Model\Session();

        $session->map($this->testSession);

        \XLite\Core\Database::getEM()->persist($session);
        \XLite\Core\Database::getEM()->flush();

        $formId = new \XLite\Model\FormId;
        $formId->setSessionId($session->getId());

        \XLite\Core\Database::getEM()->persist($formId);
        \XLite\Core\Database::getEM()->flush();


        return array($formId, $session);
    }
}
