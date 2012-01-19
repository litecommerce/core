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
 * Language selector for editor page
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class EditorLanguageSelector extends \XLite\View\AView
{
    /**
     * Current language code
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $currentCode = null;

    /**
     * Enabled languages (cache)
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $activeLanguages = null;


    /**
     * Get languages list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getLanguages()
    {
        if (!isset($this->activeLanguages)) {

            $list = array();

            foreach (\XLite\Core\Database::getRepo('\XLite\Model\Language')->findActiveLanguages() as $l) {
                $list[$l->getCode()] = $l->getName();
            }

            $this->activeLanguages = $list;
        }

        return $this->activeLanguages;
    }

    /**
     * Check - language is selected or not
     *
     * @param string $code Language code
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isLanguageSelected($code)
    {
        if (!isset($this->currentCode)) {
            $this->currentCode = \XLite::getController()->getCurrentLanguage();
        }

        return $code == $this->currentCode;
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
        return 'common/language_selector_edit.tpl';
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
        return 1 < count($this->getLanguages());
    }
}
