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

namespace XLite\View;

/**
 * CoreVersionTopLink 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class CoreVersionTopLink extends \XLite\View\AView
{
    /**
     * Flags 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $updateFlags;


    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'top_links' . LC_DS . 'version_notes' . LC_DS . 'body.tpl';
    }

    /**
     * Check widget visibility
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible() && \XLite\Core\Auth::getInstance()->isLogged();
    }

    /**
     * Alias
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCurrentCoreVersion()
    {
        return \XLite::getInstance()->getVersion();
    }

    /**
     * Check if there is a new core version
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isCoreUpgradeAvailable()
    {
        return (bool) \Includes\Utils\ArrayManager::getIndex(
            $this->getUpdateFlags(),
            \XLite\Core\Marketplace::FIELD_IS_UPGRADE_AVAILABLE,
            true
        );
    }

    /**
     * Check if there are updates (new core revision and/or module revisions)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function areUpdatesAvailable()
    {
        return (bool) \Includes\Utils\ArrayManager::getIndex(
            $this->getUpdateFlags(),
            \XLite\Core\Marketplace::FIELD_ARE_UPDATES_AVAILABLE,
            true
        );
    }

    /**
     * Return upgrade flags
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getUpdateFlags()
    {
        if (!isset($this->updateFlags)) {
            $this->updateFlags = \XLite\Core\Marketplace::getInstance()->checkForUpdates();
        }

        return $this->updateFlags;
    }
}
