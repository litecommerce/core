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

include_once dirname(__DIR__) . '/AProduct.php';

class XLite_Tests_Model_Repo_Product extends XLite_Tests_Model_AProduct
{
   /**
     * Return data needed to start application.
     * Derived class can redefine this method.
     * It's possible to detect current test using the $this->name variable
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequest()
    {
        $request = parent::getRequest();
        // Except some ones, all methods are emulate the customer area
        $request['controller'] = ('testSearchDisabledItems' === $this->name);

        return $request;
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
	 * testSearchAll
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function testSearchAll()
	{
        $cnd = $this->getDefaultCnd();

        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd);

        // If all products selected
        $this->assertEquals(8, count($result), 'Number of found product does not match');
        // If the first selected (SORT_BY_MODE_NAME, SORT_ORDER_ASC) is the "Apple" one
        $this->assertEquals(15090, $result[0]->getProductId(), 'ID of the first found product does not match');
	}

    /**
     * testSearchBySubstring
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSearchBySubstring()
    {
        $cnd = $this->getDefaultCnd();
        $cnd->{\XLite\Model\Repo\Product::P_SUBSTRING} = 'botanical';

        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd);

        // 2 products should be found
        $this->assertEquals(2, count($result), 'Number of found product does not match');
        // Check if the "Pea" and "Peach" products are only selected.
        // Also, the order is checked (SORT_BY_MODE_NAME, SORT_ORDER_ASC)
        $this->assertEquals(16280, $result[0]->getProductId(), 'ID of the first found product does not match'); // Pea
        $this->assertEquals(15091, $result[1]->getProductId(), 'ID of the first second product does not match'); // Peach
    }

    /**
     * testSearchInSingleCategory
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSearchInSingleCategory()
    {
        $cnd = $this->getDefaultCnd();
        $cnd->{\XLite\Model\Repo\Product::P_CATEGORY_ID} = 14009;
        $cnd->{\XLite\Model\Repo\Product::P_SEARCH_IN_SUBCATS} = false;

        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd);

        // 4 products should be found
        $this->assertEquals(3, count($result), 'Number of found product does not match');
        // Check category IDs
        foreach ($result as $entity) {
            $this->assertEquals(14009, $entity->getCategoryId(), 'Category ID of the product "' . $entity->getName() . '" does not match');
        }
    }

    /**
     * testSearchItemsCount 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSearchItemsCount()
    {
        $cnd = $this->getDefaultCnd();
        $cnd->{\XLite\Model\Repo\Product::P_CATEGORY_ID} = 14015;

        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd, true);

        // 4 products should be found
        $this->assertEquals(5, $result, 'Number of found product does not match');
    }

    /**
     * testSearchDisabledItems 
     * NOTE: this method is emulate the admin area
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSearchDisabledItems()
    {
        $cnd = $this->getDefaultCnd();

        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd, true);

        // 4 products should be found
        $this->assertEquals(9, $result, 'Number of found product does not match');
    }

    /**
     * testFindByCleanUrlExists 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindByCleanUrlExists()
    {
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->findByCleanUrl('test');

        // Certain product is found
        $this->assertNotNull($result, 'Product not found');
        $this->assertEquals(16282, $result->getProductId(), 'Product ID does not match');
    }

    /**
     * testFindByCleanUrlNotExists 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindByCleanUrlNotExists()
    {
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->findByCleanUrl('not_exists');

        // Product was not found
        $this->assertNull($result, 'Wrong result for the search');
    }
}
