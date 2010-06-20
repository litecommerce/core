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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Check gift certificate widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GiftCertificates_View_CheckGiftCertificate extends XLite_View_Dialog
{
    /**
     * Found gift certificate
     * 
     * @var    XLite_Module_GiftCertificates_Model_GiftCertificate
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $foundgc = null;

    /**
     * Gift certificate human-readable statuses
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $statuses = array(
        'P' => 'Pending',
        'A' => 'Active',
        'D' => 'Disabled',
        'U' => 'Used',
    );

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Verify gift certificate';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'modules/GiftCertificates/check_gift_certificate';
    }

    /**
     * Get found gift certificate
     * 
     * @return XLite_Module_GiftCertificates_Model_GiftCertificate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFoundGc()
    {
        if (is_null($this->foundgc) && XLite_Core_Request::getInstance()->gcid) {
            $this->foundgc = new XLite_Module_GiftCertificates_Model_GiftCertificate(
                XLite_Core_Request::getInstance()->gcid
            );
        }

        return $this->foundgc;
    }

    /**
     * Check - gift certificate found or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isFound()
    {
        return XLite_Core_Request::getInstance()->gcid ? $this->getFoundGc()->isExists() : false;
    }

    /**
     * Get gift certificate human-readable status 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStatus()
    {
        $status = $this->getFoundGc()->get('status');

        return isset($this->statuses[$status]) ? $this->statuses[$status] : 'Unknown';
    }

    /**
     * Get gift certificate id for input box
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getGcIdValue()
    {
        return XLite_Core_Request::getInstance()->gcid
            ? XLite_Core_Request::getInstance()->gcid
            : 'Gift certificate number';
    }

    /**
     * Check - can apply gift certificate ro cart or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function canApply()
    {
        return 'A' == $this->getFoundGc()->get('status')
            && XLite::getController()->getCart()->canApplyGiftCertificate();
    }

    /**
     * Get button label
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getButtonLabel()
    {
        return 'Verify';
    }


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'check_gift_certificate';
    
        return $result;
    }
}
