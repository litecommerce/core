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
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

class XLite_Tests_Model_FormId extends XLite_Tests_TestCase
{
    protected $testSession = array(
        'sid'    => '12345678901234567890123456789012',
        'expiry' => 1602488245, // 2020 year
    );

    protected $formId;

    public function testCreate()
    {
        list($formId, $session) = $this->getTestFormId();

        $this->assertEquals($session->getId(), $formId->getSessionId(), 'check session id');
        $this->assertRegExp('/^[a-z0-9]{32}$/Ssi', $formId->getFormId(), 'check form id');

        $date = $formId->getDate();
        $formId->setDate(1000);

        $this->assertEquals($date, $formId->getDate(), 'check date (readonly)');

        $id = $formId->getFormId();
        $formId->setFormId(str_repeat('a', 32));

        $this->assertEquals($id, $formId->getFormId(), 'check form id (readonly)');
    }

    public function testDelete()
    {
        list($formId, $session) = $this->getTestFormId();

        $id = $formId->getId();

        \XLite\Core\Database::getEM()->remove($formId);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        $formId = \XLite\Core\Database::getRepo('XLite\Model\FormId')->find($id);
        $this->assertNull($formId, 'not exists form id');
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
        $this->formId = $formId;

        return array($formId, $session);
    }
    protected function tearDown(){
        $this->clearEntity($this->formId);
        parent::tearDown();
    }
}
