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
 * Top-right side drop down links
 *
 */
class TopLinks extends \XLite\View\AView
{
    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'top_links/style.css';

        return $list;
    }

    /**
     * Return widget directory
     *
     * @return string
     */
    protected function getDir()
    {
        return 'top_links';
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/top_links.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return \XLite\Core\Auth::getInstance()->isLogged();
    }

    /**
     * Check if storefront menu section visible in the top links
     *
     * @return boolean
     */
    protected function isStorefrontMenuVisible()
    {
        return true;
    }

    /**
     * Check ACL permissions for Login history link
     *
     * @return boolean
     */
    public function checkLoginHistoryACL()
    {
        $auth = \XLite\Core\Auth::getInstance();

        return $auth->isPermissionAllowed(\XLite\Model\Role\Permission::ROOT_ACCESS)
            || $auth->isPermissionAllowed('manage users');
    }

    /**
     * Check ACL permissions for Common links
     *
     * @return boolean
     */
    public function checkCommonACL()
    {
        return \XLite\Core\Auth::getInstance()->isPermissionAllowed(\XLite\Model\Role\Permission::ROOT_ACCESS);
    }

    // {{{ Language-related routines

    /**
     * Check if language selector is visible
     *
     * @return boolean
     */
    protected function isLanguageSelectorVisible()
    {
        return 1 < count($this->getActiveLanguages());
    }

    /**
     * Return list of all active languages
     *
     * @return array
     */
    protected function getActiveLanguages()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Language')->findActiveLanguages();
    }

    /**
     * Link to change language
     *
     * @param \XLite\Model\Language $language Language to set
     *
     * @return string
     */
    protected function getChangeLanguageLink(\XLite\Model\Language $language)
    {
        $result = '#';

        if (!$this->isLanguageSelected($language)) {
            $result = $this->buildURL(
                $this->getTarget(),
                'change_language',
                array('language' => $language->getCode()) + $this->getAllParams()
            );
        }

        return $result;
    }

    /**
     * Link CSS class
     *
     * @param \XLite\Model\Language $language Current language
     *
     * @return string
     */
    protected function getChangeLanguageLinkClass(\XLite\Model\Language $language)
    {
        return $this->isLanguageSelected($language) ? 'text' : '';
    }

    /**
     * Check if language is selected
     *
     * @param \XLite\Model\Language $language Language to check
     *
     * @return boolean
     */
    protected function isLanguageSelected(\XLite\Model\Language $language)
    {
        return $language->getCode() === \XLite\Core\Session::getInstance()->getLanguage()->getCode();
    }

    // }}}
}
