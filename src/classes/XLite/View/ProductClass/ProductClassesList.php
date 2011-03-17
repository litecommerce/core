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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\ProductClass;

/**
 * Product classes list 
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class ProductClassesList  extends AProductClass
{
    /**
     * Return allowed targets
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return parent::getDir() . LC_DS . 'list';
    }


    /**
     * Return data 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->findAll();
    }


}
