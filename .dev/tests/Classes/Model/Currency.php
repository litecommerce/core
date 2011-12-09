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

/**
 * XLite_Tests_Model_Currency
 *
 * @see   ____class_see____
 * @since 1.0.13
 * @resource order
 * @resource currency
 */
class XLite_Tests_Model_Currency extends XLite_Tests_Model_OrderAbstract
{
    /**
     * testData
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.13
     */
    protected $testData = array(
        'currency_id' => 999,
        'code' => 'XXX',
        'symbol' => 'x',
        'e' => 3,
        'name' => 'Test',
    );

    /**
     * @var XLite\Model\Currency
     */
    protected $currency;

    /**
     * testCreate
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.13
     */
    public function testCreate()
    {
        $c = $this->currency;

        $this->assertTrue(0 < $c->getCurrencyId(), 'check currency id');

        foreach ($this->testData as $k => $v) {
            $m = 'get' . \XLite\Core\Converter::convertToCamelCase($k);
            $this->assertEquals($v, $c->$m(), 'Check ' . $k);
        }

        $o = $this->getTestOrder();
        $o->setCurrency($c);
        $c->addOrders($o);

        $this->assertEquals($o, $c->getOrders()->get(0), 'check order');

        try {
            $this->getTestCurrency();
            $this->fail('check code unique failed');

        } catch (\PDOException $e) {
            $this->assertRegExp(
                '/SQLSTATE\[23000\]/',
                $e->getMessage(),
                'check code unique'
            );
        }
    }

    /**
     * testUpdate
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.13
     */
    public function testUpdate()
    {
        $c = $this->currency;

        $c->setName('Test 2');
        $c->setCode('ZZZ');

        \XLite\Core\Database::getEM()->persist($c);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        $c = \XLite\Core\Database::getRepo('XLite\Model\Currency')->find($c->getCurrencyId());

        $this->assertEquals('Test 2', $c->getName(), 'check new name');
        $this->assertEquals('ZZZ', $c->getCode(), 'check new code');
    }

    /**
     * testDelete
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.13
     */
    public function testDelete()
    {
        $c = $this->currency;

        $id = $c->getCurrencyId();

        \XLite\Core\Database::getEM()->remove($c);
        \XLite\Core\Database::getEM()->flush();

        $c = \XLite\Core\Database::getRepo('XLite\Model\Currency')
            ->find($id);

        $this->assertNull($c, 'check removed currency');
    }

    /**
     * testRoundValue
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.13
     */
    public function testRoundValue()
    {
        $c = $this->currency;

        $this->assertEquals(2.555, $c->roundValue(2.5549), 'check round');

        $c->setE(2);
        $this->assertEquals(2.55, $c->roundValue(2.5549), 'check round #2');

        $c->setE(1);
        $this->assertEquals(2.6, $c->roundValue(2.5549), 'check round #3');

        $c->setE(0);
        $this->assertEquals(3, $c->roundValue(2.5549), 'check round #4');
    }

    /**
     * testRoundValueAsInteger
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.13
     */
    public function testRoundValueAsInteger()
    {
        $c = $this->currency;

        $this->assertEquals(2555, $c->roundValueAsInteger(2.5549), 'check round');

        $c->setE(2);
        $this->assertEquals(255, $c->roundValueAsInteger(2.5549), 'check round #2');

        $c->setE(1);
        $this->assertEquals(26, $c->roundValueAsInteger(2.5549), 'check round #3');

        $c->setE(0);
        $this->assertEquals(3, $c->roundValueAsInteger(2.5549), 'check round #4');
    }

    /**
     * testConvertIntegerToFloat
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.13
     */
    public function testConvertIntegerToFloat()
    {
        $c = $this->currency;

        $this->assertEquals(2.555, $c->convertIntegerToFloat(2555), 'check round');

        $c->setE(2);
        $this->assertEquals(2.55, $c->convertIntegerToFloat(255), 'check round #2');

        $c->setE(1);
        $this->assertEquals(2.6, $c->convertIntegerToFloat(26), 'check round #3');

        $c->setE(0);
        $this->assertEquals(3, $c->convertIntegerToFloat(3), 'check round #4');
    }

    /**
     * testFormatValue
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.13
     */
    public function testFormatValue()
    {
        $c = $this->currency;

        $d = \XLite\Core\Config::getInstance()->General->decimal_delim;
        $t = \XLite\Core\Config::getInstance()->General->thousand_delim;

        $this->assertEquals('1' . $t . '002' . $d . '555', $c->formatValue(1002.5549), 'check format');

        $c->setE(2);
        $this->assertEquals('1' . $t . '002' . $d . '55', $c->formatValue(1002.5549), 'check format #2');

        $c->setE(1);
        $this->assertEquals('1' . $t . '002' . $d . '6', $c->formatValue(1002.5549), 'check format #3');

        $c->setE(0);
        $this->assertEquals('1' . $t . '003', $c->formatValue(1002.5549), 'check format #4');
    }

    protected function setUp()
    {
        parent::setUp();
        $c = \XLite\Core\Database::getRepo("XLite\Model\Currency")->find(999);
        if ($c) {
            \XLite\Core\Database::getEM()->remove($c);
            \XLite\Core\Database::getEM()->flush();
        }
        $this->currency = $this->getTestCurrency();
    }

    protected function tearDown()
    {
        $this->clearEntity($this->order);
        $this->clearEntity($this->currency);
        parent::tearDown();
    }

    /**
     * getTestCurrency
     *
     * @return XLite\Model|Currency
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function getTestCurrency()
    {
        $c = new \XLite\Model\Currency();

        $c->map($this->testData);

        \XLite\Core\Database::getEM()->persist($c);
        \XLite\Core\Database::getEM()->flush();

        return $c;
    }
}
