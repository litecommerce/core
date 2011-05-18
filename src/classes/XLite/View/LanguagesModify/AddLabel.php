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
 * Add new label dialog
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class AddLabel extends \XLite\View\AView
{
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
     * Check - is requried language or not
     *
     * @param \XLite\Model\Language $language Language_
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isRequiredLanguage(\XLite\Model\Language $language)
    {
        return $language->code == $this->getDefaultLanguage()->code;
    }

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
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'languages/add_label.tpl';
    }
}
