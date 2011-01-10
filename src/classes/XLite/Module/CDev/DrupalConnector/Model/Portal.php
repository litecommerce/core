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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Model;

/**
 * So called "portal": custom Drupal node for LC controller
 *
 * TODO: if there will be more properties,
 * derive this class from the \Includes\DataStructure\Cell one
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Portal extends \XLite\Base\SuperClass
{
    /**
     * Drupal URL where the controller will be displayed
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $url;

    /**
     * Controller class name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $controller;

    /**
     * Portal type for Drupal
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $type = MENU_LOCAL_TASK;


    /**
     * Return portal default title
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTitle()
    {
        return '';
    }

    /**
     * Return portal default title callback
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTitleCallback()
    {
        return 'lcConnectorGetPortalTitle';
    }

    /**
     * Return portal default page content callback
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getContentCallback()
    {
        return 'lcConnectorGetPortalContent';
    }

    /**
     * Return portal default page access callback
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAccessCallback()
    {
        return 'lc_connector_check_controller_access';
    }

    /**
     * Constructor
     * 
     * @param string  $url        Drupal URL
     * @param string  $controller Controller class name
     * @param integer $type       Node type
     *  
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($url, $controller, $type = MENU_LOCAL_TASK)
    {
        // Check if we can replace second argument to the "\XLite\Controller\Customer\ACustomer"
        if (!is_subclass_of($controller, '\XLite\Controller\AController')) {
            \Includes\ErrorHandler::fireError('Portal class is not a controller one');
        }

        $this->url        = $url;
        $this->controller = $controller;
        $this->type       = $type;
    }

    /**
     * Getter
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * Getter
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Getter
     *
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Return menu node description in Drupal-specific form
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDrupalMenuDescription()
    {
        return array(
            'title'           => $this->getTitle(),
            'title callback'  => $this->getTitleCallback(),
            'page callback'   => $this->getContentCallback(),
            'access callback' => $this->getAccessCallback(),
            'type'            => $this->getType(),
        );
    }
}
