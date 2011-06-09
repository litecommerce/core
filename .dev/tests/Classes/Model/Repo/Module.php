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
 * XLite_Tests_Model_Repo_Module 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class XLite_Tests_Model_Repo_Module extends XLite_Tests_TestCase
{
    /**
     * setUp 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setUp()
    {
        parent::setUp();

        $this->doRestoreDb(__DIR__ . '/sql/module/setup.sql', false);
    }

    /**
     * testSearchAll 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchAll()
    {
        $this->searchTest(null, null, 19, 'CDev\TinyMCE');
    }

    /**
     * testSearchSubstring 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchSubstring()
    {
        $this->searchTest('P_SUBSTRING', 'f', 9, 'CDev\ProductOptions');
    }

    /**
     * testSearchPriceFilter 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchPriceFilterFree()
    {
        $this->searchTest('P_PRICE_FILTER', \XLite\Model\Repo\Module::PRICE_FREE, 16, 'CDev\TinyMCE');
    }

    /**
     * testSearchPriceFilterPaid 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchPriceFilterPaid()
    {
        $this->searchTest('P_PRICE_FILTER', \XLite\Model\Repo\Module::PRICE_PAID, 4, 'Test\Module7');
    }

    /**
     * testSearchInstalled 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchInstalled()
    {
        $this->searchTest('P_INSTALLED', true, 12, 'CDev\TinyMCE');
    }

    /**
     * testSearchNotInstalled 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchNotInstalled()
    {
        $this->searchTest('P_INSTALLED', false, 10, 'Test\Module7');
    }

    /**
     * testSearchInactive 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchInactive()
    {
        $this->searchTest('P_INACTIVE', true, 12, 'Test\Module7');
    }

    /**
     * testSearchCoreVersion1
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchCoreVersion1()
    {
        $this->searchTest('P_INSTALLED', true, 12, 'CDev\TinyMCE');
    }

    /**
     * testSearchFromMarketplace 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchFromMarketplace()
    {
        $this->searchTest('P_FROM_MARKETPLACE', true, 17, 'CDev\TinyMCE');
    }

    // {{{ Protected methods

    /**
     * getRepo
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRepo()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module');
    }

    /**
     * searchTest 
     * 
     * @param mixed $param ____param_comment____
     * @param mixed $value ____param_comment____
     * @param mixed $count ____param_comment____
     * @param mixed $name  ____param_comment____
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function searchTest($param, $value, $count, $name)
    {
        $cnd = new \XLite\Core\CommonCell();

        if (isset($param)) {
            $cnd->{constant('\XLite\Model\Repo\Module::' . $param)} = $value;
        }

        $result = $this->getRepo()->search($cnd, true);
        $this->assertEquals($count, $result, 'check modules count in search result');

        if ($result = $this->getRepo()->search($cnd)) {
            $result = array_pop($result);
            $this->assertEquals($name, $result->getActualName(), 'check module ID for the last item in search result');

        } else {
            $this->fail('Empty result');
        }
    }

    // }}}
}
