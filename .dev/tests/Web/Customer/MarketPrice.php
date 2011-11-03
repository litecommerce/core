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
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.10
 *
 * @resource product_market_price
 * @use product
 * @use category
 */

require_once __DIR__ . '/ACustomer.php';

/**
 * XLite_Web_Customer_MarketPrice 
 *
 * @see   ____class_see____
 * @since 1.0.10
 */
class XLite_Web_Customer_MarketPrice extends XLite_Web_Customer_ACustomer
{
    /**
     * origPrice
     *
     * @var   mixed
     * @see   ____var_see____
     * @since 1.0.10
     */
    protected $origPrice;

    /**
     * product
     *
     * @var   mixed
     * @see   ____var_see____
     * @since 1.0.10
     */
    protected $product;


    // {{{ Product details and QuickLook pages

    /**
     * testProductDetailsCommon
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testProductDetailsCommon()
    {
        $this->checkDetailsPageCommon('Regular');
    }

    /**
     * testProductDetailsWithoutLabel
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testProductDetailsWithoutLabel()
    {
        $this->checkDetailsPageWithoutLabel('Regular');
    }

    /**
     * testProductDetailsWithMaximumSave
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testProductDetailsWithMaximumSave()
    {
        $this->checkDetailsPageWithMaximumSave('Regular');
    }

    /**
     * testQuickLookCommon
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testQuickLookCommon()
    {
        $this->checkDetailsPageCommon('QuickLook');
    }

    /**
     * testQuickLookWithoutLabel
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testQuickLookWithoutLabel()
    {
        $this->checkDetailsPageWithoutLabel('QuickLook');
    }

    /**
     * testQuickLookWithMaximumSave
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testQuickLookWithMaximumSave()
    {
        $this->checkDetailsPageWithMaximumSave('QuickLook');
    }

    /**
     * checkDetailsPageCommon
     *
     * @param mixed $type ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkDetailsPageCommon($type)
    {
        // :WARNING: do not change call order!
        $percent = $this->getSavePercent('reg');

        $this->{'checkDetailsPageStructure' . $type}();
        $this->checkDetailsPageLabel($percent);
    }

    /**
     * checkDetailsPageWithoutLabel
     *
     * @param mixed $type ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkDetailsPageWithoutLabel($type)
    {
        // :WARNING: do not change call order!
        $percent = $this->getSavePercent('min');

        $this->{'checkDetailsPageStructure' . $type}();
        $this->checkDetailsPageLabel($percent);
    }

    /**
     * checkDetailsPageWithMaximumSave
     *
     * @param mixed $type ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkDetailsPageWithMaximumSave($type)
    {
        // :WARNING: do not change call order!
        $percent = $this->getSavePercent('max');

        $this->{'checkDetailsPageStructure' . $type}();
        $this->checkDetailsPageLabel($percent);
    }

    /**
     * checkDetailsPageStructureRegular
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkDetailsPageStructureRegular()
    {
        $this->skipCoverage();
        $this->open('store/product//product_id-' . $this->product->getProductId());

        $this->checkDetailsPageStructureCommon();

        $this->assertElementPresent(
            '//div[@class="product-details-market-price"]'
            . '/div[@class="text"]'
            . '/span[@class="you-save" and text()="' . $this->formatPrice($this->product->getMarketPrice() - $this->product->getPrice()) . '"]',
            'check text div - the "you save" span'
        );
    }

    /**
     * checkDetailsPageStructureQuickLook
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkDetailsPageStructureQuickLook()
    {
        $this->skipCoverage();
        $this->open('store/category/0/category_id-' . $this->product->getCategory()->getCategoryId());
        $this->click('css=.category-products ' . $this->getListEntrySelector() . ' a.quicklook-link-' . $this->product->getProductId());
        $this->waitForLocalCondition('jQuery(".BlockMsg-product-quicklook:visible").length > 0', 30000);

        $this->checkDetailsPageStructureCommon();
    }

    /**
     * checkDetailsPageStructureCommon
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkDetailsPageStructureCommon()
    {
        $this->assertElementPresent(
            '//div[@class="product-details-market-price"]',
            'check main div'
        );
        $this->assertElementPresent(
            '//div[@class="product-details-market-price"]'
            . '/div[@class="text"]'
            . '/span[@class="value" and text()="' . $this->formatPrice($this->product->getMarketPrice()) . '"]',
            'check text div - the "value" span'
        );
    }

    /**
     * checkDetailsPageLabel
     *
     * @param mixed $percentLess ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkDetailsPageLabel($percentLess)
    {
        $isPresent = 0 < $percentLess;

        $this->{'assertElement' . ($isPresent ? '' : 'Not') . 'Present'}(
            '//div[@class="product-details-market-price"]'
            . '/ul[@class="labels"]',
            'check "% less" main block"'
        );

        if ($isPresent) {
            $this->assertElementPresent(
                '//div[@class="product-details-market-price"]'
                . '/ul[@class="labels"]'
                . '/li[@class="label-orange market-price"]',
                'check "% less" label'
            );
            $this->assertElementPresent(
                '//div[@class="product-details-market-price"]'
                . '/ul[@class="labels"]'
                . '/li[@class="label-orange market-price"]'
                . '/div[text()="' . $percentLess . '% less"]',
                'check "% less" label internal div'
            );
        }
    }

    // }}}


    // {{{ Product lists

    /**
     * testGridCommon
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testGridCommon()
    {
        $this->checkListPageCommon('grid');
    }

    /**
     * testGridWithoutLabel
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testGridWithoutLabel()
    {
        $this->checkListPageWithoutLabel('grid');
    }

    /**
     * testGridWithMaximumSave
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testGridWithMaximumSave()
    {
        $this->checkListPageWithMaximumSave('grid');
    }

    /**
     * testListCommon
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testListCommon()
    {
        $this->checkListPageCommon('list');
    }

    /**
     * testListWithoutLabel
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testListWithoutLabel()
    {
        $this->checkListPageWithoutLabel('list');
    }

    /**
     * testListWithMaximumSave
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testListWithMaximumSave()
    {
        $this->checkListPageWithMaximumSave('list');
    }

    /**
     * testTableCommon
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testTableCommon()
    {
        $this->checkListPageCommon('table');
    }

    /**
     * testTableWithoutLabel
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testTableWithoutLabel()
    {
        $this->checkListPageWithoutLabel('table');
    }

    /**
     * testTableWithMaximumSave
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function testTableWithMaximumSave()
    {
        $this->checkListPageWithMaximumSave('table');
    }

    /**
     * checkListPageCommon
     *
     * @param mixed $type ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkListPageCommon($type)
    {
        // :WARNING: do not change call order!
        $percent = $this->getSavePercent('reg');

        $this->checkListPageStructure($type);
        $this->checkListPageLabel($percent, $type);
    }

    /**
     * checkListPageWithoutLabel
     *
     * @param mixed $type ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkListPageWithoutLabel($type)
    {
        // :WARNING: do not change call order!
        $percent = $this->getSavePercent('min');

        $this->checkListPageStructure($type);
        $this->checkListPageLabel($percent, $type);
    }

    /**
     * checkListPageWithMaximumSave
     *
     * @param mixed $type ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkListPageWithMaximumSave($type)
    {
        // :WARNING: do not change call order!
        $percent = $this->getSavePercent('max');

        $this->checkListPageStructure($type);
        $this->checkListPageLabel($percent, $type);
    }

    /**
     * checkListPageStructure
     *
     * @param mixed $type ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkListPageStructure($type)
    {
        $this->skipCoverage();
        $this->open('store/category/0/category_id-' . $this->product->getCategory()->getCategoryId());
        $this->click('css=.category-products ul.display-modes li.list-type-' . $type . ' a');
        $this->waitForCondition('selenium.isElementPresent("css=.blockUI.block-wait")', 30000, 'Awaiting for progess bar displaying failed');
        $this->waitForCondition('!selenium.isElementPresent("css=.blockUI.block-wait")', 30000, 'Awaiting for progess bar hiding failed');

        $isPresent = 'table' !== $type;
        $selector  = '.products-' . $type . ' ' . $this->getListEntrySelector() . ' div.product-list-market-price';

        $this->{'assertElement' . ($isPresent ? '' : 'Not') . 'Present'}('css=' . $selector, 'check main div');

        if ($isPresent) {
            $this->assertEquals(
                $this->formatPrice($this->product->getMarketPrice()),
                trim($this->getJSExpression('jQuery("' . $selector . '").html()')),
                'check value'
            );
        }
    }

    /**
     * checkDetailsPageLabel
     *
     * @param mixed $percentLess ____param_comment____
     * @param mixed $type        ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkListPageLabel($percentLess, $type)
    {
        $isPresent = 0 < $percentLess;

        $selector  = '.products-' . $type . ' ' . $this->getListEntrySelector() . ' ul.labels';
        $this->{'assertElement' . ($isPresent ? '' : 'Not') . 'Present'}('css=' . $selector, 'check "% less" main block"');

        $selector .= ' li.label-orange.market-price';
        $this->{'assertElement' . ($isPresent ? '' : 'Not') . 'Present'}('css=' . $selector, 'check "% less" label');

        $selector .= ' div';
        $this->{'assertElement' . ($isPresent ? '' : 'Not') . 'Present'}('css=' . $selector, 'check "% less" label internal div');

        if ($isPresent) {
            $this->assertEquals(
                $percentLess . '% less',
                trim($this->getJSExpression('jQuery("' . $selector . '").html()')),
                'check value'
            );
        }
    }

    // }}}


    // {{{ Auxiliarry methods

    /**
     * setMarketPrice
     *
     * @param mixed $multiplier ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function setMarketPrice($multiplier)
    {
        $this->product->setMarketPrice($this->origPrice * (1 + $multiplier));
        \XLite\Core\Database::getRepo('\XLite\Model\Product')->update($this->product);
    }

    /**
     * getListEntrySelector
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getListEntrySelector()
    {
        return '.productid-' . $this->product->getProductId();
    }

    /**
     * getSavePercent
     *
     * @param mixed $type ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getSavePercent($type)
    {
        $list = array(
            'reg' => array(rand(3, 6) / 10,),
            'min' => array(0.0001, 0),
            'max' => array(1000, 99),
        );

        $this->setMarketPrice($list[$type][0]);

        if ('reg' === $type) {
            $list['reg'][1] = round((($this->product->getMarketPrice() - $this->product->getPrice()) / $this->product->getMarketPrice()) * 100);
        }

        return $list[$type][1];
    }

    /**
     * setUp
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function setUp()
    {
        parent::setUp();

        $this->product   = $this->getActiveProduct();
        $this->origPrice = $this->product->getPrice();
    }

    // }}}
}
