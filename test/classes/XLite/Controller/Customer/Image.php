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
 * Image viewer
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Customer_Image extends XLite_Controller_Customer_Abstract
{
    /**
     * Controller parameters 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', 'action', 'id');

    /**
     * Handles the request. Parses the request variables if necessary. Attempts to call the specified action function
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        $this->startImage();

        $id = XLite_Core_Request::getInstance()->id;

        if (isset(XLite_Core_Request::getInstance()->product_id)) {
            $id = XLite_Core_Request::getInstance()->product_id;

        } elseif (isset(XLite_Core_Request::getInstance()->category_id)) {
            $id = XLite_Core_Request::getInstance()->category_id;
        }

        $image = new XLite_Model_Image(
            XLite_Core_Request::getInstance()->action,
            $id
        );
        $image->show();
    }
}
