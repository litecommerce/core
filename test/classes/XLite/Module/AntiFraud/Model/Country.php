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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_AntiFraud_Model_Country extends XLite_Model_Country implements XLite_Base_IDecorator
{
    public $riskCountry = null;

    public function __construct($id = null)  
    {
        $this->fields['risk_country'] = 0;
        parent::__construct($id);
    }

    function isRiskCountry($check=null) 
    {
        if (isset($this->riskCountry)) {
            if (!isset($check)) {
                return $this->riskCountry;
            } else {
                return (($this->riskCountry & $check) > 0) ? true : false;
            }
        }

        $result = $this->get('risk_country');
        $order = $this->get('order');
        if (isset($order)) {
            $af_data = $order->getComplex('details.af_data');
            if (isset($af_data) && is_array($af_data)) {
                if (isset($af_data['CHECK_IP_COUNTRY'])) {
                    $result += ($af_data['CHECK_IP_COUNTRY'] != $this->get('code')) ? 2 : 0;
                }
                if (isset($af_data['CHECK_IP_DISTANCE'])) {
                    $result += ($af_data['CHECK_IP_DISTANCE'] > $this->config->getComplex('AntiFraud.antifraud_safe_distance')) ? 4 : 0;
                }
            }
        }

        $this->riskCountry = $result;
        if (!isset($check)) {
            return $this->riskCountry;
        } else {
            return (($this->riskCountry & $check) > 0) ? true : false;
        }
    }
    
}
