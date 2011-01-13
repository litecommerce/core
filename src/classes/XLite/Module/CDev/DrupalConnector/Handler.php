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

namespace XLite\Module\CDev\DrupalConnector;

/**
 * CMS connector
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Handler extends \XLite\Core\CMSConnector
{
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
     * Return the default controller name 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getDefaultTarget()
    {
        return 'drupal';
    }

    /**
     * Method to get raw Drupal request arguments
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getArgs()
    {
        return arg();
    }

    /**
     * Check if current page is an LC portal
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPortal()
    {
        $result = array(null, null, array());
        $item   = menu_get_item();

        if ($item && !empty($item['path'])) {

            $result[0] = \XLite\Module\CDev\DrupalConnector\Drupal\Module::getInstance()->getPortal($item['path']);

            if ($result[0]) {
                $result[1] = $item['path'];
                $result[2] = empty($item['page_arguments']) ? array() : $item['page_arguments'];
            }
        }

        return $result;
    }

    /**
     * Check if current page is an LC controller
     *
     * @param array &$args Request arguments
     *
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isController(array &$args)
    {
        return \XLite\Module\CDev\DrupalConnector\Core\Converter::DRUPAL_ROOT_NODE === array_shift($args);
    }

    /**
     * Return portal arguments
     *
     * @param \XLite\Module\CDev\DrupalConnector\Model\Portal $portal   Portal model object
     * @param string                                          $path     Portal path
     * @param array                                           $args     Drupal URL arguments
     * @param array                                           $pageArgs Drupal page arguments
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPortalArgs(
        \XLite\Module\CDev\DrupalConnector\Model\Portal $portal,
        $path,
        array $args,
        array $pageArgs = array()
    ) {
        return $portal->getLCArgs($path, $args, \Includes\Utils\Converter::parseArgs($pageArgs), '-');
    }

    /**
     * Return controller arguments
     *
     * @param array $args Drupal URL arguments
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getControllerArgs(array $args)
    {
        $result = array();

        foreach (array('target', 'action') as $param) {
            $result[$param] = empty($args) ? '' : array_shift($args);
        }

        return array_merge($result, \Includes\Utils\Converter::parseArgs($args, '-'), $_POST);
    }

    /**
     * Return default arguments
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultArgs()
    {
        return array('target' => $this->getDefaultTarget());
    }

    /**
     * Translate Drupal request into LC format
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLCArgs()
    {
        $result = array();
        $args   = $this->getArgs();

        list($portal, $path, $pageArgs) = $this->getPortal();
        
        if ($portal) {

            // Portal (LC controller with custom URL)
            $result += $this->getPortalArgs($portal, $path, $args, $pageArgs);

        } elseif ($this->isController($args)) {

            // Regular LC controller
            $result += $this->getControllerArgs($args);

        } else {

            // Non-LC page
            $result += $this->getDefaultArgs();
        }

        return $result;
    }

    /**
     * Get portal object by path
     * 
     * @param string $path Path to compare
     *  
     * @return \XLite\Module\CDev\DrupalConnector\Model\Portal
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPortalByPath($path)
    {
        return \XLite\Module\CDev\DrupalConnector\Drupal\Module::getInstance()->getPortal($path);
    }

    /**
     * Get portal object by target
     * 
     * @param string $target Target to search
     *  
     * @return \XLite\Module\CDev\DrupalConnector\Model\Portal
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPortalByTarget($target)
    {
        $class = \XLite\Core\Converter::getControllerClass($target);

        $portals = array_filter(
            \XLite\Module\CDev\DrupalConnector\Drupal\Module::getInstance()->getPortals(),
            function (\XLite\Module\CDev\DrupalConnector\Model\Portal $portal) use ($class) {
                return $portal->getController() === $class;
            }
        );

        return is_array($portals) ? array_shift($portals) : null;
    }

    /**
     * Initialization
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function init()
    {
        parent::init();

        $this->mapRequest($this->getLCArgs());
    }
}
