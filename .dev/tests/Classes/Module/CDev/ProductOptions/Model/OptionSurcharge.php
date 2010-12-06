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

class XLite_Tests_Module_CDev_ProductOptions_Model_OptionSurcharge extends XLite_Tests_TestCase
{
    protected $product;

    protected $testGroup = array(
        'name'      => 'Test name',
        'fullname'  => 'Test full name',
        'orderby'   => 10,
        'type'      => XLite\Module\CDev\ProductOptions\Model\OptionGroup::GROUP_TYPE,
        'view_type' => XLite\Module\CDev\ProductOptions\Model\OptionGroup::SELECT_VISIBLE,
        'cols'      => 11,
        'rows'      => 12,
        'enabled'   => true,
    );

    protected $testOption = array(
        'name'      => 'Test option name',
        'orderby'   => 11,
        'enabled'   => true,
    );

    protected function setUp()
    {
        parent::setUp();

        \XLite\Core\Database::getEM()->clear();
    }

    public function testCreate()
    {
        $group = $this->getTestGroup();

        $list = $group->getOptions()->get(2)->getSurcharges();
        $this->assertEquals(1, count($list), 'Check surcharges count');

        $this->assertEquals('price', $list[0]->getType(), 'Check type'); 
        $this->assertEquals(10, $list[0]->getModifier(), 'Check modifier');                                      
        $this->assertEquals('$', $list[0]->getModifierType(), 'Check modifier type');

        $this->assertNotNull($list[0]->getSurchargeId(), 'Check id');
    }

    public function testUpdate()
    {
        $group = $this->getTestGroup();

        $list = $group->getOptions()->get(2)->getSurcharges();
        $list[0]->setModifier(20);

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(20, $list[0]->getModifier(), 'Check modifier');                                      
    }

    public function testDelete()
    {
        $group = $this->getTestGroup();

        $list = $group->getOptions()->get(2)->getSurcharges();
        foreach ($list as $i) {
            \XLite\Core\Database::getEM()->remove($i);
        }
        $list->clear();

        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(0, count($list), 'Check surcharges list');
    }

    public function testGetSign()
    {
        $group = $this->getTestGroup();

        $s = $group->getOptions()->get(2)->getSurcharges()->get(0);

        $this->assertEquals('+', $s->getSign(), '+ sign');

        $s->setModifier(-10);
        \XLite\Core\Database::getEM()->persist($s);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals('-', $s->getSign(), '- sign');

        $s->setModifier(0);
        \XLite\Core\Database::getEM()->persist($s);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals('', $s->getSign(), '0 sign');
    }

    public function testisEmpty()
    {
        $group = $this->getTestGroup();

        $s = $group->getOptions()->get(2)->getSurcharges()->get(0);

        $this->assertFalse($s->isEmpty(), 'not empty');

        $s->setModifier(0);
        \XLite\Core\Database::getEM()->persist($s);
        \XLite\Core\Database::getEM()->flush();

        $this->assertTrue($s->isEmpty(), 'empty');

        $s->setModifier(0.01);
        $s->setModifierType('%');
        \XLite\Core\Database::getEM()->persist($s);
        \XLite\Core\Database::getEM()->flush();

        $this->assertTrue($s->isEmpty(), 'empty #2');
    }

    public function testGetAbsoluteValue()
    {
        $group = $this->getTestGroup();

        $s = $group->getOptions()->get(2)->getSurcharges()->get(0);

        $this->assertEquals(10, $s->getAbsoluteValue(), '10');

        $s->setModifier(0);
        \XLite\Core\Database::getEM()->persist($s);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(0, $s->getAbsoluteValue(), '0');

        $s->setModifier(0.01);
        $s->setModifierType('%');
        \XLite\Core\Database::getEM()->persist($s);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(0, $s->getAbsoluteValue(), '0 #2');

        $s->setType('weight');
        $s->setModifier(20);
        $s->setModifierType('$');
        \XLite\Core\Database::getEM()->persist($s);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(20, $s->getAbsoluteValue(), '20');

        $s->setModifier(0.1);
        $s->setModifierType('%');
        \XLite\Core\Database::getEM()->persist($s);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(0, $s->getAbsoluteValue(), '0 #3');
    }

    protected function getProduct()
    {
        if (!isset($this->product)) {
            $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findFrame(1, 1);

            $this->product = array_shift($list);
            foreach ($list as $p) {
                $p->detach();
            }

            if (!$this->product) {
                $this->fail('Can not find enabled product');
            }

            // Clean option groups
            foreach ($this->product->getOptionGroups() as $group) {
                \XLite\Core\Database::getEM()->remove($group);
            }
            $this->product->getOptionGroups()->clear();
            \XLite\Core\Database::getEM()->flush();
        }

        return $this->product;
    }

    protected function getTestGroup()
    {
        $group = new XLite\Module\CDev\ProductOptions\Model\OptionGroup();

        $group->setProduct($this->getProduct());
        $this->getProduct()->addOptionGroups($group);

        $group->map($this->testGroup);

        $option = new XLite\Module\CDev\ProductOptions\Model\Option();
        $option->setGroup($group);
        $group->addOptions($option);

        $option->map($this->testOption);

        $option = new XLite\Module\CDev\ProductOptions\Model\Option();
        $option->setGroup($group);
        $group->addOptions($option);

        $option->map($this->testOption);
        $option->setName('o2');

        $option = new XLite\Module\CDev\ProductOptions\Model\Option();
        $option->setGroup($group);
        $group->addOptions($option);

        $option->map($this->testOption);
        $option->setName('o3');

        $s = new XLite\Module\CDev\ProductOptions\Model\OptionSurcharge();
        $s->setOption($option);
        $s->setType('price');
        $s->setModifier(10);
        $s->setModifierType('$');

        $option->addSurcharges($s);

        $e = new XLite\Module\CDev\ProductOptions\Model\OptionException();
        $e->setOption($option);
        $e->setExceptionId(
            \XLite\Core\Database::getRepo('XLite\Module\CDev\ProductOptions\Model\OptionException')
            ->getNextExceptionId()
        );

        $option->addExceptions($e);
        
        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        return $group;
    }
}
