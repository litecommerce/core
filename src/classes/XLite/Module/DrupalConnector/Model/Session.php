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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_Module_DrupalConnector_Model_Session extends XLite_Model_Session implements XLite_Base_IDecorator
{
    /**
     * Return path for cookies
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getPath()
    {
        return XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS()
            ? base_path() 
            : parent::getPath();
    }

    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        parent::__construct();

        if (defined('LC_CONNECTOR_INITIALIZED')) {
            $this->options['https_host'] = $_SERVER['HTTP_HOST'];
            $this->options['http_host']  = $_SERVER['HTTP_HOST'];

            $url = parse_url($_SERVER['REQUEST_URI']);

            $this->options['web_dir']    = $url['path'];
            $this->options['web_dir_wo_slash'] = preg_replace('/\/$/Ss', '', $this->options['web_dir']);
        }
    }

    /**
     * Return pointer to the single instance of current class
     *
     * @param string $className name of derived class
     *
     * @return XLite_Base_Singleton
     * @access protected
     * @see    ____func_see____
     * @since  3.0
     */
    protected static function getInternalInstance($className)
    {
        return parent::getInternalInstance($className . '_' . LC_SESSION_TYPE);
    }

    /**
     * Destructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __destruct()
    {
        $this->writeClose();
    }
}
