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

namespace XLite\View\Payment;

/**
 * Payment methods list
 */
class Methods extends \XLite\View\Dialog
{
    /**
     * Get payment methods list
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $list = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findAllMethods();

        foreach ($list as $i => $method) {
            if (!$method->getProcessor()) {
                unset($list[$i]);
            }
        }

        return $list;
    }

    /**
     * Get method name
     *
     * @param \XLite\Model\Payment\Method $method Method
     *
     * @return string
     */
    public function getMethodName(\XLite\Model\Payment\Method $method)
    {
        return $method->getSoftTranslation($this->getLanguage())->getName();
    }

    /**
     * Get method description
     *
     * @param \XLite\Model\Payment\Method $method Method
     *
     * @return string
     */
    public function getMethodDescription(\XLite\Model\Payment\Method $method)
    {
        return $method->getSoftTranslation($this->getLanguage())->getDescription();
    }

    /**
     * Check - method is enabled or not
     *
     * @param \XLite\Model\Payment\Method $method Method
     *
     * @return boolean
     */
    public function isMethodEnabled(\XLite\Model\Payment\Method $method)
    {
        return (bool) $method->getEnabled();
    }

    /**
     * Check - method is configurable or not
     *
     * @param \XLite\Model\Payment\Method $method Method
     *
     * @return boolean
     */
    public function isMethodConfigurable(\XLite\Model\Payment\Method $method)
    {
        return $method->getProcessor() && $method->getProcessor()->getSettingsWidget();
    }

    /**
     * Check - method has module settings
     *
     * @param \XLite\Model\Payment\Method $method Method
     *
     * @return boolean
     */
    public function isModuleConfigurable(\XLite\Model\Payment\Method $method)
    {
        return $method->getProcessor()->hasModuleSettings() && $this->getModuleURL($method);
    }

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/style.css';

        return $list;
    }

    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/controller.js';

        return $list;
    }

    /**
     * Get module settings URL
     *
     * @param \XLite\Model\Payment\Method $method Method
     *
     * @return string
     */
    protected function getModuleURL(\XLite\Model\Payment\Method $method)
    {
        return $method->getConfigurationURL();
    }

    /**
     * Get module name
     *
     * @param \XLite\Model\Payment\Method $method Method
     *
     * @return string
     */
    protected function getModuleName(\XLite\Model\Payment\Method $method)
    {
        return $method->getProcessor()->getModule()
            ? $method->getProcessor()->getModule()->getModuleName()
            : null;
    }

    /**
     * Get current language code
     *
     * @return string
     */
    protected function getLanguage()
    {
        return \XLite\Core\Request::getInstance()->language
            ?: \XLite\Core\Session::getInstance()->getLanguage()->getCode();
    }

    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDir()
    {
        return 'payment/appearance';
    }
}
