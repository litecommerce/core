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

namespace XLite\View\ProductClass;

/**
 * Product classes list
 *
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class ProductClassesList  extends AProductClass
{
    /**
     * Return allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();

        $result[] = 'product_classes';

        return $result;
    }

    /**
     * Return CSS files list for widget
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . LC_DS . 'css' . LC_DS . 'style.css';

        return $list;
    }

    /**
     * Return JS files list for widget
     *
     * @return void
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = $this->getDir() . LC_DS . 'js' . LC_DS . 'script.js';

        return $list;
    }


    /**
     * Return templates catalog
     *
     * @return string
     */
    protected function getDir()
    {
        return parent::getDir() . LC_DS . 'list';
    }

    /**
     * Return data
     *
     * @return array
     */
    protected function getData()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->findAll();
    }
}
