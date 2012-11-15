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

namespace XLite\Controller\Admin;

/**
 * Product
 *
 */
class Product extends \XLite\Controller\Admin\Base\Catalog
{
    /**
     * Backward compatibility
     *
     * @var array
     */
    public $params = array('target', 'id', 'product_id', 'page', 'backURL');

    // {{{ Abstract method implementations

    /**
     * Check if we need to create new product or modify an existsing one
     *
     * NOTE: this function is public since it's neede for widgets
     *
     * @return boolean
     */
    public function isNew()
    {
        return !$this->getProduct()->isPersistent();
    }

    /**
     * Return class name for the controller main form
     *
     * @return string
     */
    protected function getFormClass()
    {
        return '\XLite\View\Form\Product\Modify\Single';
    }

    /**
     * Alias
     *
     * @return \XLite\Model\Product
     */
    protected function getEntity()
    {
        return $this->getProduct();
    }

    // }}}

    // {{{ Pages

    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    public function checkACL()
    {
        return parent::checkACL() || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage catalog');
    }

    /**
     * Get pages sections
     *
     * @return array
     */
    public function getPages()
    {
        $list = parent::getPages();
        $list['info'] = 'Product info';

        if (!$this->isNew()) {
            $list['images']    = 'Product images';
            $list['inventory'] = 'Inventory tracking';
            if ($this->getProduct()->getAttributesCount()) {
                $list['attributes'] = 'Attributes';
            }
        }

        return $list;
    }

    /**
     * Get pages templates
     *
     * @return array
     */
    protected function getPageTemplates()
    {
        $list = parent::getPageTemplates();
        $list['info']    = 'product/info.tpl';
        $list['default'] = 'product/info.tpl';

        if (!$this->isNew()) {
            $list['images']    = 'product/product_images.tpl';
            $list['inventory'] = 'product/inventory.tpl';
            if ($this->getProduct()->getAttributesCount()) {
                $list['attributes'] = 'product/attributes.tpl';
            }
        }

        return $list;
    }

    // }}}

    // {{{ Data management

    /**
     * Alias
     *
     * @return \XLite\Model\Product
     */
    public function getProduct()
    {
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->find($this->getProductId());

        if (!isset($result)) {
            $result = new \XLite\Model\Product();
        }

        return $result;
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getProduct()->getName();
    }

    /**
     * getInventory
     *
     * @return \XLite\Model\Inventory
     */
    public function getInventory()
    {
        return $this->getProduct()->getInventory();
    }

    /**
     * Get product category id
     *
     * @return integer
     */
    public function getCategoryId()
    {
        $categoryId = parent::getCategoryId();

        if (empty($categoryId) && !$this->isNew()) {
            $categoryId = $this->getProduct()->getCategoryId();
        }

        return $categoryId;
    }

    /**
     * Return current product Id
     *
     * NOTE: this function is public since it's neede for widgets
     *
     * @return integer
     */
    public function getProductId()
    {
        $result = intval(\XLite\Core\Request::getInstance()->product_id);

        if (0 >= $result) {
            $result = intval(\XLite\Core\Request::getInstance()->id);
        }

        return $result;
    }

    /**
     * Return list of the CategoryProduct entities
     *
     * @param \XLite\Model\Product $product Current product
     *
     * @return array
     */
    protected function getCategoryProducts(\XLite\Model\Product $product)
    {
        $data = array();

        foreach ((array) $this->getPostedData('category_ids') as $categoryId) {
            $data[] = new \XLite\Model\CategoryProducts(
                array(
                    'product_id'  => $product->getProductId(),
                    'category_id' => $categoryId,
                    'category'    => \XLite\Core\Database::getRepo('\XLite\Model\Category')->find($categoryId),
                    'product'     => $product,
                )
            );
        }

        return array('categoryProducts' => new \Doctrine\Common\Collections\ArrayCollection($data));
    }

    /**
     * getClasses
     *
     * @param \XLite\Model\Product $product ____param_comment____
     *
     * @return array
     */
    protected function getClasses(\XLite\Model\Product $product)
    {
        $data = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ((array) $this->getPostedData('class_ids') as $classId) {
            $class = \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->findOneById($classId);

            if ($class) {
                if (!$class->getProducts()->contains($product)) {
                    $class->getProducts()->add($product);
                }

                $data->add($class);
            }
        }

        return array('classes' => $data);
    }

    /**
     * Get posted data
     *
     * @param string $field Name of the field to retrieve OPTIONAL
     *
     * @return mixed
     */
    protected function getPostedData($field = null)
    {
        $value = parent::getPostedData($field);

        if (!isset($field)) {

            if (isset($value['arrivalDate'])) {
                $value['arrivalDate'] = intval(strtotime($value['arrivalDate'])) ?: time();
            }

            if (isset($value['sku']) && \XLite\Core\Converter::isEmptyString($value['sku'])) {
                $value['sku'] = null;
            }

        } elseif ('arrivalDate' === $field) {
            $value = intval(strtotime($value)) ?: time();

        } elseif ('sku' === $field) {
            $value = null;
        }

        return $value;
    }

