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
 * @since     1.0.16
 */

namespace XLite\Controller\Admin;

/**
 * ProductClassAssignProductClasss 
 *
 * @see   ____class_see____
 * @since 1.0.16
 */
class ProductClassAssignAttributes extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return page title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getTitle()
    {
        return 'Assign attributes';
    }

    /**
     * Get product class object
     *
     * @return \XLite\Model\ProductClass
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getProductClass()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->find($this->getProductClassId())
            ?: \Includes\ErrorHandler::fireError('There is no class with ID "' . $this->getProductClassId() . '"');
    }

    /**
     * Return list of grouped attributes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getGroupedAttributes()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Attribute')->getGroupedAttributes();
    }

    /**
     * Get class ID from request
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getProductClassId()
    {
        return intval(\XLite\Core\Request::getInstance()->classId);
    }

    /**
     * Save changes
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function doActionSave()
    {
        $this->setReturnURL($this->buildURL('product_classes'));

        $productClass = $this->getProductClass();
        $attributes   = \XLite\Core\Database::getRepo('\XLite\Model\Attribute')->findByIds(
            array_keys(array_filter($this->getPostedData()))
        );

        $productClass->getAttributes()->clear();

        foreach ($attributes as $attribute) {
            $productClass->addAttributes($attribute);
        }

        \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->update($productClass);

        \XLite\Core\TopMessage::addInfo('Attributes has been successfully assigned');
    }
}
