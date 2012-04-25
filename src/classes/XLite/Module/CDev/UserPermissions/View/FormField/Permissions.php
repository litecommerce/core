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

namespace XLite\Module\CDev\UserPermissions\View\FormField;

/**
 * Permissions selector 
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
class Permissions extends \XLite\View\FormField\Select\CheckboxList\ACheckboxList
{
    /**
     * Root permission
     * 
     * @var   \XLite\Model\Role\Permission
     * @see   ____var_see____
     * @since 1.0.22
     */
    protected $root;

    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list[] = parent::getJSFiles();

        $list[] = 'modules/CDev/UserPermissions/role/permissions.js';

        return $list;
    }

    /**
     * Return default options list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultOptions()
    {
        $list = array();

        foreach (\XLite\Core\Database::getRepo('XLite\Model\Role\Permission')->findAll() as $perm) {
            $section = $perm->getSection();
            if (!isset($list[$section])) {
                $list[$section] = array(
                    'label'   => $section,
                    'options' => array(),
                );
            }

            $list[$section]['options'][$perm->getId()] = $perm->getPublicName();
        }

        return $list;
    }

    /**
     * Get option attributes
     *
     * @param mixed $value Value
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function getOptionAttributes($value)
    {
        $list = parent::getOptionAttributes($value);

        if ($value == $this->getRootPermission()->getId()) {
            $list['data-isRoot'] = '1';
        }

        return $list;
    }

    /**
     * Get root permission 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function getRootPermission()
    {
        if (!isset($this->root)) {
            $this->root = \XLite\Core\Database::getRepo('XLite\Model\Role\Permission')
                ->findOneBy(array('code' => \XLite\Model\Role\Permission::ROOT_ACCESS));
        }

        return $this->root;
    }
}
