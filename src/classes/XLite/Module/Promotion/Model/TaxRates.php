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
 * Tax rates
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Promotion_Model_TaxRates extends XLite_Model_TaxRates implements XLite_Base_IDecorator
{
    /**
     * Set order item 
     * 
     * @param XLite_Model_OrderItem $item Order item
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setOrderItem(XLite_Model_OrderItem $item)
    {
        parent::setOrderItem($item);

        if ($item->get('bonusItem') && !is_null($item->get('originalBonusPrice'))) {
            $this->_conditionValues['cost'] = $item->get('originalBonusPrice');
        }
    }

    public function saveSchema($name, $schema = "")
    {
        parent::saveSchema($name, $schema);

        // update existing schemas repositary
        if (!is_null($schema)) {

            if (is_null($this->config->Taxes->schemas) || !is_array($schemas = $this->config->Taxes->schemas)) {
                $schemas = array();
            }

            if ($schema === "") {
                $schemas[$name]['discounts_after_taxes'] = ($this->config->Taxes->discounts_after_taxes ? 'Y' : 'N');

            } elseif (!in_array($schemas[$name]['discounts_after_taxes'], array('Y', 'N'))) {
                $schemas[$name]['discounts_after_taxes'] = 'N';
            }

            XLite_Core_Database::getRepo('XLite_Model_Config')->createOption('Taxes', 'schemas', serialize($schemas), 'serialized');
        }
    }
}
