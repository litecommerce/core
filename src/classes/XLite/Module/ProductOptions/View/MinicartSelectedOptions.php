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

namespace XLite\Module\ProductOptions\View;

/**
 * Selected product options widget (minicart)
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 * @ListChild (list="minicart.horizontal.item", weight="15")
 * @ListChild (list="minicart.vertical.item", weight="15")
 */
class MinicartSelectedOptions extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_ITEM    = 'item';
    const PARAM_CART_ID = 'cartId';


    /**
     * Item short options list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $options = null;

    /**
     * Item full options list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $allOptions = null;

    /**
     * Limit enabled flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $limitEnabled = null;

    /**
     * Options lisst limit
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $lengthLimit = 2;


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/ProductOptions/minicart.tpl';
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
            self::PARAM_ITEM    => new \XLite\Model\WidgetParam\Object('Item', null, false, '\XLite\Model\OrderItem'),
            self::PARAM_CART_ID => new \XLite\Model\WidgetParam\Int('Cart id', 0, false),
        );
    }

    /**
     * getItem 
     * 
     * @return \XLite\Model\OrderItem
     * @access protected
     * @since  3.0.0
     */
    protected function getItem()
    {
        return $this->getParam(self::PARAM_ITEM);
    }


    /**
     * Check widget visibility 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getItem()->hasOptions();
    }

    /**
     * Get short options list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptions()
    {
        if (is_null($this->options)) {
            $this->assembleList();
        }

        return $this->options;
    }

    /**
     * Get full options list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAllOptions()
    {
        if (is_null($this->allOptions)) {
            $this->assembleList();
        }

        return $this->allOptions;
    }

    /**
     * Check - options lisst limit enabled or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isLimitEnabled()
    {
        if (is_null($this->limitEnabled)) {
            $this->assembleList();
        }

        return $this->limitEnabled;
    }

    /**
     * Assemble options lists
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function assembleList()
    {
        $this->limitEnabled = false;
        $this->allOptions = $this->getItem()->getOptions()->toArray();
        $this->options = $this->allOptions;
    
        if ($this->lengthLimit < count($this->options)) {
            $this->options = array_slice($this->options, 0, $this->lengthLimit);
            $this->limitEnabled = true;
        }
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        if ($this->isLimitEnabled()) {
            $list[] = 'modules/ProductOptions/minicart.js';
            $list[] = 'js/jquery.cluetip.js';
        }

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'modules/ProductOptions/minicart.css';

        return $list;
    }
}

