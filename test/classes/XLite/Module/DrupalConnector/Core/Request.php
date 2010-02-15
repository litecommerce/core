<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Request
 *  
 * @category  Litecommerce
 * @package   Core
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Request 
 * 
 * @package    Core
 * @since      3.0.0
 */
class XLite_Module_DrupalConnector_Core_Request extends XLite_Core_Request implements XLite_Base_IDecorator
{
    /**
     * Method to access the singleton 
     * 
     * @return XLite_Core_CMSConnector
     * @access public
     * @since  3.0
     */
    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }

    /**
     * Remap request data
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function remapRequest()
    {
        $this->data = array();

        if (preg_match('/\?q=store\/(.+)$/Ss', $_SERVER['REQUEST_URI'], $match)) {
            $match = explode('/', $match[1]);

            $this->data['target'] = isset($match[0]) ? $match[0] : null;
            $this->data['action'] = isset($match[1]) ? $match[1] : null;

            for ($i = 2; $i < count($match); $i++) {
                $pair = explode('-', $match[$i], 2);
                if (count($pair) == 2) {
                    $this->data[$pair[0]] = $pair[1];
                }
            }
        }
    }
}

