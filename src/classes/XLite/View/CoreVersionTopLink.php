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

namespace XLite\View;

/**
 * CoreVersionTopLink
 *
 */
class CoreVersionTopLink extends \XLite\View\AView
{
    /**
     * Flags
     *
     * @var array
     */
    protected $updateFlags;


    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'top_links' . LC_DS . 'version_notes' . LC_DS . 'body.tpl';
    }

    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    protected function checkACL()
    {
        return parent::checkACL()
            && \XLite\Core\Auth::getInstance()->isPermissionAllowed(\XLite\Model\Role\Permission::ROOT_ACCESS);
    }

    /**
     * Check widget visibility
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && \XLite\Core\Auth::getInstance()->isLogged();
    }

    /**
     * Alias
     *
     * @return string
     */
    protected function getCurrentCoreVersion()
    {
        return \XLite::getInstance()->getVersion();
    }

    /**
     * Check if there is a new core version
     *
     * @return boolean
     */
    protected function isCoreUpgradeAvailable()
    {
        $flags = $this->getUpdateFlags();

        return !empty($flags[\XLite\Core\Marketplace::FIELD_IS_UPGRADE_AVAILABLE]);
    }

    /**
     * Check if there are updates (new core revision and/or module revisions)
     *
     * @return boolean
     */
    protected function areUpdatesAvailable()
    {
        $flags = $this->getUpdateFlags();

        return !empty($flags[\XLite\Core\Marketplace::FIELD_ARE_UPDATES_AVAILABLE]);
    }

    /**
     * Return upgrade flags
     *
     * @return array
     */
    protected function getUpdateFlags()
    {
        if (!isset($this->updateFlags)) {
            $this->updateFlags = \XLite\Core\Marketplace::getInstance()->checkForUpdates();
        }

        return is_array($this->updateFlags) ? $this->updateFlags : array();
    }
}
