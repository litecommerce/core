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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_Controller_Customer_Main 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Customer_Main extends XLite_Controller_Customer_ACustomer
{
    /**
     * params 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', "page");

    /**
     * handleRequest 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest()
    {
        if ($this->config->General->add_on_mode) {

            // switch to cart in Add-on mode
            $addOnModePage = $this->config->General->add_on_mode_page;

            if ($addOnModePage != "cart.php") {
                $this->redirect($addOnModePage);

            } else {
            	parent::handleRequest();
            }

        } else {
            parent::handleRequest();
        }
    }

    /**
     * init 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function init(array $params = array())
    {
        parent::init($params);

        if (!isset(XLite_Core_Request::getInstance()->action)) {
            $this->session->set('productListURL', $this->get('url'));
        }
    }

    /**
     * getExtraPage 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getExtraPage()
    {
        if (is_null($this->extraPage)) {

            $this->extraPage = new XLite_Model_ExtraPage();

            if (isset(XLite_Core_Request::getInstance()->page) && !empty(XLite_Core_Request::getInstance()->page)) {
                $this->extraPage = $this->extraPage->findPage(XLite_Core_Request::getInstance()->page);
            }
        }

        return $this->extraPage;
    }
}

