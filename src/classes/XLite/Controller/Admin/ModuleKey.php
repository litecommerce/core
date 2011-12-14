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
 * @since     1.0.0
 */

namespace XLite\Controller\Admin;

/**
 * ModuleKey
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class ModuleKey extends \XLite\Controller\Admin\AAdmin
{
    // {{{ Public methods for viewers

    /**
     * Return page title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Enter license key';
    }

    // }}}

    // {{{ "Register key" action handler

    /**
     * Action of license key registration
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionRegisterKey()
    {
        $key  = \XLite\Core\Request::getInstance()->key;
        $info = \XLite\Core\Marketplace::getInstance()->checkAddonKey($key);

        if ($info) {
            $module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->findOneBy($info);

            if ($module) {
                $repo   = \XLite\Core\Database::getRepo('\XLite\Model\ModuleKey');
                $entity = $repo->findKey($info['author'], $info['name']);

                if ($entity) {
                    $entity->setKeyValue($key);
                    $repo->update($entity);

                } else {
                    $entity = $repo->insert($info + array('keyValue' => $key));
                }

                // Clear cache for proper installation
                \XLite\Core\Marketplace::getInstance()->clearActionCache(\XLite\Core\Marketplace::ACTION_GET_ADDONS_LIST);

                $this->showInfo(
                    __FUNCTION__,
                    'License key has been successfully verified for "{{name}}" module by "{{author}}" author',
                    array(
                        'name'   => $module->getModuleName(),
                        'author' => $module->getAuthorName(),
                    )
                );

            } else {
                $this->showError(
                    __FUNCTION__,
                    'Key is validated, but the module [' . explode(',', $info) . '] was not found'
                );
            }

        } else {

            $error = \XLite\Core\Marketplace::getInstance()->getError();

            if ($error) {

                $this->showError(__FUNCTION__, 'Response from marketplace: ' . $error);

            } else {

                $this->showError(__FUNCTION__, 'Response from marketplace is not received');
            }
        }

        $this->setReturnURL($this->buildURL('addons_list_marketplace'));
    }

    // }}}
}
