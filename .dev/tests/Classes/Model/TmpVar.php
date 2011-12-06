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

class XLite_Tests_Model_TmpVar extends XLite_Tests_TestCase
{
    protected $entityData = array(
        'name'  => 'test',
        'value' => 'value',
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
        $c = new \XLite\Model\TmpVar();

        $c->map($this->entityData);


        \XLite\Core\Database::getEM()->persist($c);
        \XLite\Core\Database::getEM()->flush();

        $this->assertTrue(0 < $c->getId(), 'check id');

        $id = $c->getId();

        \XLite\Core\Database::getEM()->clear();

        $c = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->find($id);

        $this->assertEquals($c->getName(), $this->entityData['name'], 'check name');
        $this->assertEquals($c->getValue(), $this->entityData['value'], 'check value');
        \XLite\Core\Database::getEM()->remove($c);
        \XLite\Core\Database::getEM()->flush();
    }

    public function testUpdate()
    {
        foreach (\XLite\Core\Database::getRepo('XLite\Model\TmpVar')->findAll() as $c) {
            \XLite\Core\Database::getEM()->remove($c);
        }
        \XLite\Core\Database::getEM()->flush();

        $c = new \XLite\Model\TmpVar();

        $c->map($this->entityData);

        \XLite\Core\Database::getEM()->persist($c);
        \XLite\Core\Database::getEM()->flush();

        $c->setName('zzz');

        $id = $c->getId();

        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        $c = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->find($id);

        $this->assertEquals('zzz', $c->getName(), 'check name');
        \XLite\Core\Database::getEM()->remove($c);
        \XLite\Core\Database::getEM()->flush();
    }

    public function testRemove()
    {
        foreach (\XLite\Core\Database::getRepo('XLite\Model\TmpVar')->findAll() as $c) {
            \XLite\Core\Database::getEM()->remove($c);
        }
        \XLite\Core\Database::getEM()->flush();

        $c = new \XLite\Model\TmpVar();

        $c->map($this->entityData);

        \XLite\Core\Database::getEM()->persist($c);
        \XLite\Core\Database::getEM()->flush();

        $id = $c->getId();

        \XLite\Core\Database::getEM()->remove($c);

        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        $c = \XLite\Core\Database::getRepo('XLite\Model\TmpVar')->find($id);

        $this->assertTrue(is_null($c), 'check entity');
        //\XLite\Core\Database::getEM()->remove($c);
        \XLite\Core\Database::getEM()->flush();
    }

}
