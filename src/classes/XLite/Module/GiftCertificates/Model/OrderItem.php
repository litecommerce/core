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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\GiftCertificates\Model;

/**
 * Order item
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OrderItem extends \XLite\Model\OrderItem implements \XLite\Base\IDecorator
{
    /**
     * Gift certificate (cache)
     * 
     * @var    \XLite\Module\GiftCertificates\Model\GiftCertificate
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $gc = null;

    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        // Gift Certificate unique ID
        $this->fields['gcid'] = '';

        parent::__construct();
    }

    /**
     * Get gift certificate
     * 
     * @return \XLite\Module\GiftCertificates\Model\GiftCertificate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getGC()
    {
        if (is_null($this->gc)) {
            $gcId = parent::get('gcid');
            $this->gc = $gcId ? new \XLite\Module\GiftCertificates\Model\GiftCertificate($gcId) : null;
        }

        return $this->gc;
    }

    /**
     * Get item key 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getKey()
    {
        $gcId = $this->get('gcid');

        return $gcId ? ('GC' . $gcId) : parent::getKey();
    }

    /**
     * Get taxable item total 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTaxableTotal()
    {
        return is_null($this->getGC()) ? parent::getTaxableTotal() : 0;
    }

    /**
     * Check - item is shipped or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShipped()
    {
        return is_null($this->getGC()) ? parent::isShipped() : false;
    }

    /**
     * Get item description 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDescription()
    {
        return is_null($this->getGC())
            ? parent::getDescription()
            : ('Gift certificate # ' . $this->get('gcid'));
    }

    /**
     * Get discountable price 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDiscountablePrice()
    {
        return is_null($this->getGC()) ? parent::getDiscountablePrice() : 0;
    }

    /**
     * Get item short description 
     * 
     * @param integer $limit Length limit
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShortDescription($limit = 30)
    {
        return is_null($this->getGC())
            ? parent::getShortDescription($limit)
            : substr('GC #' . $this->get('gcid'), 30);
    }

    /**
     * getter
     * 
     * @param string $name Property name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function get($name)
    {
        if (!is_null($this->getGC())) {

            switch ($name) {
                case 'name':
                    $result = $this->getDescription();
                    break;

                case 'brief_description':
                    $result = $this->getDescription();
                    break;

                case 'description':
                    $result = $this->getDescription();
                    break;

                case 'sku':
                    $result = '';
                    break;

                case 'amount':
                    $result = 1;
                    break;

                default:
            }
        }

        return isset($result) ? $result : parent::get($name);
    }

    /**
     * Delete item
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function delete()
    {
        // remove disabled GCs
        if (!is_null($this->getGC()) && 'D' == $this->getGC()->get('status')) {
            $this->getGC()->delete();
        }

        parent::delete();
    }

    /**
     * Check - item is valid or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isValid()
    {
        $gc = $this->getGC();

        return is_null($gc) ? parent::isValid() : $gc->isExists();
    }

    /**
     * Setter (for gift certificate) 
     * 
     * @param \XLite\Module\GiftCertificates\Model\GiftCertificate $gc Gift certificate
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setGC($gc)
    {
        if (is_null($gc) || !($gc instanceof \XLite\Module\GiftCertificates\Model\GiftCertificate)) {
            $this->gc = null;
            $this->set('gcid', '');

        } else {
            $this->gc = $gc;
            $this->set('gcid', $gc->get('gcid'));
            $this->set('product_id', '');
            $this->set('price', $gc->get('amount'));
        }
    }

    /**
     * Check - has item options or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasOptions()
    {
        return (\XLite\Core\Database::getRepo('XLite\Model\Module')->isModuleActive('ProductOptions') && $this->getProduct())
            ? parent::hasOptions()
            : false;
    }

    /**
     * Check - use standard template for item or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isUseStandardTemplate()
    {
        return !$this->get('gcid') && parent::isUseStandardTemplate();
    }

    /**
     * Get item URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getURL()
    {
        $gcId = $this->get('gcid');

        return $gcId
            ? \XLite\Core\Converter::buildURL('gift_certificate', '', array('gcid' => $gcId))
            : parent::getURL();
    }

}
