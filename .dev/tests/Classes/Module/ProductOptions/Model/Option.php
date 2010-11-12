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

class XLite_Tests_Module_ProductOptions_Model_Option extends XLite_Tests_TestCase
{
    protected $product;

    protected $testGroup = array(
        'name'      => 'Test name',
        'fullname'  => 'Test full name',
        'orderby'   => 10,
        'type'      => \XLite\Module\ProductOptions\Model\OptionGroup::GROUP_TYPE,
        'view_type' => \XLite\Module\ProductOptions\Model\OptionGroup::SELECT_VISIBLE,
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

        $this->assertEquals(3, count($group->getOptions()), 'Check options count');

        $options = $group->getOptions();
        $option = $options[0];

        foreach ($this->testOption as $k => $v) {
            $m = 'get' . \XLite\Core\Converter::convertToCamelCase($k);
            $this->assertEquals($v, $option->$m(), 'Check ' . $k . ' (option)');
        }

        $this->assertEquals(10, $options[2]->getSurcharge('price')->getModifier(), 'Check modifier');
        $this->assertEquals('$', $options[2]->getSurcharge('price')->getModifierType(), 'Check modifier type');

        $this->assertEquals(1, $options[2]->getExceptions()->count(), 'Check exceptions'); 
    }

    public function testUpdate()
    {
        $group = $this->getTestGroup();

        $group->getOptions()->get(2)->setName('o0');
        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals('o0', $group->getOptions()->get(2)->getName(), 'Check name');

        $s = $group->getOptions()->get(2)->getSurcharge('price');
        $group->getOptions()->get(2)->getSurcharges()->removeElement($s);
        \XLite\Core\Database::getEM()->remove($s);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(0, $group->getOptions()->get(2)->getSurcharges()->count(), 'Check surcharges count');

        $list = $group->getOptions()->get(2)->getExceptions();
        foreach ($list as $i) {
            \XLite\Core\Database::getEM()->remove($i);
        }
        $list->clear();
        $group->getOptions()->get(2)->setExceptions($list);

        $list = $group->getOptions()->get(2)->getSurcharges();
        foreach ($list as $i) {
            \XLite\Core\Database::getEM()->remove($i);
        }
        $list->clear();
        $group->getOptions()->get(2)->setSurcharges($list);

        $list = $group->getOptions()->get(2)->getTranslations();
        foreach ($list as $i) {
            \XLite\Core\Database::getEM()->remove($i);
        }
        $list->clear();
        $group->getOptions()->get(2)->setTranslations($list);

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $option = $group->getOptions()->get(2);

        $this->assertEquals(0, $option->getSurcharges()->count(), 'Check surcharges count (empty)');
        $this->assertEquals(0, $option->getExceptions()->count(), 'Check exceptions count (empty)');
        $this->assertEquals(0, $option->getTranslations()->count(), 'Check translations count (empty)');
    }

    public function testDelete()
    {
        $group = $this->getTestGroup();

        $option = $group->getOptions()->get(0);
        $id = $option->getOptionId();

        $group->getOptions()->removeElement($option);
        \XLite\Core\Database::getEM()->remove($option);

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $group = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\Option')
            ->find($id);

        $this->assertNull($group, 'Check removed option');
    }

    public function testMultilanguage()
    {
        $group = $this->getTestGroup();

        $option = $group->getOptions()->get(0);

        $t = new \XLite\Module\ProductOptions\Model\OptionTranslation();
        $t->setOwner($option);
        $t->setCode('de');
        $t->setName('de1');
        $option->addTranslations($t);

        $t = new \XLite\Module\ProductOptions\Model\OptionTranslation();
        $t->setOwner($option);
        $t->setCode('ru');
        $t->setName('ru1');
        $option->addTranslations($t);

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $list = $option->getTranslations();

        $this->assertEquals(3, count($list), 'check translations length');

        $list->removeElement($t);

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $list = $option->getTranslations();

        $this->assertEquals(2, count($list), 'check translations length again');

        $t = $option->getTranslation('de');

        $this->assertNotEquals($t->getLabelId(), $option->getOptionId(), 'check translation label id');
        $this->assertEquals($t->getOwner()->getOptionId(), $option->getOptionId(), 'check translation owner id (again)');

    }

