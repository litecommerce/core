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

namespace XLite\Module\CDev\SimpleCMS\Core;

/**
 * Miscelaneous convertion routines
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Converter extends \XLite\Core\Converter implements \XLite\Base\IDecorator
{
    /**
     * Get clean URL book
     *
     * @param string $url  Main part of a clean URL
     * @param string $last First part before the "url" OPTIONAL
     * @param string $rest Part before the "url" and "last" OPTIONAL
     * @param string $ext  Extension OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected static function getCleanURLBook($url, $last = '', $rest = '', $ext = '')
    {
        $list = parent::getCleanURLBook($url, $last, $rest, $ext);

        $list['page'] = '\XLite\Module\CDev\SimpleCMS\Model\Page';

        return $list;
    }

    /**
     * Compose clean URL
     *
     * @param string $target Page identifier OPTIONAL
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.21
     */
    public static function buildCleanURL($target = '', $action = '', array $params = array())
    {
        $result = null;
        $urlParams = array();

        if ('page' === $target && !empty($params['id'])) {
            $page = \XLite\Core\Database::getRepo('\XLite\Module\CDev\SimpleCMS\Model\Page')->find($params['id']);

            if (isset($page) && $page->getCleanURL()) {
                $urlParams[] = $page->getCleanURL() . '.html';

                unset($params['id']);
            }
        }

        if (!empty($urlParams)) {
			static::buildCleanURLHook($target, $action, $params, $urlParams);

            unset($params['target']);

            $result  = \Includes\Utils\ConfigParser::getOptions(array('host_details', 'web_dir_wo_slash'));
            $result .= '/' . implode('/', array_reverse($urlParams));

            if (!empty($params)) {
                $result .= '?' . http_build_query($params);
            }
        }

        return $result ?: parent::buildCleanURL($target, $action, $params);
    }

}
