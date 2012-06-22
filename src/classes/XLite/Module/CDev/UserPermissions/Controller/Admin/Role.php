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
 * @since     1.0.17
 */

namespace XLite\Module\CDev\UserPermissions\Controller\Admin;

/**
 * Role 
 * 
 * @see   ____class_see____
 * @since 1.0.17
 */
class Role extends \XLite\COntroller\Admin\AAdmin
{
    /**
     * Controller parameters
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $param = array('target', 'id');

    /**
     * Role id
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $id;

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        $model = $this->getModelForm()->getModelObject();

        return ($model && $model->getId())
            ? $model->getPublicName()
            : \XLite\Core\Translation::getInstance()->lbl('Role');
    }

    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return $this->getTitle();
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addBaseLocation()
    {
        $this->addLocationNode(
            'Roles',
            \XLite\Core\Converter::buildUrl('roles')
        );
    }

    /**
     * Update coupon
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function doActionUpdate()
    {
        $this->getModelForm()->performAction('modify');

        if ($this->getModelForm()->getModelObject()->getId()) {
            $this->setReturnUrl(\XLite\Core\Converter::buildURL('roles'));
        }
    }

   /**
    * Get model form class
    *
    * @return void
    * @see    ____func_see____
    * @since  1.0.15
    */
   protected function getModelFormClass()
   {
       return 'XLite\Module\CDev\UserPermissions\View\Model\Role';
   }

}

