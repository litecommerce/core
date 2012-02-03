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

namespace XLite\View\ProductClasses\Book;

/**
 * AssignAttributes
 *
 * @see   ____class_see____
 * @since 1.0.16
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class AssignAttributes extends \XLite\View\Dialog
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
        $result[] = 'product_class_assign_attributes';

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
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/controller.js';

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
        return 'product_classes/book/row/assign_attributes';
    }

    /**
     * Check if all attributes in group are assigned
     *
     * @param \XLite\Model\Attribute\Group $group Group to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function isGroupSelected(\XLite\Model\Attribute\Group $group)
    {
        return false;
    }

    /**
     * Get numder of attributes in group
     *
     * @param \XLite\Model\Attribute\Group $group Group to get info
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getGroupAttributesCount(\XLite\Model\Attribute\Group $group)
    {
        return count($group->getAttributes());
    }

    /**
     * Get text label
     *
     * @param \XLite\Model\Attribute\Group $group Group to get info
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getGroupAttributesCountLabel(\XLite\Model\Attribute\Group $group)
    {
        return static::t('{{X}} attributes in group', array('X' => $this->getGroupAttributesCount($group)));
    }

    /**
     * Check if the attribute is assigned ot current class
     *
     * @param \XLite\Model\Attribute $attribute Attribute to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function isAttributeSelected(\XLite\Model\Attribute $attribute)
    {
        return $this->getProductClass()->getAttributes()->contains($attribute);
    }

    /**
     * Return number of assigned attributes
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAttributesCount()
    {
        return count($this->getProductClass()->getAttributes());
    }

    /**
     * Get text label
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAttributesCountLabel()
    {
        return static::t('{{X}} attributes selected', array('X' => $this->getAttributesCount()));
    }
}
