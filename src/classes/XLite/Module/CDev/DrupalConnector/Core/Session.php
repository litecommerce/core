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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Core;

/**
 * Session
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Session extends \XLite\Core\Session implements \XLite\Base\IDecorator
{
    /**
     * Get URL path for Set-Cookie
     *
     * @param boolean $secure Secure protocol or not OPTIONAL
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCookiePath($secure = false)
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            ? base_path()
            : parent::getCookiePath();
    }

    /**
     * Get parsed URL for Set-Cookie
     *
     * @param boolean $secure Secure protocol or not OPTIONAL
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCookieURL($secure = false)
    {
        if (defined('LC_CONNECTOR_INITIALIZED')) {
            $url = ($secure ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $url = parse_url($url);

        } else {
            $url = parent::getCookieURL($secure);
        }

        return $url;
    }

    /**
     * Get current language
     *
     * @return string Language code
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCurrentLanguage()
    {
        global $language;

        $result = null;

        if (
            isset($language)
            && is_object($language)
            && $language instanceof \stdClass
        ) {
            $lng = \XLite\Core\Database::getRepo('XLite\Model\Language')->findOneByCode($language->language);
            if ($lng) {
                $result = $language->language;
                $lng->detach();
            }
        }

        return isset($result)
            ? $result
            : parent::getCurrentLanguage();
    }
}
