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
 * Advanced search widget
 * 
 * @package    XLite
 * @subpackage View
 * @since      3.0.0
 */
class XLite_Module_AdvancedSearch_View_AdvancedSearch extends XLite_View_Dialog
{
    /**
     * Widget parameter names
     */

    const PARAM_DISPLAY_MODE = 'displayMode';

    /**
     * Allowed display modes
     */

    const DISPLAY_MODE_VERTICAL   = 'vertical';
    const DISPLAY_MODE_HORIZONTAL = 'horizontal';


    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array('advanced_search');

    /**
     *  Display modes
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $displayModes = array(
        self::DISPLAY_MODE_VERTICAL   => 'Vertical',
        self::DISPLAY_MODE_HORIZONTAL => 'Horizontal',
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
        return 'modules/AdvancedSearch/' . $this->getParam(self::PARAM_DISPLAY_MODE);
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_DISPLAY_MODE => new XLite_Model_WidgetParam_List(
                'Display mode', self::DISPLAY_MODE_HORIZONTAL, true, $this->displayModes
            ),
        );
    }


    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() || $this->getParam(self::PARAM_IS_EXPORTED);
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'modules/AdvancedSearch/advanced_search.js';

        return $list;
    }

    /**
     * Get prices
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPrices()
    {
        $prices = unserialize($this->config->AdvancedSearch->prices);
        usort($prices, array($this, 'getSortOrderCallback'));

        return $prices;
    }

    /**
     * Check - price / weight range is selected or not
     * 
     * @param string $range        Saved price / weight range
     * @param object $currentValue Current price range 
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isRangeSelected($range, $currentValue)
    {
        return $range = $currentValue['start'] . ',' . $currentValue['end'];
    }

    /**
     * Get weights
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWeights()
    {
        $weights = unserialize($this->config->AdvancedSearch->weights);
        usort($weights, array($this, 'getSortOrderCallback'));

        return $weights;
    }

    /**
     * Get sort order for prices / weights list (Callback)
     * 
     * @param array $val1 First record
     * @param array $val2 Second record
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSortOrderCallback($val1, $val2)
    {
        $result = 0;

        if ($val1['start'] != $val2['start']) {
            $result = ($val1['start'] < $val2['start']) ? -1 : 1;

        } elseif ($val1['label'] != $val2['label']) {
            $result = ($val1['label'] > $val2['label']) ? -1 : 1;
        }

        return $result;
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

