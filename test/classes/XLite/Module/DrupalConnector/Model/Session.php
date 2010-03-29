<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Drupal-specific session
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage Module DrupalConnector
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */


/**
 * Drupal-specific session 
 * 
 * @package    Lite Commerce
 * @subpackage Module DrupalConnector
 * @since      3.0.0
 */
abstract class XLite_Module_DrupalConnector_Model_Session extends XLite_Model_Session implements XLite_Base_IDecorator
{
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
    protected static function _getInstance($className)
    {
		return parent::_getInstance($className . '_' . LC_SESSION_TYPE);
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
