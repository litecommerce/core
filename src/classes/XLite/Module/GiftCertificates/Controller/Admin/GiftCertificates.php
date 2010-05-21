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
 * Gift certificates
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GiftCertificates_Controller_Admin_GiftCertificates extends XLite_Controller_Admin_Abstract
{
    /**
     * Gift certificates list
     * 
     * @var    array of XLite_Module_GiftCertificates_Model_GiftCertificate
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $giftCertificates = null;

    /**
     * Get gift certificates 
     * 
     * @return array of XLite_Module_GiftCertificates_Model_GiftCertificate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getGiftCertificates()
    {
        if (is_null($this->giftCertificates)) {
            $gc = new XLite_Module_GiftCertificates_Model_GiftCertificate();
            $this->giftCertificates = $gc->findAll('', 'add_date desc');
            foreach ($this->giftCertificates as $gc) {
                $gc->validate();
            }
        }

        return $this->giftCertificates;
    }
    
    /**
     * Update 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        if (
            isset(XLite_Core_Request::getInstance()->status)
            && is_array(XLite_Core_Request::getInstance()->status)
        ) {
            foreach (XLite_Core_Request::getInstance()->status as $gcid => $status) {
                $gc = new XLite_Module_GiftCertificates_Model_GiftCertificate($gcid);
                $gc->set('status', $status);
                $gc->update();
            }
        }
    }

    /**
     * Delete 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        $gc = new XLite_Module_GiftCertificates_Model_GiftCertificate(XLite_Core_Request::getInstance()->gcid);
        $gc->delete();
    }

    /**
     * Delete all gift certificates
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDeleteAll()
    {
        $gc = new XLite_Module_GiftCertificates_Model_GiftCertificate();
        $status = XLite_Core_Request::getInstance()->deleteStatus;
        $toDelete = $gc->iterate('status = \'' . $status . '\'');
        while ($gc->next($toDelete)) {
            $gc->delete();
        }
    }

}
