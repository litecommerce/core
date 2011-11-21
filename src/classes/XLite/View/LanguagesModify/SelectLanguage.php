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

namespace XLite\View\LanguagesModify;

/**
 * Select language dialog
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class SelectLanguage extends \XLite\View\AView
{
    /**
     * Translate language (cache)
     *
     * @var   \XLite\Model\Language
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $translateLanguage = null;


    /**
     * Get added languages
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAddedLanguages()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Language')->findAddedLanguages();
    }

    /**
     * Check - is interface language or not
     *
     * @param \XLite\Model\Language $language ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isInterfaceLanguage(\XLite\Model\Language $language)
    {
        return \XLite\Core\Config::getInstance()->General->defaultLanguage->code == $language->code;
    }

    /**
     * Check - is translate language or not
     *
     * @param \XLite\Model\Language $language Language
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isTranslateLanguage(\XLite\Model\Language $language)
    {
        return $this->getTranslatedLanguage()
            && $this->getTranslatedLanguage()->code == $language->code;
    }

    /**
     * Get application default language
     *
     * @return \XLite\Model\Language
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDefaultLanguage()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Language')->getDefaultLanguage();
    }

    /**
     * Check - specified language can been selected or not
     *
     * @param \XLite\Model\Language $language Language
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function canSelect(\XLite\Model\Language $language)
    {
        return $language->code != $this->getDefaultLanguage()->code
            && (!$this->getTranslatedLanguage() || $language->code != $this->getTranslatedLanguage()->code);
    }

    /**
     * Check - specified language can been deleted or not
     *
     * @param \XLite\Model\Language $language Language
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function canDelete(\XLite\Model\Language $language)
    {
        return !in_array(
            $language->code,
            array($this->getDefaultLanguage()->code, \XLite\Core\Config::getInstance()->General->defaultLanguage->code)
        );
    }

    /**
     * Get inactive languages
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getInactiveLanguages()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Language')
            ->findInactiveLanguages();
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
        return 'languages/select_language.tpl';
    }

    /**
     * Get translated language
     *
     * @return \XLite\Model\Language|boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTranslatedLanguage()
    {
        if (!isset($this->translateLanguage)) {
            if (\XLite\Core\Request::getInstance()->language) {
                $this->translateLanguage = \XLite\Core\Database::getRepo('\XLite\Model\Language')->findOneByCode(
                    \XLite\Core\Request::getInstance()->language
                );
                if (!$this->translateLanguage || !$this->translateLanguage->added) {
                    $this->translateLanguage = false;
                }
            }
        }

        return $this->translateLanguage;
    }

    /**
     * Check widget visibility
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getInactiveLanguages();
    }
}
