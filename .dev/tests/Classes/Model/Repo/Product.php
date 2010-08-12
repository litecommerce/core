<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\Repo\Product class tests
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

class XLite_Tests_Model_Repo_Product extends XLite_Tests_TestCase
{
    /**
     * setUp 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setUp()
    {
        parent::setUp();

        $this->query(file_get_contents(__DIR__ . '/sql/product/setup.sql'));
        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * tearDown 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function tearDown()
    {
        parent::tearDown();

        // $this->query(file_get_contents(__DIR__ . 'sql/product/restore.sql'));
        // \XLite\Core\Database::getEM()->flush();
    }

    /**
     * getDefaultCnd 
     * 
     * @return \XLite\Core\CommonCell
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultCnd()
    {
        return new \XLite\Core\CommonCell(
            array(
                \XLite\Model\Repo\Product::P_SKU => '',
                \XLite\Model\Repo\Product::P_CATEGORY_ID => 0,
                \XLite\Model\Repo\Product::P_SUBSTRING => '',
                \XLite\Model\Repo\Product::P_SEARCH_IN_SUBCATS => true,
                \XLite\Model\Repo\Product::P_ORDER_BY => array(
                    \XLite\View\ItemsList\Product\AProduct::SORT_BY_MODE_NAME,
                    \XLite\View\ItemsList\Product\AProduct::SORT_ORDER_ASC
                ),
                \XLite\Model\Repo\Product::P_LIMIT => array(0, 100),
            )
        );
    }


	/**
	 * testsearch 
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function testsearch1()
	{
        $cnd = $this->getDefaultCnd();

        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd);

        $this->assertEquals(9, count($result), 'Number of found product does not match');
	}

    /**
     * testsearch 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testsearch2()
    {   
    }

    /**
     * testsearch 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testsearch3()
    {   
    }
}
