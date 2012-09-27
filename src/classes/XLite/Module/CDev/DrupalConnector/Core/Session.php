<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\DrupalConnector\Core;

/**
 * Session
 *
 */
abstract class Session extends \XLite\Core\Session implements \XLite\Base\IDecorator
{
    /**
     * Get session TTL (seconds)
     *
     * @return integer
     */
    public static function getTTL()
    {
        $ttl = intval(ini_get('session.cookie_lifetime'));

        return 0 < $ttl ? time() + $ttl : 0;
    }

    /**
     * Get URL path for Set-Cookie
     *
     * @param boolean $secure Secure protocol or not OPTIONAL
     *
     * @return string
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
     */
    protected function getCookieURL($secure = false)
    {
        // FIXME
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
     */
    protected function getCurrentLanguage()
    {
        // DO NOT change call order here
        if (!\XLite::isAdminZone() && function_exists('drupal_multilingual') && drupal_multilingual()) {
            global $language;

            if ($language instanceof \stdClass) {
                $object = \XLite\Core\Database::getRepo('XLite\Model\Language')->findOneByCode($language->language);

                // DO NOT use "===" here
                if (isset($object) && \XLite\Model\Language::ENABLED == $object->getStatus()) {
                    $result = $object->getCode();
                }
            }
        }

        return isset($result) ? $result : parent::getCurrentLanguage();
    }
}
