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

namespace XLite\Base;

/**
 * Module patcher interface
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
interface IPatcher
{
    /**
     *  Common patch record cell names
     */

    const PATCHER_CELL_TYPE = 'type';
    const PATCHER_CELL_TPL  = 'tpl';


    /**
     * XPath-based patch specific record cell names 
     */

    const XPATH_CELL_QUERY       = 'query';
    const XPATH_CELL_INSERT_TYPE = 'insertType';
    const XPATH_CELL_BLOCK       = 'block';


    /**
     * XPath-based patch insertion mode
     */

    const XPATH_INSERT_BEFORE    = 'before';
    const XPATH_INSERT_AFTER     = 'after';
    const XPATH_REPLACE          = 'replace';


    /**
     * Regular expression-based patch specific record cell names
     */

    const REGEXP_CELL_PATTERN = 'pattern';
    const REGEXP_CELL_REPLACE = 'replace';

    /**
     * Callback-based patch specific record cell names 
     */

    const CUSTOM_CELL_CALLBACK = 'callback';


    /**
     * Patch types
     */

    const PATCH_TYPE_XPATH  = 'xpath';
    const PATCH_TYPE_REGEXP = 'regexp';
    const PATCH_TYPE_CUSTOM = 'custom';


    /**
     * Interface codes
     */

    const INTERFACE_ADMIN    = 'admin';
    const INTERFACE_CUSTOMER = 'customer';


    /**
     * Get patches 
     * 
     * @return array(array)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getPatches();
}
