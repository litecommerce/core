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
     * Dialog title
     *
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $head = 'Search for products';

    /**
     * Widget body tempalte
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $body = 'modules/AdvancedSearch/advanced_search.tpl';

    /**
     * Display mode 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $display_mode = 'horizontal';

    /**
     * Display modes 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $display_modes = array(
        'horizontal' => array(
            'name' => 'Horizontal',
            'body' => 'modules/AdvancedSearch/advanced_search.tpl',
        ),
        'vertical' => array(
            'name' => 'Vertical',
            'body' => 'modules/AdvancedSearch/advanced_search_box.tpl',
        ),
    );

    /**
     * Initilization
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function initView()
    {
		parent::initView();

        if ($this->display_mode && $this->call_as_widget && isset($this->display_modes[$this->display_mode])) {
            $this->body = $this->display_modes[$this->display_mode]['body'];
        }

        $this->visible = $this->visible && ($this->call_as_widget || 'advanced_search' == $this->target);

        $this->mode = '';
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

        $display_mode = new XLite_Model_WidgetParam_List('display_mode', $this->display_mode, 'Display mode');
        foreach ($this->display_modes as $k => $v) {
            $display_mode->options[$k] = $v['name'];
        }

        $this->widgetParams += array(
            $display_mode,
        );
    }

    /**
     * Check passed attributes 
     * 
     * @param array $attributes attributes to check
     *  
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validateAttributes(array $attributes)
    {
        $errors = parent::validateAttributes($attributes);

        // Display mode
        if (
            !$errors
            && (!isset($attributes['display_mode']) || !isset($this->display_modes[$attributes['display_mode']]))
        ) {
            $errors['display_mode'] = 'Display mode has wrong value!';
        }

		return $errors;
    }

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

