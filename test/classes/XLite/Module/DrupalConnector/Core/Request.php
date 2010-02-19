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
     * Drupla-specific URL param 
     */
    CONST DRUPAL_NODE_PARAM = 'q';


    /**
     * Get target, action and query params from the Drupal URL
     * 
     * @param string $url Drupal URL to parse
     *  
     * @return array
     * @access protected
     * @since  3.0.0 EE
     */
    protected function parseDrupalNodeURL($url)
    {
        $result = array();

        // Search for the certain node
        if (preg_match('/' . XLite_Module_DrupalConnector_Core_Handler::DRUPAL_ROOT_NODE . '\/(.+)$/Ss', $url, $match)) {

            $match = explode('/', $match[1]);

            // Get target and action
            foreach (array('target', 'action') as $param) {
                $result[$param] = array_shift($match); 
            }

            // Fetch additional params
            foreach ($match as $param) {
                list($name, $value) = explode('-', $param, 2);
                if (!empty($name)) {
                    $result[$name] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Propare data before save
     *
     * @param mixed $data data to prepare
     *
     * @return mixed
     * @access protected
     * @since  3.0.0 EE
     */
    protected function prepare($data)
    {
        $data = parent::prepare($data);

        if (is_array($data) && isset($data[self::DRUPAL_NODE_PARAM])) {

            $data += $this->parseDrupalNodeURL($data[self::DRUPAL_NODE_PARAM]);
            unset($data[self::DRUPAL_NODE_PARAM]);
        }

        return $data;
    }


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
}

