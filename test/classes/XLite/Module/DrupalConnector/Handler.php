<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Handler to use in Drupal
 *  
 * @category  Litecommerce
 * @package   DrupalConnector
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Handler to use in Drupal 
 * 
 * @package    Lite Commerce
 * @subpackage DrupalConnector
 * @since      3.0
 */
class XLite_Module_DrupalConnector_Handler extends XLite_Core_CMSConnector
{
	/**
	 * Semaphore 
	 * 
	 * @var    bool
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected static $isRequestRemapped = false;

    /**
     * Relative path
     * 
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected static $resourceRelativePath = null;

	/**
     * Method to access the singleton
     *
     * @return XLite_Module_DrupalConnector_Handler
     * @access public
     * @since  3.0
     */
    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }

	/**
	 * Return name of current CMS 
	 * 
	 * @return string
	 * @access public
	 * @since  1.0.0
	 */
	public function getCMSName()
	{
		return '____DRUPAL____';
	}

    /**
     * Get landing link 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0 EE
     */
    public function getLandingLink()
    {
		$link = new XLite_Module_DrupalConnector_Model_LandingLink();
		$link->create();

		return $link->getLink();
    }

	/**
     * Get translation table for prfile data
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0 EE
     */
    protected function getUserTranslationTable()
    {
        $table = parent::getUserTranslationTable();

        $table['pass'] = 'password';

        return $table;
    }

    /**
     * Prepare call
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function prepareCall()
	{
		if (!self::$isRequestRemapped) {
			XLite_Core_Request::getInstance()->remapRequest();
			self::$isRequestRemapped = true;
		}
	}

    /**
     * Prepare widget resources 
     * 
     * @param array $resources Resources
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareResources(array $resources)
    {
        $resources = parent::prepareResources($resources);

		if (is_null(self::$resourceRelativePath)) {
        	self::$resourceRelativePath = $this->getRelativePath(base_path());
		}

        foreach (array('css', 'js') as $res) {
            foreach ($resources[$res] as $k => $v) {
                $resources[$res][$k] = self::$resourceRelativePath . $this->layoutPath . $v;
            }
        }

        return $resources;
    }

    /**
     * Return a relative path from a web directory path to the XLite web directory
     *
     * @param string $web_dir The web directory from which a relative path to the XLite web directory is needed
     *
     * @return string
     * @access protected
     */
    protected function getRelativePath($web_dir)
	{
        // Remove a trailing slash (if any)
        if (substr($web_dir, -1) == '/') {
            $web_dir = substr($web_dir, 0, -1);
		}

        $base_path = explode('/', $web_dir);
        $xlite_path = explode('/', XLite::getInstance()->getOptions(array('host_details', 'web_dir')));

        $i = 0;
        $c1 = count($base_path);
        $c2 = count($xlite_path);

        // Count and skip common parts of the directories
        for ($i = 0; ($i < $c1) && ($i < $c2); $i++) {
            if ($base_path[$i] != $xlite_path[$i]) {
                break;

            } else {
                unset($xlite_path[$i]);
			}
		}

        return str_repeat('../', count($base_path) - $i - 1) . join('/', $xlite_path) . '/';
    }
}

