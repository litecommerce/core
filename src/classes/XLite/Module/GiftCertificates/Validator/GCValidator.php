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
 * @subpackage Validator
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\GiftCertificates\Validator;

/**
 * Gift certificate validator
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class GCValidator extends \XLite\Validator\AValidator
{
    /**
     * Validator template 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $template = 'modules/GiftCertificates/gc_validator.tpl';

    /**
     * Flag 'Gift ceritificate does not exists'
     * 
     * @var    boolean
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $doesnotexist = false;

    /**
     * Flag 'Gift certificate expired'
     * 
     * @var    boolean
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $expired = false;

    /**
     * Flag 'Gift certificate not active'
     * 
     * @var    boolean
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $notactive = false;

    /**
     * Gift certificate id 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $gcid = null;
    
    /**
     * isValid 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isValid()
    {
        $result = true;

        $fieldName = $this->get('field');

        if (!parent::isValid()) {
            $result = false;

        } elseif (isset(\XLite\Core\Request::getInstance()->$fieldName)) {

            \XLite\Core\Request::getInstance()->$fieldName = trim(\XLite\Core\Request::getInstance()->$fieldName);
            $this->gcid = \XLite\Core\Request::getInstance()->$fieldName;

            // Pass validation if cert already related with current order
            $cart = \XLite\Model\Cart::getInstance();
            if (!is_object($cart) || is_null($cart) || $cart->get('gcid') != $this->gcid) {

                // validate
                $gc = new \XLite\Module\GiftCertificates\Model\GiftCertificate($this->gcid);
                $gcStatus = 0 == strlen($this->gcid)
                    ? \XLite\Module\GiftCertificates\Model\GiftCertificate::GC_DOESNOTEXIST
                    : $gc->validate();

                $result = false;

                switch ($gcStatus) {
                    case \XLite\Module\GiftCertificates\Model\GiftCertificate::GC_OK: 
                        $result = true;
                        break;

                    case \XLite\Module\GiftCertificates\Model\GiftCertificate::GC_DOESNOTEXIST: 
                        $this->doesnotexist = true;
                        break;

                    case \XLite\Module\GiftCertificates\Model\GiftCertificate::GC_EXPIRED: 
                        $this->expired = true;
                        break;

                    case \XLite\Module\GiftCertificates\Model\GiftCertificate::GC_DISABLED: 
                        $this->notactive = true;
                        break;

                    default:
                }
            }
        }

        return $result;
    }

}
