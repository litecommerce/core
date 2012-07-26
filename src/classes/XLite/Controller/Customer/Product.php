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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller\Customer;

/**
 * Product
 *
 */
class Product extends \XLite\Controller\Customer\Catalog
{
    /**
     * Product 
     *
     * @var \XLite\Model\Product
     */
    protected $product;

    /**
     * Define and set handler attributes; initialize handler
     *
     * @param array $params Handler params OPTIONAL
     *
     * @return void
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);
        
        $this->params[] = 'product_id';
    }

    /**
     * Check whether the title is to be displayed in the content area
     *
     * @return boolean
     */
    public function isTitleVisible()
    {
        return false;
    }

    /**
     * Get product category id
     *
     * @return integer
     */
    public function getCategoryId()
    {
        $categoryId = parent::getCategoryId();

        if ($this->getRootCategoryId() == $categoryId && $this->getProduct() && $this->getProduct()->getCategoryId()) {
            $categoryId = $this->getProduct()->getCategoryId();
        }

        return $categoryId;
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription()
    {
        return (parent::getDescription() || !$this->getProduct()) ?: $this->getProduct()->getBriefDescription();
    }

    /**
     * Return current (or default) product object
     *
     * @return \XLite\Model\Product
     */
    public function getModelObject()
    {
        return $this->getProduct();
    }

    /**
     * Alias
     *
     * @return \XLite\Model\Product
     */
    public function getProduct()
    {
        if (!isset($this->product)) {
            $this->product = $this->defineProduct();
        }

        return $this->product;
    }

    /**
     * Define product
     *
     * @return \XLite\Model\Product
     */
    protected function defineProduct()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')->find($this->getProductId());
    }

    /**
     * Common method to determine current location
     *
     * @return string
     */
    protected function getLocation()
    {
        return $this->getProduct() ? $this->getProduct()->getName() : null;
    }

    /**
     * Return current product Id
     *
     * @return integer
     */
    protected function getProductId()
    {
        return \XLite\Core\Request::getInstance()->product_id;
    }

    /**
     * Check controller visibility
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getProduct();
    }
}