    public function testGetSurcharge()
    {
        $group = $this->getTestGroup();

        $option = $group->getOptions()->get(2);

        $this->assertEquals(10, $option->getSurcharge('price')->getModifier(), 'Check modifier');
        $this->assertEquals('$', $option->getSurcharge('price')->getModifierType(), 'Check modifier type');
 
        $this->assertNull($option->getSurcharge('weight'), 'Check empty modifier');
    }

    public function testHasActiveSurcharge()
    {
        $group = $this->getTestGroup();

        $option = $group->getOptions()->get(2);

        $this->assertTrue($option->hasActiveSurcharge('price'), 'Check active surcharge');
        $this->assertFalse($option->getSurcharge('price')->isEmpty(), 'check surcharge object not empty');

        $option->getSurcharge('price')->setModifier(0);

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $this->assertNotNull($option->getSurcharge('price'), 'check surcharge object type');
        $this->assertFalse($option->hasActiveSurcharge('price'), 'Check active surcharge #2');
        $this->assertTrue($option->getSurcharge('price')->isEmpty(), 'check surcharge object empty');

        $s = $option->getSurcharge('price');

        $option->getSurcharges()->removeElement($s);
        \XLite\Core\Database::getEM()->remove($s);

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $this->assertNull($option->getSurcharge('price'), 'check surcharge object type #2');
        $this->assertFalse($option->hasActiveSurcharge('price'), 'Check active surcharge #3');
    }

    public function testIsModifier()
    {
        $group = $this->getTestGroup();

        $option = $group->getOptions()->get(2);

        $this->assertTrue($option->isModifier(), 'check modifier status');

        $option->getSurcharge('price')->setModifier(0.01);
        $option->getSurcharge('price')->setModifierType('%');

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $this->assertFalse($option->isModifier(), 'check modifier status (failed)');
    }

    public function testGetNotEmptyModifiers()
    {
        $group = $this->getTestGroup();

        $option = $group->getOptions()->get(2);

        $this->assertEquals(1, count($option->getNotEmptyModifiers()), 'check modifiers list');

        $option->getSurcharge('price')->setModifier(0.01);
        $option->getSurcharge('price')->setModifierType('%');

        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(0, count($option->getNotEmptyModifiers()), 'check modifiers list (empty)');
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
        $group = new \XLite\Module\ProductOptions\Model\OptionGroup();

        $group->setProduct($this->getProduct());
        $this->getProduct()->addOptionGroups($group);

        $group->map($this->testGroup);

        $option = new \XLite\Module\ProductOptions\Model\Option();
        $option->setGroup($group);
        $group->addOptions($option);

        $option->map($this->testOption);

        $option = new \XLite\Module\ProductOptions\Model\Option();
        $option->setGroup($group);
        $group->addOptions($option);

        $option->map($this->testOption);
        $option->setName('o2');

        $option = new \XLite\Module\ProductOptions\Model\Option();
        $option->setGroup($group);
        $group->addOptions($option);

        $option->map($this->testOption);
        $option->setName('o3');

        $s = new \XLite\Module\ProductOptions\Model\OptionSurcharge();
        $s->setOption($option);
        $s->setType('price');
        $s->setModifier(10);
        $s->setModifierType('$');

        $option->addSurcharges($s);

        $e = new \XLite\Module\ProductOptions\Model\OptionException();
        $e->setOption($option);
        $e->setExceptionId(
            \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionException')
            ->getNextExceptionId()
        );

        $option->addExceptions($e);
        
        \XLite\Core\Database::getEM()->persist($group);
        \XLite\Core\Database::getEM()->flush();

        return $group;
    }
}
