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
 * Miscelaneous convertion routines
 *
 */
class Converter extends \XLite\Core\Converter implements \XLite\Base\IDecorator
{
    /**
     * It's the the root part of Drupal nodes which are the imported LiteCommerce widgets
     */
    const DRUPAL_ROOT_NODE = 'store';

    /**
     * Special symbol for empty action
     */
    const EMPTY_ACTION = '0';

    /**
     * Compose URL from target, action and additional params
     *
     * @param string $target    Page identifier OPTIONAL
     * @param string $action    Action to perform OPTIONAL
     * @param array  $params    Additional params OPTIONAL
     * @param string $interface Interface script OPTIONAL
     *
     * @return string
     */
    public static function buildURL($target = '', $action = '', array $params = array(), $interface = null)
    {
        if ('' === $target) {
            $target = \XLite::TARGET_DEFAULT;
        }

        if (
            \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            || (\XLite::CART_SELF == $interface && \XLite\Module\CDev\DrupalConnector\Core\Caller::getInstance()->isInitialized())
        ) {

            $portal = \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getPortalByTarget($target);

            if ($portal) {

                // Drupal URL (portal)
                list($path, $args) = $portal->getDrupalArgs($target, $action, $params);
                $result = static::normalizeDrupalURL($path, $args);
            } else {

                // Drupal URL
                $result = static::buildDrupalURL($target, $action, $params);
            }
    
        } else {

            // Standalone URL
            $result = parent::buildURL($target, $action, $params, $interface);

        }

        return $result;
    }

    /**
     * Build Drupal path string
     *
     * @param string $target Target OPTIONAL
     * @param string $action Action OPTIONAL
     * @param array  $params Parameters list OPTIONAL
     * @param string $node   Node OPTIONAL
     *
     * @return string
     */
    public static function buildDrupalPath($target = '', $action = '', array $params = array(), $node = self::DRUPAL_ROOT_NODE)
    {
        if (empty($action) && $params) {
            $action = static::EMPTY_ACTION;
        }

        $url = implode('/', array($node, $target, $action));

        if ($params) {
            $url .= '/' . \Includes\Utils\Converter::buildQuery($params, '-', '/');
        }

        return $url;
    }

    /**
     * Compose URL from target, action and additional params
     *
     * @param string $target Page identifier OPTIONAL
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     * @param string $node   Node OPTIONAL
     *
     * @return string
     */
    public static function buildDrupalURL($target = '', $action = '', array $params = array(), $node = self::DRUPAL_ROOT_NODE)
    {
        return static::normalizeDrupalURL(static::buildDrupalPath($target, $action, $params, $node));
    }

    /**
     * Normalize Drupal URL to full path
     *
     * @param string $url  Short URL
     * @param array  $args Additional arguments OIPTIONAL
     *
     * @return string
     */
    protected static function normalizeDrupalURL($url, array $args = array())
    {
        return preg_replace(
            '/(\/)\%252F([^\/])/iSs',
            '\1/\2',
            \XLite\Module\CDev\DrupalConnector\Core\Caller::getInstance()->url($url, array('query' => $args))
        );
    }
}
