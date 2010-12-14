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

class XLite_Tests_Model_Currency extends XLite_Tests_TestCase
{
    protected $testData = array(
        'code'   => 'XXX',
        'symbol' => 'x',
        'e'      => 3,
        'name'   => 'Test',
    );

    public function testCreate()
    {
        $c = $this->getTestCurrency();

        $this->assertTrue(0 < $c->getCurrencyId(), 'check currency id');

        foreach ($this->testData as $k => $v) {
            $m = 'get' . \XLite\Core\Converter::convertToCamelCase($k);
            $this->assertEquals($v, $c->$m(), 'Check ' . $k);
        }

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

    public function testUpdate()
    {
        $c = $this->getTestCurrency();

        $c->setName('Test 2');
        $c->setCode('ZZZ');

        \XLite\Core\Database::getEM()->persist($c);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        $c = \XLite\Core\Database::getRepo('XLite\Model\Currency')->find($c->getCurrencyId());

        $this->assertEquals('Test 2', $c->getName(), 'check new name');
        $this->assertEquals('ZZZ', $c->getCode(), 'check new code');
    }

    public function testDelete()
    {
        $c = $this->getTestCurrency();

        $id = $c->getCurrencyId();

        \XLite\Core\Database::getEM()->remove($c);
        \XLite\Core\Database::getEM()->flush();

        $c = \XLite\Core\Database::getRepo('XLite\Model\Currency')
            ->find($id);

        $this->assertNull($c, 'check removed currency');
    }

    public function testRoundValue()
    {
        $c = $this->getTestCurrency();

        $this->assertEquals(2.555, $c->roundValue(2.5549), 'check round');

        $c->setE(2);
        $this->assertEquals(2.55, $c->roundValue(2.5549), 'check round #2');

        $c->setE(1);
        $this->assertEquals(2.6, $c->roundValue(2.5549), 'check round #3');

        $c->setE(0);
        $this->assertEquals(3, $c->roundValue(2.5549), 'check round #4');
    }

    public function testRoundValueAsInteger()
    {
        $c = $this->getTestCurrency();

        $this->assertEquals(2555, $c->roundValueAsInteger(2.5549), 'check round');

        $c->setE(2);
        $this->assertEquals(255, $c->roundValueAsInteger(2.5549), 'check round #2');

        $c->setE(1);
        $this->assertEquals(26, $c->roundValueAsInteger(2.5549), 'check round #3');

        $c->setE(0);
        $this->assertEquals(3, $c->roundValueAsInteger(2.5549), 'check round #4');
    }

    public function testConvertIntegerToFloat()
    {
        $c = $this->getTestCurrency();

        $this->assertEquals(2.555, $c->convertIntegerToFloat(2555), 'check round');

        $c->setE(2);
        $this->assertEquals(2.55, $c->convertIntegerToFloat(255), 'check round #2');

        $c->setE(1);
        $this->assertEquals(2.6, $c->convertIntegerToFloat(26), 'check round #3');

        $c->setE(0);
        $this->assertEquals(3, $c->convertIntegerToFloat(3), 'check round #4');
    }

    public function testFormatValue()
    {
        $c = $this->getTestCurrency();

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

    /**
     * PHPUnit default function.
     * It's not recommended to redefine this method
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setUp()
    {
        parent::setUp();

        $list = \XLite\Core\Database::getEM()->createQueryBuilder()
            ->select('c')
            ->from('XLite\Model\Currency', 'c')
            ->andWhere('c.code IN(:code1, :code2)')
            ->setParameter('code1', 'XXX')
            ->setParameter('code2', 'ZZZ')
            ->getQuery()
            ->getResult();

        foreach ($list as $c) {
            \XLite\Core\Database::getEM()->remove($c);
        }
        \XLite\Core\Database::getEM()->flush();
    }

    protected function getTestCurrency()
    {
        $c = new \XLite\Model\Currency();

        $c->map($this->testData);

        \XLite\Core\Database::getEM()->persist($c);
        \XLite\Core\Database::getEM()->flush();

        return $c;
    }
}