    // }}}

    // {{{ Action handlers

    /**
     * doActionAdd
     *
     * @return void
     */
    protected function doActionAdd()
    {
        $product = \XLite\Core\Database::getRepo('\XLite\Model\Product')->insert($this->getPostedData());

        if (isset($product)) {

            $this->updateSKU($product);

            $inventory = new \XLite\Model\Inventory();
            $inventory->setProduct($product);

            // Create associations (categories and images)
            \XLite\Core\Database::getRepo('\XLite\Model\Product')->update(
                $product,
                $this->getCategoryProducts($product)
                + array(
                    'inventory' => $inventory,
                )
                + $this->getClasses($product)
            );

            \XLite\Core\TopMessage::addInfo('New product has been added successfully');

            // Add the ID of created product to the return URL
            $this->setReturnURL($this->buildURL('product', '', array('product_id' => $product->getProductId())));
        }
    }

    /**
     * doActionUpdate
     *
     * @return void
     */
    protected function doActionUpdate()
    {
        $product = $this->getProduct();

        // Clear all category associates
        \XLite\Core\Database::getRepo('\XLite\Model\CategoryProducts')->deleteInBatch(
            $product->getCategoryProducts()->toArray()
        );

        $product->getClasses()->clear();
        $product->getCategoryProducts()->clear();
        $data = $this->getCategoryProducts($product) + $this->getClasses($product) + $this->getPostedData();

        // Update all data
        \XLite\Core\Database::getRepo('\XLite\Model\Product')->update($product, $data);

        $this->updateSKU($product);

        \XLite\Core\TopMessage::addInfo('Product info has been updated successfully');
    }

    /**
     * Update SKU 
     * 
     * @param \XLite\Model\Product $product Product
     *  
     * @return void
     */
    protected function updateSKU(\XLite\Model\Product $product)
    {
        if (!$product->getSKU()) {
            $product->setSKU(\XLite\Core\Database::getRepo('\XLite\Model\Product')->generateSKU($product));
            \XLite\Core\Database::getEM()->flush();
        }
    }

    // TODO: refactor

    /**
     * Delete detailed image
     *
     * @return void
     */
    protected function doActionDeleteImage()
    {
        $img = \XLite\Core\Database::getRepo('\XLite\Model\Image\Product\Image')
            ->find(\XLite\Core\Request::getInstance()->image_id);

        if ($img) {

            $img->getProduct()->getImages()->removeElement($img);

            \XLite\Core\Database::getEM()->remove($img);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::addInfo(
                'The detailed image has been deleted'
            );

        } else {

            \XLite\Core\TopMessage::addError(
                'The detailed image has not been deleted'
            );
        }
    }

    /**
     * Update image
     *
     * @return void
     */
    protected function doActionUpdateImages()
    {
        $zoomId = 0;

        if (isset(\XLite\Core\Request::getInstance()->is_zoom)) {

            $keys = array_keys(\XLite\Core\Request::getInstance()->is_zoom);

            $zoomId = array_shift($keys);
        }

        foreach (\XLite\Core\Request::getInstance()->alt as $imageId => $alt) {

            $img = \XLite\Core\Database::getRepo('\XLite\Model\Image\Product\Image')
                ->find($imageId);

            if ($img) {

                $img->setAlt($alt);

                $img->setOrderby(\XLite\Core\Request::getInstance()->orderby[$imageId]);

                \XLite\Core\Database::getEM()->persist($img);
            }
        }

        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\TopMessage::addInfo('The detailed images have been updated successfully');
    }


    /**
     * Update inventory
     *
     * @return void
     */
    protected function doActionUpdateInventory()
    {
        $inv = $this->getInventory();

        $inv->map($this->getPostedData());

        if (!$inv->getInventoryId()) {
            $product = $this->getProduct();
            $product->setInventory($inv);
            $inv->setProduct($product);
            \XLite\Core\Database::getEM()->persist($inv);
        }

        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Update attributes
     *
     * @return void
     */
    protected function doActionUpdateAttributes()
    {
        $this->getProduct()->setAttrSepTab((bool) \XLite\Core\Request::getInstance()->attrSepTab);
        $cnd = new \XLite\Core\CommonCell;
        $cnd->product = $this->getProduct();
        $attributes = \XLite\Core\Database::getRepo('\XLite\Model\Attribute')->search($cnd);
        $attributeValues = \XLite\Core\Request::getInstance()->attributeValues;

        if (
            count($attributes)
            && $attributeValues
            && is_array($attributeValues)
        ) {
            foreach ($attributes as $a) {
                if (isset($attributeValues[$a->getId()])) {
                    $a->setAttributeValue($this->getProduct(), $attributeValues[$a->getId()]);
                }
            }
        }

        \XLite\Core\Database::getEM()->flush();
        \XLite\Core\TopMessage::addInfo('Attributes have been updated successfully');
    }

    // }}}
}
