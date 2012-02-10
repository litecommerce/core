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

namespace XLite\View\Attributes\Book\Row\Attribute;

/**
 * AssignClasses
 *
 * @see   ____class_see____
 * @since 1.0.16
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class AssignClasses extends \XLite\View\Dialog
{
    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.16
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'attribute_assign_classes';

        return $result;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/style.css';

        return $list;
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getDir()
    {
        return 'attributes/book/row/attribute/assign_classes';
    }

    /**
     * Check if class assigned to attribute
     *
     * @param \XLite\Model\ProductClass $class Product class to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function isProductClassAssigned(\XLite\Model\ProductClass $class)
    {
        return $this->getAttribute()->getClasses()->contains($class);
    }

    /**
     * Get text label
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAssignedClassesInfoLabel()
    {
        return static::t('{{X}} product classes selected', array('X' => count($this->getAttribute()->getClasses())));
    }

    /**
     * Return number of products assigned to attribute
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAssignedProductsCount()
    {
        return $this->getAttribute()->getAssignedProductsCount();
    }

    /**
     * Get text label
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAssignedProductsInfoLabel()
    {
        return static::t('{{X}} products in total', array('X' => $this->getAssignedProductsCount()));
    }
}
