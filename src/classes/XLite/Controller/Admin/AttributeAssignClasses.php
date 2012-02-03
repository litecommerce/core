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
 * AttributeAssignClasses 
 *
 * @see   ____class_see____
 * @since 1.0.16
 */
class AttributeAssignClasses extends \XLite\Controller\Admin\Base\AttributePopup
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
        return 'Assign to clases';
    }

    /**
     * Get all product classes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getProductClasses()
    {
        // FIXME [DOCTRINE 2.1]
        return \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->findBy(array(), array('pos' => 'ASC'));
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
        $this->setReturnURL($this->buildURL('attributes'));

        $classes    = $this->getProductClasses();
        $attribute  = $this->getAttribute();
        $postedData = $this->getPostedData();

        foreach ($classes as $class) {
            $flag = empty($postedData[$class->getId()]);

            if ($class->getAttributes()->contains($attribute) xor !$flag) {
                $class->getAttributes()->{$flag ? 'removeElement' : 'add'}($attribute);
            }
        }

        \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->updateInBatch($classes);

        \XLite\Core\TopMessage::addInfo('Product classes have been successfully assigned');
    }
}
