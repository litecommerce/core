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

namespace XLite\View;

/**
 * Top-right side drop down links
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class TopLinks extends \XLite\View\AView
{
    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return 'top_links';
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/top_links.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return \XLite\Core\Auth::getInstance()->isLogged();
    }

    /**
     * Check if storefront menu section visible in the top links
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isStorefrontMenuVisible()
    {
        return true;
    }

    // {{{ Language-related routines

    /**
     * Check if language selector is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function isLanguageSelectorVisible()
    {
        return 1 < count($this->getActiveLanguages());
    }

    /**
     * Return list of all active languages
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
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
     * @see    ____func_see____
     * @since  1.0.19
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
     * @see    ____func_see____
     * @since  1.0.19
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
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function isLanguageSelected(\XLite\Model\Language $language)
    {
        return $language->getCode() === \XLite\Core\Session::getInstance()->getLanguage()->getCode();
    }

    // }}}
}
