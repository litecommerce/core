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
 */

class XLite_Tests_Model_Language extends XLite_Tests_TestCase
{
    protected $entityData = array(
        'code'   => 'zz',
        'code3'  => 'zzz',
        'r2l'    => true,
        'status' => 1,
        'name'   => 'test name',
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
        $c = new \XLite\Model\Language();

        foreach ($this->entityData as $field => $testValue) {
            $setterMethod = 'set' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $c->$setterMethod($testValue);
            $value = $c->$getterMethod();
            $this->assertEquals($testValue, $value, 'Creation checking (' . $field . ')');
        }
        $em = \XLite\Core\Database::getEM();
        $em->persist($c);
        $em->flush();

        $this->assertTrue(0 < $c->getLngId(), 'check language id');
        $em->remove($c);
        $em->flush($c);
    }

    public function testgetAdded()
    {
        $c = new \XLite\Model\Language();
        $c->map($this->entityData);

        $c->setStatus(0);
        $this->assertFalse($c->getAdded(), 'check inactive');

        $c->setStatus(1);
        $this->assertTrue($c->getAdded(), 'check added');

        $c->setStatus(2);
        $this->assertTrue($c->getAdded(), 'check enabled');
    }

    public function testsetAdded()
    {
        $c = new \XLite\Model\Language();
        $c->map($this->entityData);

        $c->setAdded(true);
        $this->assertEquals(1, $c->getStatus(), 'check added');

        $c->setAdded(false);
        $this->assertEquals(0, $c->getStatus(), 'check inactive');

        $c->setEnabled(true);
        $c->setAdded(true);
        $this->assertEquals(2, $c->getStatus(), 'check added #2');

        $c->setAdded(false);
        $this->assertEquals(0, $c->getStatus(), 'check added #3');
    }

    public function testgetEnabled()
    {
        $c = new \XLite\Model\Language();
        $c->map($this->entityData);

        $c->setStatus(0);
        $this->assertFalse($c->getEnabled(), 'check inactive');

        $c->setStatus(1);
        $this->assertFalse($c->getEnabled(), 'check added');

        $c->setStatus(2);
        $this->assertTrue($c->getEnabled(), 'check enabled');
    }

    public function testsetEnabled()
    {
        $c = new \XLite\Model\Language();
        $c->map($this->entityData);

        $c->setEnabled(false);
        $this->assertEquals(1, $c->getStatus(), 'check added');

        $c->setEnabled(true);
        $this->assertEquals(2, $c->getStatus(), 'check enabled');
    }

    public function testgetFlagURL()
    {
        $l = \XLite\Core\Database::getRepo('XLite\Model\Language')
            ->findOneBy(array('code' => 'uk'));

        $flag = $l->getFlagURL();
        $this->assertRegExp('/.images.flags.uk\.png$/Ss', $l->getFlagURL(), 'UK flag');

        $l = \XLite\Core\Database::getRepo('XLite\Model\Language')
            ->findOneBy(array('code' => 'aa'));

        $this->assertNull($l->getFlagURL(), 'AA flag');

    }
}
