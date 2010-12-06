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

class XLite_Tests_Module_CDev_ProductOptions_Model_OptionException extends XLite_Tests_TestCase
{
    protected $product;

    protected $testGroups = array(
        array(
            'name'      => 'Color',
            'fullname'  => 'Color',
            'orderby'   => 10,
            'type'      => XLite\Module\CDev\ProductOptions\Model\OptionGroup::GROUP_TYPE,
            'view_type' => XLite\Module\CDev\ProductOptions\Model\OptionGroup::SELECT_VISIBLE,
            'cols'      => 11,
            'rows'      => 12,
            'enabled'   => true,
        ),
        array(
            'name'      => 'Size',
            'fullname'  => 'Size',
            'orderby'   => 10,
            'type'      => XLite\Module\CDev\ProductOptions\Model\OptionGroup::GROUP_TYPE,
            'view_type' => XLite\Module\CDev\ProductOptions\Model\OptionGroup::SELECT_VISIBLE,
            'cols'      => 11,
            'rows'      => 12,
            'enabled'   => true,
        ),
    );

    protected $testOptions = array(
        'Color' => array(
            array(
                'name'    => 'Red',
                'enabled' => true,
            ),
            array(
                'name'    => 'Green',
                'enabled' => true,
            ),
            array(
                'name'    => 'Blue',
                'enabled' => true,
            ),
        ),
        'Size' => array(
            array(
                'name'    => 'S',
                'enabled' => true,
            ),
            array(
                'name'    => 'M',
                'enabled' => true,
            ),
            array(
                'name'    => 'L',
                'enabled' => true,
            ),
        ),
    );



    protected function setUp()
    {
        parent::setUp();

        \XLite\Core\Database::getEM()->clear();
    }

    public function testCreate()
    {
        $this->getTestGroups();

        $this->assertEquals(
            1,
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(0)->getExceptions()->count(),
            'check exceptions count'
        );
        $this->assertEquals(
            1,
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->getExceptions()->count(),
            'check exceptions count #2'
        );
        $this->assertEquals(
            2,
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->getExceptions()->count(),
            'check exceptions count #3'
        );

        $this->assertEquals(
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->getExceptions()->get(0)->getExceptionId(),
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->getExceptions()->get(1)->getExceptionId(),
            'equals exception id'
        );

        $this->assertEquals(
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->getExceptions()->get(0)->getOption()->getOptionId(),
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->getOptionId(),
            'equals option id'
        );

        $this->assertNotNull(
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->getExceptions()->get(0)->getId(),
            'check id'
        );
    }

    public function testUpdate()
    {
        $this->getTestGroups();

        $e = $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->getExceptions()->get(0);
        $e->setOption($this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(2));
        $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->getExceptions()->clear();
        $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(2)->addExceptions($e);

        \XLite\Core\Database::getEM()->persist($this->getProduct()->getOptionGroups()->get(0));
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(
            0,
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->getExceptions()->count(),
            'check exceptions count'
        );

        $this->assertEquals(
            1,
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(2)->getExceptions()->count(),
            'check exceptions count'
        );
    }

    public function testDelete()
    {
        $this->getTestGroups();

        $list = $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->getExceptions();
        foreach ($list as $e) {
            \XLite\Core\Database::getEM()->remove($e);
        }
        $list->clear();
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(
            0,
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->getExceptions()->count(),
            'check exceptions count'
        );

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

            \XLite\Core\Database::getEM()->persist($this->product);
            \XLite\Core\Database::getEM()->flush();
        }

        return $this->product;
    }

    protected function getTestGroups()
    {
        foreach ($this->testGroups as $data) {
            $group = new XLite\Module\CDev\ProductOptions\Model\OptionGroup();

            $group->setProduct($this->getProduct());
            $this->getProduct()->addOptionGroups($group);

            $group->map($data);

            foreach ($this->testOptions[$data['name']] as $opt) {
                $option = new XLite\Module\CDev\ProductOptions\Model\Option();
                $option->setGroup($group);
                $group->addOptions($option);

                $option->map($opt);
            }

            \XLite\Core\Database::getEM()->persist($group);
        }

        $e = new XLite\Module\CDev\ProductOptions\Model\OptionException();
        $e->setOption(
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(0)
        );
        $e->setOption(
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)
        );
        $e->setExceptionId(
            \XLite\Core\Database::getRepo('XLite\Module\CDev\ProductOptions\Model\OptionException')
            ->getNextExceptionId()
        );

        $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(0)->addExceptions($e);
        $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->addExceptions($e);
        
        \XLite\Core\Database::getEM()->flush();

        $e = new XLite\Module\CDev\ProductOptions\Model\OptionException();
        $e->setOption(
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)
        );
        $e->setOption(
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)
        );
        $e->setExceptionId(
            \XLite\Core\Database::getRepo('XLite\Module\CDev\ProductOptions\Model\OptionException')
            ->getNextExceptionId()
        );

        $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->addExceptions($e);
        $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->addExceptions($e);

        \XLite\Core\Database::getEM()->flush();
    }
}
