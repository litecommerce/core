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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_View_Price 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_View_Price extends XLite_View_Abstract
{
    /**
     * Widget parameter names
     */

    const PARAM_PRODUCT            = 'product';
    const PARAM_DISPLAY_ONLY_PRICE = 'displayOnlyPrice';


    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_PRODUCT            => new XLite_Model_WidgetParam_Object('Product', null, false, 'XLite_Model_Product'),
            self::PARAM_DISPLAY_ONLY_PRICE => new XLite_Model_WidgetParam_Bool('Only price', false),
        );

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('common/price_plain.tpl');
    }

    /**
     * Check - sale price is enabled or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSalePriceEnabled()
    {
        return $this->config->General->enable_sale_price && $this->calcSaveValue(false) > 0;
    }

    /**
     * Check - is save block is enabeld or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSaveEnabled()
    {
        return $this->config->General->you_save != 'N';
    }

    /**
     * Calculate save value 
     * 
     * @param boolean $full Calculate save value as formatted string or float
     *  
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calcSaveValue($full = true)
    {
         switch ($this->config->General->you_save) {
            case 'YP':
                $value = round(($this->getProduct()->get('sale_price') - $this->getProduct()->get('listPrice')) / $this->getProduct()->get('sale_price') * 100)
					. ($full ? ' %' : '');

                break;

            case 'YD':
                if ($full) {
                    $wg = new XLite_View_Abstract();
                    $value = $wg->price_format($this->getProduct()->get('sale_price') - $this->getProduct()->get('listPrice'));

                } else {
                    $value = $this->getProduct()->get('sale_price') - $this->getProduct()->get('listPrice');
                }

                break;

            default:
                $value = $this->getProduct()->get('listPrice');
        }

        return $value;
    }

    /**
     * Get save value 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSaveValue()
    {
        return $this->calcSaveValue();
    }

	/**
	 * Get product 
	 * 
	 * @return XLite_Model_Product
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getProduct()
	{
		return $this->getParam(self::PARAM_PRODUCT);
	}

	/**
	 * Check - display only price or not
	 * 
	 * @return boolean
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function isDisplayOnlyPrice()
	{
		return $this->getParam(self::PARAM_DISPLAY_ONLY_PRICE);
	}
}
