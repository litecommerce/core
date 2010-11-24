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

namespace XLite\Module\AntiFraud\Model;

/**
 * Country
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Country extends \XLite\Model\Country implements \XLite\Base\IDecorator
{
    /**
     * Country risk rate
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $risk_country;

    /**
     * Country risk rate with current order (cache)
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $riskCountryCache;

    /**
     * Check - is risk country or not
     * 
     * @param integer $check Check value OPTIONAL
     *  
     * @return integer|boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isRiskCountry($check = null) 
    {
        if (is_null($this->riskCountryCache)) {

            $result = $this->risk_country;

            /* TODO - check & rework
            $order = $this->get('order');
            if (isset($order)) {
                $af_data = $order->getComplex('details.af_data');
                if (isset($af_data) && is_array($af_data)) {
                    if (isset($af_data['CHECK_IP_COUNTRY'])) {
                        $result += ($af_data['CHECK_IP_COUNTRY'] != $this->get('code')) ? 2 : 0;
                    }
                    if (isset($af_data['CHECK_IP_DISTANCE'])) {
                        $result += ($af_data['CHECK_IP_DISTANCE'] > $this->config->AntiFraud->antifraud_safe_distance) ? 4 : 0;
                    }
                }
            }
            */

            $this->riskCountryCache = $result;
        }

        return is_null($check)
            ? $this->riskCountryCache
            : 0 < ($this->riskCountryCache & $check);

    }
    
}
