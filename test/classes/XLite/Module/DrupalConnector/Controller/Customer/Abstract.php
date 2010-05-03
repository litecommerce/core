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
 * Abstract controller (customer interface)
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_DrupalConnector_Controller_Customer_Abstract extends XLite_Controller_Customer_Abstract implements XLite_Base_IDecorator
{
    /**
     * Die if trying to access storefront and DrupalConnector module is enabled 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function checkStorefrontAccessability()
    {
        return parent::checkStorefrontAccessability() &&
            XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS();
    }

    /**
     * Return Drupal URL
     * 
     * @return string|null
     * @access protected
     * @since  3.0.0
     */
    protected function getDrupalLink()
    {
        return $this->config->DrupalConnector->drupal_root_url;
    }

    /**
     * Perform some actions to prohibit access to storefornt
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function closeStorefront()
    {
        $this->getDrupalLink() 
            ? XLite_Core_Operator::getInstance()->redirect($this->getDrupalLink()) 
            : parent::closeStorefront();
    }

    /**
     * Get external link 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getExternalLink()
    {
        if (XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS()) {
            $result = XLite_Core_Converter::buildDrupalURL(
                $this->getTarget(),
                '',
                $this->getParamsHash(array_keys($this->getWidgetSettings()))
            );

        } else {
            $result = parent::getExternalLink();
        }

        return $result;
    }
}

