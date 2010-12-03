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

include_once __DIR__ . '/AProduct.php';

class XLite_Tests_Model_Product extends XLite_Tests_Model_AProduct
{
	/**
	 * getProductData 
	 * 
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getProductData()
	{
		return array(
			'price'         => array(4.99, null),
			'sale_price'    => array(3.54, null),
			'sku'           => array('test_sku', null),
			'enabled'       => array(true, null),
			'weight'        => array(2.88, null),
			'tax_class'     => array('test_class', null),
			'free_shipping' => array(true, null),
			'clean_url'     => array('test_url', null),
			'javascript'    => array('test_js', null),
		);
	}

	/**
	 * testConstruct 
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function testConstruct()
	{
		$dataToSet = array();
		$dataToCheck = array();

		foreach ($this->getProductData() as $key => $data) {
			list($actual, $expected) = $data;
			$dataToSet[$key] = $actual;
			$dataToCheck[$key] = isset($expected) ? $expected : $actual;
		}

		$entity = new \XLite\Model\Product($dataToSet);

		foreach ($dataToCheck as $key => $value) {
			$this->assertEquals($value, $entity->{'get' . \XLite\Core\Converter::convertToCamelCase($key)}(), 'Field "' . $key . '" does not match');
		}
	}

	/**
	 * testAddCategoryProducts 
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function testAddCategoryProducts()
	{
		$entity = new \XLite\Model\Product();

		$entity->addCategoryProducts(
			new \XLite\Model\CategoryProducts(
				array('category_id' => 14015, 'product_id' => 15090)
			)
		);

		$result = array_pop($entity->getCategoryProducts()->toArray());

		// Check keys
		$this->assertEquals(14015, $result->getCategoryId(), 'Invalid category ID');
		$this->assertEquals(15090, $result->getProductId(), 'Invalid product ID');
	}

	/**
	 * testAddOptionGroups 
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function testAddOptionGroups()
    {
        $entity = new \XLite\Model\Product();

        $entity->addOptionGroups(
            new XLite\Module\CDev\ProductOptions\Model\OptionGroup(
                array(
					'type' => XLite\Module\CDev\ProductOptions\Model\OptionGroup::GROUP_TYPE,
					'view_type' => XLite\Module\CDev\ProductOptions\Model\OptionGroup::SELECT_VISIBLE,
				)
            )
        );

        $result = array_pop($entity->getOptionGroups()->toArray());

        // Check keys
        $this->assertEquals(XLite\Module\CDev\ProductOptions\Model\OptionGroup::GROUP_TYPE, $result->getType(), 'Invalid group type');
        $this->assertEquals(XLite\Module\CDev\ProductOptions\Model\OptionGroup::SELECT_VISIBLE, $result->getViewType(), 'Invalid view type');
    }

    /**
     * testAddTranslations 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testAddTranslations()
    {
        $entity = new \XLite\Model\Product();

        $entity->addTranslations(new \XLite\Model\ProductTranslation(array('name' => 'test')));

        $result = array_pop($entity->getTranslations()->toArray());

        // Check keys
        $this->assertEquals('test', $result->getName(), 'Invalid product name');
    }

    /**
     * testGetProductId 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
	public function testGetProductId()
    {
        $result = \XLite\Core\Database::getRepo('XLite\Model\Product')->find(15090);

        // Check entity
        $this->assertNotNull($result, 'Product not found');
        $this->assertEquals(15090, $result->getProductId(), 'Product ID does not match');
    }
}
