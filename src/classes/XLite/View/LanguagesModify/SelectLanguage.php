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

namespace XLite\View\LanguagesModify;

/**
 * Select language dialog
 *
 */
class SelectLanguage extends \XLite\View\AView
{
    /**
     * Translate language (cache)
     *
     * @var \XLite\Model\Language
     */
    protected $translateLanguage = null;

    /**
     * Get added languages
     *
     * @return array
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
     */
    public function isInterfaceLanguage(\XLite\Model\Language $language)
    {
        return static::getDefaultLanguage() == $language->code;
    }

    /**
     * Check - is translate language or not
     *
     * @param \XLite\Model\Language $language Language
     *
     * @return boolean
     */
    public function isTranslateLanguage(\XLite\Model\Language $language)
    {
        return $this->getTranslatedLanguage()
            && $this->getTranslatedLanguage()->code == $language->code;
    }

    /**
     * Check - specified language can been selected or not
     *
     * @param \XLite\Model\Language $language Language
     *
     * @return boolean
     */
    public function canSelect(\XLite\Model\Language $language)
    {
        return $language->code !== static::getDefaultLanguage()
            && (!$this->getTranslatedLanguage() || $language->code != $this->getTranslatedLanguage()->code);
    }

    /**
     * Check - specified language can been deleted or not
     *
     * @param \XLite\Model\Language $language Language
     *
     * @return boolean
     */
    public function canDelete(\XLite\Model\Language $language)
    {
        return $language->code !== static::getDefaultLanguage();
    }

    /**
     * Get inactive languages
     *
     * @return array
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
     */
    protected function getDefaultTemplate()
    {
        return 'languages/select_language.tpl';
    }

    /**
     * Get translated language
     *
     * @return \XLite\Model\Language|boolean
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
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getInactiveLanguages();
    }
}
