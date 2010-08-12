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

class XLite_Tests_Module_ProductOptions_Model_Repo_OptionException extends XLite_Tests_TestCase
{
    protected $product;

    protected $testGroups = array(
        array(
            'name'      => 'Color',
            'fullname'  => 'Color',
            'orderby'   => 10,
            'type'      => \XLite\Module\ProductOptions\Model\OptionGroup::GROUP_TYPE,
            'view_type' => \XLite\Module\ProductOptions\Model\OptionGroup::SELECT_VISIBLE,
            'cols'      => 11,
            'rows'      => 12,
            'enabled'   => true,
        ),
        array(
            'name'      => 'Size',
            'fullname'  => 'Size',
            'orderby'   => 10,
            'type'      => \XLite\Module\ProductOptions\Model\OptionGroup::GROUP_TYPE,
            'view_type' => \XLite\Module\ProductOptions\Model\OptionGroup::SELECT_VISIBLE,
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

    public function testCheckOptions()
    {
        $this->getTestGroups();

        $repo = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionException');

        $ids = array(
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(0)->getOptionId(),
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->getOptionId(),
        );

        $this->assertFalse($repo->checkOptions($ids), 'check options (failed)');

        $eid = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionException')->getNextExceptionId();
        $e = new \XLite\Module\ProductOptions\Model\OptionException();
        $e->setOption(
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(2)
        );
        $e->setExceptionId($eid);
        $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(2)->addExceptions($e);

        \XLite\Core\Database::getEM()->persist($this->getProduct()->getOptionGroups()->get(0));
        \XLite\Core\Database::getEM()->flush();

        $ids = array(
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(2)->getOptionId(),
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->getOptionId(),
        );

        $this->assertFalse($repo->checkOptions($ids), 'check options (failed #2)');

        $ids = array(
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(0)->getOptionId(),
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(0)->getOptionId(),
        );

        $this->assertTrue($repo->checkOptions($ids), 'check options');
    }

    public function testGetNextExceptionId()
    {
        $this->getTestGroups();

        $repo = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionException');

        $e = $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->getExceptions()->get(0);

        $this->assertEquals($e->getExceptionId() + 1, $repo->getNextExceptionId(), 'check next id');

        $eid = $repo->getNextExceptionId();
        $e2 = new \XLite\Module\ProductOptions\Model\OptionException();
        $e2->setOption(
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)
        );
        $e2->setExceptionId($repo->getNextExceptionId());
        $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->addExceptions($e2);
        $e2 = new \XLite\Module\ProductOptions\Model\OptionException();
        $e2->setOption(
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(2)
        );
        $e2->setExceptionId($repo->getNextExceptionId());
        $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(2)->addExceptions($e2);

        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals($e->getExceptionId() + 2, $repo->getNextExceptionId(), 'check next id #2');
    }

    public function testFindByExceptionId()
    {
        $this->getTestGroups();

        $repo = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionException');

        $e = $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->getExceptions()->get(0);

        $list = $repo->findByExceptionId($e->getExceptionId());

        $this->assertEquals(2, count($list), 'check list count');

        $list = array(
            $list[0]->getOption()->getOptionId(),
            $list[1]->getOption()->getOptionId(),
        );
        sort($list);

        $etalon = array(
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->getOptionId(),
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->getOptionId(),
        );
        sort($etalon);

        $this->assertEquals($list, $etalon, 'check array');
    }

    public function testFindByExceptionIds()
    {
        $this->getTestGroups();

        $repo = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionException');

        $e = $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->getExceptions()->get(0);
        $e2 = $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(0)->getExceptions()->get(0);

        $list = $repo->findByExceptionIds(array($e->getExceptionId(), $e2->getExceptionId(),));

        $this->assertEquals(4, count($list), 'check list count');

        $list = array(
            $list[0]->getOption()->getOptionId(),
            $list[1]->getOption()->getOptionId(),
            $list[2]->getOption()->getOptionId(),
            $list[3]->getOption()->getOptionId(),
        );

        $list = array_values(array_unique($list));
        sort($list);

        $etalon = array(
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->getOptionId(),
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->getOptionId(),
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(0)->getOptionId(),
        );
        sort($etalon);

        $this->assertEquals($list, $etalon, 'check array');
    }

    protected function getProduct()
    {
        if (!isset($this->product)) {
            $list = \XLite\Core\Database::getRepo('XLite\Model\Product')->findFrame(1, 1);

            $this->product = array_shift($list);
            foreach ($list as $p) {
                $p->detach();
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
            $group = new \XLite\Module\ProductOptions\Model\OptionGroup();

            $group->setProduct($this->getProduct());
            $this->getProduct()->addOptionGroups($group);

            $group->map($data);

            foreach ($this->testOptions[$data['name']] as $opt) {
                $option = new \XLite\Module\ProductOptions\Model\Option();
                $option->setGroup($group);
                $group->addOptions($option);

                $option->map($opt);
            }

            \XLite\Core\Database::getEM()->persist($group);
        }

        $eid = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionException')->getNextExceptionId();
        $e = new \XLite\Module\ProductOptions\Model\OptionException();
        $e->setOption(
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(0)
        );
        $e->setExceptionId($eid);
        $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(0)->addExceptions($e);
        $e = new \XLite\Module\ProductOptions\Model\OptionException();
        $e->setOption(
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)
        );
        $e->setExceptionId($eid);
        $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->addExceptions($e);

        \XLite\Core\Database::getEM()->flush();

        $eid = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionException')->getNextExceptionId();
        $e = new \XLite\Module\ProductOptions\Model\OptionException();
        $e->setOption(
            $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)
        );
        $e->setExceptionId($eid);
        $this->getProduct()->getOptionGroups()->get(0)->getOptions()->get(1)->addExceptions($e);
        $e = new \XLite\Module\ProductOptions\Model\OptionException();
        $e->setOption(
            $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)
        );
        $e->setExceptionId($eid);
        $this->getProduct()->getOptionGroups()->get(1)->getOptions()->get(1)->addExceptions($e);

        \XLite\Core\Database::getEM()->flush();
    }
}
