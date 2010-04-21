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
 * Checkoput controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GiftCertificates_Controller_Customer_Checkout extends XLite_Controller_Customer_Checkout
implements XLite_Base_IDecorator
{

    /**
     * Set payment method
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionPayment()
    {
        $this->checkCertificatesExpiration();

        parent::action_payment();
    }

    /**
     * Check gift certificate expiration 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkCertificatesExpiration()
    {
        if ($this->config->GiftCertificates->expiration_email) {

            $gc = new XLite_Module_GiftCertificates_Model_GiftCertificate();

            $gcs = $gc->findAll(
                implode(' AND ', $gc->getExpirationConditions()),
                'expiration_date',
                null,
                10
            );

            foreach ($gcs as $gc) {
                $gc->isDisplayWarning();
            }
        }
    }
}
