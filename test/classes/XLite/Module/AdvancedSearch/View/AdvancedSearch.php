<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Advanced search widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Advanced search widget 
 * 
 * @package    View
 * @subpackage Widget
 * @since      3.0.0 EE
 */
class XLite_Module_AdvancedSearch_View_AdvancedSearch extends XLite_View_Dialog
{
    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('advanced_search');

    /**
     * Widget directories
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $displayModes = array(
        'vertical'   => 'Vertical',
        'horizontal' => 'Horizontal',
    );


    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getHead()
    {
        return 'Search for products';
    }

    /**
     * Get widget templates directory
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'modules/AdvancedSearch/' . $this->attributes['displayMode'];
    }

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
            'displayMode' => new XLite_Model_WidgetParam_List('displayMode', 'horizontal', 'Display mode', $this->displayModes),
        );
    }


    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        return parent::isVisible() || $this->attributes[self::IS_EXPORTED];
    }



    // TODO - check the following methods

    /**
     * Get prices 
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getPrices()
    {
        $prices = unserialize($this->config->AdvancedSearch->prices);
        usort($prices, array($this, 'cmp'));

        return $prices;
    }
    
    /**
     * Get weights 
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getWeights()
    {
        $weights =  unserialize($this->config->AdvancedSearch->weights);
        usort($weights, array($this, 'cmp'));

        return $weights;
    }

    /**
     * String special concationation 
     * 
     * @param srting $val1      String 1
     * @param string $val2      String 2
     * @param string $delimeter Delimiter
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    function strcat($val1, $val2, $delimeter)
    {
        return $val1 . $delimeter . $val2;
    }

    /**
     * Prepare price option 
     * 
     * @param array $option Price range data
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function preparePriceOption($option)
    {
        if ($option['label']) {
            $string = $option['label'];

        } else { 
            $string = '&nbsp;&nbsp;&nbsp;' . $this->price_format($option['start']);

            if ($option['end']) {
                $string .= '-' . $this->price_format($option['end']);

            } else {
                $string .= ' ++';
            }
        }
    
        return $string;
    }

    /**
     * Prepare weight option 
     * 
     * @param array $option Weight range data
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function prepareWeightOption($option)
    {
        if ($option['label']) {
            $string = $option['label'];

        } else {
            $string = $option['start'] . '(' . $this->config->General->weight_symbol . ')';

            if ($option['end']) {
                $string .= '-' . $option['end'] . '(' . $this->config->General->weight_symbol . ')';

            } else {
                $string .= ' ++';
            }
        }

        return $string;
    }
}

