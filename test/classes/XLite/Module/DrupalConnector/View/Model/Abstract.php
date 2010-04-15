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

/**
 * XLite_Module_DrupalConnector_View_Model_Abstract 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
abstract class XLite_Module_DrupalConnector_View_Model_Abstract extends XLite_View_Model_Abstract implements XLite_Base_IDecorator
{
    /**
     * Save form state in session
     *
     * @param array $data form fields
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function saveFormErrors(array $data)
    {
        parent::saveFormErrors($data);

		XLite_Core_TopMessage::getInstance()->addBatch(
			$this->getErrorMessages(),
			XLite_Core_TopMessage::ERROR
		);
    }

    /**
     * Perform some action on success
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function success()
    {
        parent::success();

        XLite_Core_TopMessage::getInstance()->add('Data have been saved successfully', XLite_Core_TopMessage::INFO);
    }
}

