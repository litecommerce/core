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
 * @since     1.0.17
 */

namespace XLite\Module\CDev\ContactUs\View;

/**
 * Contact us widget
 * 
 * @see   ____class_see____
 * @since 1.0.17
 *
 * @ListChild (list="center", zone="customer")
 */
class ContactUs extends \XLite\View\AView
{
    /**
     * Return list of allowed targets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        return array_merge(parent::getAllowedTargets(), array('contact_us'));
    }

   /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'modules/CDev/ContactUs/contact_us/style.css';

        return $list;
    }

    /**
     * Return captcha 
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCaptcha()
    {
        $config = \XLite\Core\Config::getInstance()->CDev->ContactUs;
        $result = '';

        if (
            $config->recaptcha_private_key
            && $config->recaptcha_public_key
        ) {
            require_once LC_DIR_MODULES . '/CDev/ContactUs/recaptcha/recaptchalib.php';
            $result = recaptcha_get_html($config->recaptcha_public_key);
        }

        return $result;
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
        return 'modules/CDev/ContactUs/contact_us/body.tpl';
    }

    /**
     * Return widget description
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDescription()
    {
        return \XLite\Core\Config::getInstance()->CDev->ContactUs->page_description;
    }

}
