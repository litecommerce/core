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
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

include_once __DIR__ . '/AProduct.php';

class XLite_Tests_Model_Product extends XLite_Tests_Model_AProduct
{
    /**
     * Return data needed to start application.
     * Derived class can redefine this method.
     * It's possible to detect current test using the $this->name variable
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequest()
    {
        $request = parent::getRequest();

        $request['controller'] = false;

        return $request;
    }

	/**
	 * getProductData
	 *
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  1.0.0
	 */
	protected function getProductData()
	{
		return array(
			'price'         => array(4.99, null),
			'sku'           => array('test_sku', null),
			'enabled'       => array(true, null),
			'weight'        => array(2.88, null),
			'free_shipping' => array(true, null),
			'clean_url'     => array('test_url', null),
			'javascript'    => array('test_js', null),
            'name'          => array('test name', null),
            'description'   => array('test description', null),
            'brief_description' => array('test brief description', null),
            'meta_tags'     => array('test meta tags', null),
            'meta_desc'     => array('test meta description', null),
            'meta_title'    => array('test meta title', null),
		);
	}

	/**
	 * testConstruct
	 *
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  1.0.0
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
			$this->assertEquals(
                $value,
                $entity->{'get' . \XLite\Core\Converter::convertToCamelCase($key)}(),
                'Field "' . $key . '" does not match'
            );
		}

        // Order item
        $i = new \XLite\Model\OrderItem;
        $entity->addOrderItems($i);
        $this->assertEquals($i, $entity->getOrderItems()->get(0), 'check order item');

        // Image
        $i = new \XLite\Model\Image\Product\Image;
        $entity->addImages($i);
        $this->assertEquals($i, $entity->getImages()->get(0), 'check image');

	}

	/**
	 * testAddCategoryProducts
	 *
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  1.0.0
	 */
	public function testAddCategoryProducts()
	{
        $c = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneBy(array('cleanURL' => 'fruit'));
		$p = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => '00000'));

        $this->assertNotNull($p, 'check product');

        $cp = new \XLite\Model\CategoryProducts();
        $cp->setCategory($c);
        $cp->setProduct($p);

		$result = $cp;

		// Check keys
        $this->assertNotNull($result->getCategory(), 'Invalid category');
        $this->assertNotNull($result->getProduct(), 'Invalid product');
		$this->assertEquals(14015, $result->getCategory()->getCategoryId(), 'Invalid category ID');
		$this->assertEquals(15090, $result->getProduct()->getProductId(), 'Invalid product ID');
	}

	/**
	 * testAddOptionGroups
	 *
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  1.0.0
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
     * @since  1.0.0
     */
    public function testAddTranslations()
    {
        $entity = new \XLite\Model\Product();

        $entity->addTranslations(new \XLite\Model\ProductTranslation(array('name' => 'test')));

        $result = array_pop($entity->getTranslations()->toArray());

        // Check keys
        $this->assertEquals('test', $result->getName(), 'Invalid product name');
    }

    public function testIsAvailable()
    {
        $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => '00007'));

        $this->assertNotNull($p, 'check product');

        $p->setEnabled(true);
        $this->assertTrue($p->isAvailable(), 'check enabled');

        $p->setEnabled(false);
        $this->assertFalse($p->isAvailable(), 'check disabled');

        $p->setEnabled(true);
        $p->setArrivalDate(100);
        $this->assertTrue($p->isAvailable(), 'check enabled (old product)');

        $p->setArrivalDate(time() + 86400);
        $this->assertFalse($p->isAvailable(), 'check disabled (comming soon)');


    }

    public function testgetListPrice()
    {
        $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => '00007'));

        $this->assertNotNull($p, 'check product');
        $this->assertEquals($p->getPrice(), $p->getListPrice(), 'check taxed price (equals taxed price)');
    }

    public function testhasImage()
    {
        $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => '00000'));

        $this->assertNotNull($p, 'check product');
        $this->assertTrue($p->hasImage(), 'check image');

        $p->getImages()->clear();

        $this->assertFalse($p->hasImage(), 'check empty image');
    }

    public function testgetImageURL()
    {
        $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => '00000'));

        $this->assertNotNull($p, 'check product');
        $this->assertRegExp('/images.product.demo_p15090\.jpeg$/Ss', $p->getImageURL(), 'check image URL');

        $p->getImages()->clear();

        $this->assertNull($p->getImageURL(), 'check empty image URL');
    }

    public function testgetCategory()
    {
        $c = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneBy(array('cleanURL' => 'fruit'));
        $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => '00007'));

        $this->assertNotNull($p, 'check product');

        $cp = new \XLite\Model\CategoryProducts();
        $cp->setCategory($c);
        $cp->setProduct($p);

        $em = \XLite\Core\Database::getEM();
        $em->persist($cp);
        $em->flush();

        $em->clear();

        $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => '00007'));

        $this->assertNotNull($p, 'check product #2');
        $cp = $p->getCategory(14015);
        $this->assertTrue($cp instanceof \XLite\Model\Category, 'check class');
        $this->assertEquals(14015, $cp->getCategoryId(), 'check category id');

        $cp = $p->getCategory(999999999);
        $this->assertTrue($cp instanceof \XLite\Model\Category, 'check class #2');
        $this->assertTrue(is_null($cp->getCategoryId()), 'check category is null');

        $cp = $p->getCategory();
        $this->assertTrue($cp instanceof \XLite\Model\Category, 'check class #3');
        $this->assertEquals(14015, $cp->getCategoryId(), 'check category id #3');
        $em->merge($cp);
        $em->remove($cp);
        $em->flush();
    }

    public function testgetOrderBy()
    {
        $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => '00007'));

        $this->assertNotNull($p, 'check product');
        $this->assertEquals(10, $p->getOrderBy(14009), 'check order by of exist link');
        $this->assertEquals(0, $p->getOrderBy(999999999), 'check order by of NON exist link');
    }

    public function testcountImages()
    {
        $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => '00000'));

        $this->assertNotNull($p, 'check product');
        $this->assertEquals(1, $p->countImages(), 'check 1 images');

        $p->getImages()->clear();

        $this->assertEquals(0, $p->countImages(), 'check zero images');
    }

    public function testgetCommonDescription()
    {
        $p = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBy(array('sku' => '00008'));

        $this->assertNotNull($p, 'check product');

        $this->assertEquals(
            '<h5>Cucumber</h5>
<p>The cucumber (Cucumis sativus) is a widely cultivated plant in the gourd family Cucurbitaceae, which includes squash, and in the same genus as the muskmelon.</p>
<p>&nbsp;</p>
<div style="padding: 24px 24px 24px 21px; display: block; background-color: #ececec;">From <a style="color: #1e7ec8; text-decoration: underline;" title="Wikipedia" href="http://en.wikipedia.org">Wikipedia</a>, the free encyclopedia</div>',
            $p->getCommonDescription(),
            'check as description'
        );

        $p->setBriefDescription('test brief description');
        $this->assertEquals(
            'test brief description',
            $p->getCommonDescription(),
            'check as description'
        );
    }
}
