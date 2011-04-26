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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Controller\Admin;

/**
 * AddonInstall 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class AddonInstall extends \XLite\Controller\Admin\AAdmin
{
    // {{{ Public methods for viewers

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return ($module = $this->getModule()) ? ($module->getModuleName() . ' license agreement') : null;
    }

    /**
     * Return LICENSE text for the module
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getLicense()
    {
        $result = null;
        $marketplaceID = $this->getModule()->getMarketplaceID();

        if (!empty($marketplaceID)) {
            $info = \XLite\Core\Marketplace::getInstance()->getAddonInfo($marketplaceID);

            if ($info) {
                $result = $info[\XLite\Core\Marketplace::RESPONSE_FIELD_MODULE_LICENSE];
            } else {
                \XLite\Core\Marketplace::getInstance()->setErrorTopMessage();
            }

        } else {
            \XLite\Core\TopMessage::getInstance()->addError('Markeplace ID is not set for module');
        }

        // Since this action is performed in popup
        if (!isset($result)) {
            $this->redirect();
        }

        return $result;
    }

    // }}}

    // {{{ Short-name methods

    /** 
     * Return module identificator
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleId()
    {   
        return \XLite\Core\Request::getInstance()->moduleId;
    }

    /**
     * Search for module
     * 
     * @return \XLite\Model\Module|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModule()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($this->getModuleId());
    }

    /**
     * Search for module license key
     *
     * @param array $data Keys to search
     * 
     * @return \XLite\Model\ModuleKey
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleKey(array $data)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\ModuleKey')->findOneBy($data);
    }

    // }}}

    // {{{ "Get license" action handler

    /**
     * Action of getting LICENSE text. Redirection to GET request
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionGetLicense()
    {
        $this->setReturnURL(
            $this->buildURL('addon_install', 'show_license', array('moduleId' => $this->getModuleId()))
        );
    }

    // }}}
}
