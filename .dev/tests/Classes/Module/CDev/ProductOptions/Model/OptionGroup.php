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

class XLite_Tests_Module_CDev_ProductOptions_Model_OptionGroup extends XLite_Tests_TestCase
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

        $this->assertTrue(0 < $group->getGroupId(), 'check group id type');

        foreach ($this->testGroup as $k => $v) {
            $m = 'get' . \XLite\Core\Converter::convertToCamelCase($k);
            $this->assertEquals($v, $group->$m(), 'Check ' . $k);
        }

        $this->assertEquals($this->getProduct()->getProductId(), $group->getProduct()->getProductId(), 'check product id');

        $this->assertEquals(1, count($group->getOptions()), 'Check options count');

        $options = $group->getOptions();
        $option = $options[0];

        foreach ($this->testOption as $k => $v) {
            $m = 'get' . \XLite\Core\Converter::convertToCamelCase($k);
            $this->assertEquals($v, $option->$m(), 'Check ' . $k . ' (option)');
        }

    }

    public function testUpdate()
    {
        $group = $this->getTestGroup();

        $group->setType($group::TEXT_TYPE);
        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals($group::TEXT_TYPE, $group->getType(), 'Check new type');

        $group = \XLite\Core\Database::getRepo('XLite\Module\CDev\ProductOptions\Model\OptionGroup')
            ->find($group->getGroupId());

        $list = $group->getOptions();
        $option = new XLite\Module\CDev\ProductOptions\Model\Option();
        $option->setGroup($group);
        $list[] = $option;

        $option->map($this->testOption);
        $option->setName('o5');

        \XLite\Core\Database::getEM()->persist($option);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(2, count($group->getOptions()), 'Check options count');
    }

    public function testDelete()
    {
        $group = $this->getTestGroup();

        $id = $group->getGroupId();

        \XLite\Core\Database::getEM()->remove($group);
        \XLite\Core\Database::getEM()->flush();

        $group = \XLite\Core\Database::getRepo('XLite\Module\CDev\ProductOptions\Model\OptionGroup')
            ->find($id);

        $this->assertNull($group, 'Check removed group');
    }

    public function testMultilanguage()
    {
        $group = $this->getTestGroup();

        $t = new XLite\Module\CDev\ProductOptions\Model\OptionGroupTranslation();
        $t->setOwner($group);
        $t->setCode('de');
        $t->setName('de1');
        $group->addTranslations($t);

        $t = new XLite\Module\CDev\ProductOptions\Model\OptionGroupTranslation();
        $t->setOwner($group);
        $t->setCode('ru');
        $t->setName('ru1');
        $group->addTranslations($t);

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $list = $group->getTranslations();

        $this->assertEquals(3, count($list), 'check translations length');

        \XLite\Core\Database::getEM()->remove($t);
        $list->removeElement($t);

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $t = $group->getTranslation('de');

        $this->assertNotEquals($t->getLabelId(), $group->getGroupId(), 'check translation label id');
        $this->assertEquals($t->getOwner()->getGroupId(), $group->getGroupId(), 'check translation owner id (again)');

        $this->assertEquals(2, count($list), 'check translations length again');
    }

    public function testSetType()
    {
        $group = $this->getTestGroup();

        $this->assertTrue($group->setType($group::TEXT_TYPE), 'Check text type');
        $this->assertFalse($group->setType('z'), 'Check wrong type');

        $oldViewType = $group->getViewType();
        $this->assertTrue($group->setType($group::GROUP_TYPE), 'Check text type');
        $this->assertNotEquals($oldViewType, $group->getViewType(), 'Check view type change');
    }

    public function testSetViewType()
    {
        $group = $this->getTestGroup();

        $this->assertTrue($group->setViewType($group::SELECT_VISIBLE), 'Check view type');
        $this->assertFalse($group->setViewType('z'), 'Check wrong view type');
        $this->assertFalse($group->setViewType($group::INPUT_VISIBLE), 'Check wrong view type');

        $this->assertTrue($group->setType($group::TEXT_TYPE), 'Check text type');
        $this->assertTrue($group->setViewType($group::INPUT_VISIBLE), 'Check view type (again)');

    }

    public function testGetDisplayName()
    {
        $group = $this->getTestGroup();

        $group->setName('n1');
        $group->setFullname('n2');

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals('n2', $group->getDisplayName(), 'Get display name from full name');

        $group->setFullname('');

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals('n1', $group->getDisplayName(), 'Get display name from name');
    }

    public function testGetActiveOptions()
    {
        $group = $this->getTestGroup();

        $option = new XLite\Module\CDev\ProductOptions\Model\Option();
        $option->setGroup($group);
        $group->addOptions($option);

        $option->map($this->testOption);

        $option->setName('o2');

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $options = $group->getActiveOptions();
        
        $this->assertEquals(2, count($options), 'check option count');

        $option->setEnabled(false);

        \XLite\Core\Database::getEM()->persist($option);
        \XLite\Core\Database::getEM()->flush();

        $options = $group->getActiveOptions();

        $this->assertEquals(1, count($options), 'check option count again');

        $this->assertNotEquals($option->getOptionId(), $options[0]->getOptionId(), 'check option id');
    }

    public function testGetDefaultOption()
    {
        $group = $this->getTestGroup();

        $option = new XLite\Module\CDev\ProductOptions\Model\Option();
        $option->setGroup($group);
        $group->addOptions($option);

        $option->map($this->testOption);

        $option->setName('o3');

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        foreach ($group->getOptions() as $option) {
            $option->setEnabled(true);
        }

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $options = $group->getActiveOptions();

        $this->assertEquals($options[0]->getOptionId(), $group->getDefaultOption()->getOptionId(), 'check option id');
        $this->assertEquals($options[1]->getOptionId(), $group->getDefaultOption(1)->getOptionId(), 'check option id 2');

        foreach ($group->getOptions() as $option) {
            \XLite\Core\Database::getEM()->remove($option);
        }
        $group->getOptions()->clear();

        \XLite\Core\Database::getEM()->flush();

        $this->assertNull($group->getDefaultOption(), 'check empty default option');
    }

    public function testGetDefaultPlainValue()
    {
        $group = $this->getTestGroup();

        $option = new XLite\Module\CDev\ProductOptions\Model\Option();
        $option->setGroup($group);
        $group->addOptions($option);

        $option->map($this->testOption);

        $option->setName('o4');

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        foreach ($group->getOptions() as $option) {
            $option->setEnabled(true);
        }

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $options = $group->getActiveOptions();

        $this->assertEquals($options[0]->getOptionId(), $group->getDefaultPlainValue(), 'check option id');

        $group->setType($group::TEXT_TYPE);
        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals('', $group->getDefaultPlainValue(), 'check empty string');

        $group->setType($group::GROUP_TYPE);
        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        foreach ($group->getOptions() as $option) {
            \XLite\Core\Database::getEM()->remove($option);
        }
        $group->getOptions()->clear();

        \XLite\Core\Database::getEM()->flush();

        $this->assertNull($group->getDefaultPlainValue(), 'check empty options list');
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

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        return $group;
    }
}
