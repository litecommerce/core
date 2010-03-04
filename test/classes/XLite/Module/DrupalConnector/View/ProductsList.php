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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Products list
 * 
 * @package    XLite
 * @subpackage View
 * @since      3.0.0 EE
 */
class XLite_Module_DrupalConnector_View_ProductsList extends XLite_View_ProductsList implements XLite_Base_IDecorator
{
    /**
     * Input arguments (AJAX) 
     */
    const BLOCK_DELTA_ARG = 'blockDelta';

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        if (XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS()) {
            $list[] = 'modules/DrupalConnector/products_list.js';
        }

        return $list;
    }

    /**
     * Get AJAX specific parameters 
     * 
     * @param array $params Parameters
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAJAXSpecificParams(array $params)
    {
        $data = parent::getAJAXSpecificParams($params);

        if (XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS()) {
            $data[self::BLOCK_DELTA_ARG] = self::PATTERN_BORDER_SYMBOL . self::BLOCK_DELTA_ARG . self::PATTERN_BORDER_SYMBOL;   
        }

        return $data;
    }

    /**
     * Get URL translation table
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getURLTranslationTable()
    {
        $list = parent::getURLTranslationTable();

        if (XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS()) {
            $list['blockDelta'] = self::BLOCK_DELTA_ARG;
        }

        return $list;
    }
}

