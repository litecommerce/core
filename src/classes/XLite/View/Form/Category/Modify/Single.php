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
 * @since     1.0.21
 */

namespace XLite\View\Form\Category\Modify;

/**
 * Single 
 *
 * @see   ____class_see____
 * @since 1.0.21
 */
class Single extends \XLite\View\Form\Category\Modify\AModify
{
    /**
     * getDefaultTarget
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTarget()
    {
        return 'category';
    }

    /**
     * getDefaultAction
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultAction()
    {
        return 'modify';
    }

    /**
     * getDefaultParams
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultParams()
    {
        $list = parent::getDefaultParams();
        $list['category_id'] = $this->getCategoryId();
        $list['parent_id'] = $this->getParentCategoryId();

        return $list;
    }

    /**
     * Get validator
     *
     * @return \XLite\Core\Validator\HashArray
     * @see    ____func_see____
     * @since  1.0.21
     */
    protected function getValidator()
    {
        $validator = parent::getValidator();
        $this->setDataValidators($validator->addPair('postedData', new \XLite\Core\Validator\HashArray()));

        return $validator;
    }

    /**
     * Set validators pairs for products data
     *
     * @param mixed $data Data
     *
     * @return null
     * @see    ____func_see____
     * @since  1.0.21
     */
    protected function setDataValidators($data)
    {
        $data->addPair('name', new \XLite\Core\Validator\String(true), null, 'Category name');
        $data->addPair('show_title', new \XLite\Core\Validator\Enum\Boolean(), null, 'Category title');
        $data->addPair('description', new \XLite\Core\Validator\String(), null, 'Description');
        $data->addPair('enabled', new \XLite\Core\Validator\Enum\Boolean(), null, 'Availability');
        $data->addPair('meta_title', new \XLite\Core\Validator\String(), null, 'Meta title');
        $data->addPair('meta_tags', new \XLite\Core\Validator\String(), null, 'Meta keywords');
        $data->addPair('meta_desc', new \XLite\Core\Validator\String(), null, 'Meta description');

        $data->addPair(
            'cleanURL',
            new \XLite\Core\Validator\String\RegExp(false, $this->getCleanURLPattern()),
            null,
            'Clean URL'
        );
    }
}
