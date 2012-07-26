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

namespace XLite\Controller\Admin;

/**
 * Common promotions controller
 * 
 */
class Promotions extends \XLite\Controller\Admin\AAdmin
{
    /**
     * FIXME- backward compatibility
     *
     * @var array
     */
    protected $params = array('target', 'page');

    // {{{ Pages

    /**
     * Get pages static 
     * 
     * @return array
     */
    public static function getPagesStatic()
    {
        return array();
    }

    /**
     * Get pages sections
     *
     * @return array
     */
    public function getPages()
    {
        $list = array();

        foreach (static::getPagesStatic() as $key => $page) {
            $list[$key] = $page['name'];
        }

        return $list;
    }

    /**
     * Get pages templates
     *
     * @return array
     */
    protected function getPageTemplates()
    {
        $list = array();

        foreach (static::getPagesStatic() as $key => $page) {
            $list[$key] = $page['tpl'];
        }

        return $list;
    }

    // }}}
}
