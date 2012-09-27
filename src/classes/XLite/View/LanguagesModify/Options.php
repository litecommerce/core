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
 * Language options dialog
 *
 */
class Options extends \XLite\View\AView
{
    /**
     * Widget parameters
     */
    const PARAM_LNG_ID = 'lng_id';

    /**
     * Language (cache)
     *
     * @var \XLite\Model\Language
     */
    protected $editLanguage = null;

    /**
     * Get language
     *
     * @return \XLite\Model\Language|boolean
     */
    public function getEditLanguage()
    {
        if (!isset($this->label)) {
            if ($this->getParam(static::PARAM_LNG_ID)) {
                $this->editLanguage = \XLite\Core\Database::getRepo('\XLite\Model\Language')
                    ->find($this->getParam(static::PARAM_LNG_ID));

            } else {
                $this->editLanguage = false;
            }
        }

        return $this->editLanguage;
    }

    /**
     * Get language translation
     *
     * @param \XLite\Model\Language $language Translation language
     *
     * @return string
     */
    public function getTranslation(\XLite\Model\Language $language)
    {
        return strval($this->getEditLanguage()->getTranslation($language->code)->name);
    }

    /**
     * Check - can language disabled / enabled or not
     *
     * @return boolean
     */
    public function canSwitch()
    {
        return \XLite\Core\Config::getInstance()->General->default_language !== $this->getEditLanguage()->getCode();
    }

    /**
     * Check - can language deleted or not
     *
     * @return boolean
     */
    public function canDelete()
    {
        return $this->canSwitch() && static::getDefaultLanguage() !== $this->getEditLanguage()->getCode();
    }

    /**
     * Get wwicther block class
     *
     * @return string
     */
    public function getSwitcherClass()
    {
        $classes = array('switcher');

        if (!$this->canSwitch()) {
            $classes[] = 'switcher-default';

        } elseif ($this->getEditLanguage()->enabled) {
            $classes[] = 'switcher-enabled';

        } else {
            $classes[] = 'switcher-disabled';
        }

        return implode(' ', $classes);
    }


    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'languages/options.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_LNG_ID => new \XLite\Model\WidgetParam\Int(
                'Language id', \XLite\Core\Request::getInstance()->{static::PARAM_LNG_ID}
            ),
        );
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getEditLanguage();
    }
}
