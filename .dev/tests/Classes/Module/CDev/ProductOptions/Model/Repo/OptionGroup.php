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

class XLite_Tests_Module_CDev_ProductOptions_Model_Repo_OptionGroup extends XLite_Tests_TestCase
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

    public function testFindActiveByProductId()
    {
        $group = $this->getTestGroup();

        $groups = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
            ->findActiveByProductId($group->getProduct()->getProductId());

        $this->assertEquals(1, count($groups), 'check groups count');
        $this->assertEquals($group->getGroupId(), $groups[0]->getGroupId(), 'check group id');

        $group->setEnabled(false);

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $groups = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
            ->findActiveByProductId($group->getProduct()->getProductId());

        $this->assertEquals(0, count($groups), 'check groups count again');

        $group->setEnabled(true);

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $groups = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
            ->findActiveByProductId($group->getProduct()->getProductId());

        $this->assertEquals(1, count($groups), 'check groups count #3');

        foreach ($group->getOptions() as $option) {
            \XLite\Core\Database::getEM()->remove($option);
        }
        $group->getOptions()->clear();
        \XLite\Core\Database::getEM()->flush();

        $groups = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
            ->findActiveByProductId($group->getProduct()->getProductId());

        $this->assertEquals(0, count($groups), 'check groups count #4');
    }

    public function testFindOneByGroupIdAndProductId()
    {
        $group = $this->getTestGroup();

        $pid = $group->getProduct()->getProductId();
        $g = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
            ->findOneByGroupIdAndProductId($group->getGroupId(), $pid);

        $this->assertNotNull($g, 'check new group');
        $this->assertEquals($group->getGroupId(), $g->getGroupid(), 'check group id');

        $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findFrame(2, 1);
        $product = array_shift($list);

        $group->getProduct()->getOptionGroups()->removeElement($group);
        $group->setProduct($product);
        $product->getOptionGroups()->add($group);

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $g = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
            ->findOneByGroupIdAndProductId($group->getGroupId(), $pid);

        $this->assertNull($g, 'check empty group');
    }

    public function testGetOptionGroupTypes()
    {
        $data = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
            ->getOptionGroupTypes();

        $etalon = array(
            'g' => array(
                'name'  => 'Options group',
                'views' => array(
                    's' => array(
                        'name' => 'Select box',
                    ),
                    'r' => array(
                        'name' => 'Radio buttons list',
                    ),
                ),
            ),
            't' => array(
                'name'  => 'Text option',
                'views' => array(
                    't' => array(
                        'name' => 'Text area',
                    ),
                    'i' => array(
                        'name' => 'Input box',
                    ),
                ),
            ),
        );

        $this->assertEquals($etalon, $data, 'comarision');
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
