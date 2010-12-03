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

namespace XLite\Module\CDev\GiftCertificates\Controller\Customer;

/**
 * Checkoput controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Checkout extends \XLite\Controller\Customer\Checkout
implements \XLite\Base\IDecorator
{
    /**
     * Remove applied gift certificate
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionRemoveGc()
    {
        $this->getCart()->setGC(null);
        $this->getCart()->update();
    }

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

        parent::doActionPayment();
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

            $gc = new \XLite\Module\CDev\GiftCertificates\Model\GiftCertificate();

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
