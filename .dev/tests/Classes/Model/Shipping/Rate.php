<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * \XLite\Model\Shipping\Rate class tests
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

class XLite_Tests_Model_Shipping_Rate extends XLite_Tests_TestCase
{
    /**
     * testCreate
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCreate()
    {
        // Prepare data for rate
        $method = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->find(100);
        $methodName = $method->getName();

        $markups = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Markup')->findAll();
        $markup = array_shift($markups);
        $markupId = $markup->getMarkupId();
        unset($markups);

        $extraData = new \XLite\Core\CommonCell();
        $extraData->testparam1 = 'test value 1';
        $extraData->testparam2 = 'test value 2';

        $newRate = new \XLite\Model\Shipping\Rate();

        $newRate->setMethod($method);
        $newRate->setBaseRate(100);
        $newRate->setMarkup($markup);
        $newRate->setMarkupRate(200);
        $newRate->setExtraData($extraData);

        // Check all parameters
        $this->assertEquals(100, $newRate->getMethod()->getMethodId(), 'Method wrong');
        $this->assertEquals($markupId, $newRate->getMarkup()->getMarkupId(), 'Markup wrong');
        $this->assertEquals(100, $newRate->getBaseRate(), 'Base rate wrong');
        $this->assertEquals(200, $newRate->getMarkupRate(), 'Markup rate wrong');
        $this->assertEquals(300, $newRate->getTotalRate(), 'Total rate wrong');
        $this->assertEquals('test value 1', $newRate->getExtraData()->testparam1, 'Extra data #1 wrong');
        $this->assertEquals('test value 2', $newRate->getExtraData()->testparam2, 'Extra data #2 wrong');
        $this->assertEquals(100, $newRate->getMethodId(), 'getMethod() returned wrong value');
        $this->assertEquals($methodName, $newRate->getMethodName(), 'getMethodName() returned wrong value');
    }

}
