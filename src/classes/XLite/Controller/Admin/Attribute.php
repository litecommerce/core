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
 * Attribute controller
 *
 */
class Attribute extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Controller parameters
     *
     * @var array
     */
    protected $params = array('target', 'id', 'product_class_id');

    /**
     * Product class
     *
     * @var \XLite\Model\ProductClass
     */
    protected $productClass;

    /**
     * Attribute 
     *
     * @var \XLite\Model\Attribute
     */
    protected $attribute;

    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    public function checkACL()
    {
        return parent::checkACL() 
            || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage catalog');
    }

    /**
     * Check if current page is accessible
     *
     * @return boolean
     */
    public function checkAccess()
    {
        return parent::checkAccess() 
            && $this->getProductClass()
            && $this->isAJAX();
    }

    /**
     * Get product class
     *
     * @return \XLite\Model\ProductClass
     */
    public function getProductClass()
    {
        if (
            is_null($this->productClass)
            && \XLite\Core\Request::getInstance()->product_class_id
        ) {
            $this->productClass = \XLite\Core\Database::getRepo('XLite\Model\ProductClass')
                ->find(intval(\XLite\Core\Request::getInstance()->product_class_id));
        }

        return $this->productClass;
    }

    /**
     * Get attribute
     *
     * @return \XLite\Model\Attribute
     */
    public function getAttribute()
    {
        if (
            is_null($this->attribute)
            && \XLite\Core\Request::getInstance()->id
        ) {
            $this->attribute = \XLite\Core\Database::getRepo('XLite\Model\Attribute')
                ->find(intval(\XLite\Core\Request::getInstance()->id));
        }

        return $this->attribute;
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        $id = intval(\XLite\Core\Request::getInstance()->id);
        $model = $id
            ? \XLite\Core\Database::getRepo('XLite\Model\Attribute')->find($id)
            : null;

        return ($model && $model->getId())
            ? \XLite\Core\Translation::getInstance()->lbl('Edit attribute values') 
            : \XLite\Core\Translation::getInstance()->lbl('New attribute');
    }

    /**
     * Update model
     *
     * @return void
     */
    protected function doActionUpdate()
    {
        if ($this->getModelForm()->getModelObject()->getId()) {
            $this->setSilenceClose();

        } else {
            $this->setInternalRedirect();
        }

        $list = new \XLite\View\ItemsList\Model\AttributeOption;
        $list->processQuick();

        if ($this->getModelForm()->performAction('modify')) {
            $this->setReturnUrl(
                \XLite\Core\Converter::buildURL(
                    'attribute',
                    '',
                    array(
                        'id'               => $this->getModelForm()->getModelObject()->getId(),
                        'product_class_id' => \XLite\Core\Request::getInstance()->product_class_id,
                        'widget'           => 'XLite\View\Attribute'    
                    )
                )
            );
        }
    }

    /**
     * Get model form class
     * 
     * @return string
     */
    protected function getModelFormClass()
    {
        return 'XLite\View\Model\Attribute';
    }

}
