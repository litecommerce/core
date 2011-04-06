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
 * @since     3.0.0
 */

namespace XLite\View\Payment;

/**
 * Payment methods list
 * 
 * @see   ____class_see____
 * @since 3.0.0
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class Methods extends \XLite\View\Dialog
{
    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();

        $result[] = 'payment_methods';

        return $result;
    }

    /**
     * Get payment methods list
     * 
     * @return \Doctrine\Common\Collections\Collection
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPaymentMethods()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findAllMethods();
    }

    /**
     * Get method name
     * 
     * @param \XLite\Model\Payment\Method $method Method
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
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
     * @see    ____func_see____
     * @since  3.0.0
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isMethodEnabled(\XLite\Model\Payment\Method $method)
    {
        return (bool)$method->getEnabled();
    }

    /**
     * Check - method is configurable or not
     * 
     * @param \XLite\Model\Payment\Method $method Method
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isMethodConfigurable(\XLite\Model\Payment\Method $method)
    {
        return (bool)$method->getProcessor()->getSettingsWidget();
    }


    /**
     * Get current language code
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLanguage()
    {
        $language = \XLite\Core\Request::getInstance()->language;

        return $language ? $language : \XLite\Core\Translation::getCurrentLanguageCode();
    }

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHead()
    {
        return null;
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'payment/methods';
    }
}
