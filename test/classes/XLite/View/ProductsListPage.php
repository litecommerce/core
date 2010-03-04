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
 * Products list page widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_ProductsListPage extends XLite_View_Abstract
{
    /**
     * Display modes 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $displayModes = array(
        'grid'  => 'Grid',
        'list'  => 'List',
        'table' => 'Table',
    );

    /**
     * Default display mode 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $defaultDisplayMode = 'grid';

    /**
     * Grid columns count
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $gridColumns = 3;

    /**
     * Widget template 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $template = 'products_list/grid/body.tpl';

    /**
     * Constructor
     * 
     * @param array $attributes widget params
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function init(array $attributes = array())
    {
        $this->attributes['data'] = array();
        $this->attributes['displayMode'] = self::$defaultDisplayMode;

        parent::init($attributes);
    }

    /**
     * Initialization
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function initView()
    {
        parent::initView();

        // Define widget template
        $this->template = 'products_list/';

        $displayModes = self::getDisplayModes();
        $this->template .= isset($displayModes[$this->attributes['displayMode']])
            ? $this->attributes['displayMode']
            : self::$defaultDisplayMode;

        $this->template .= '/body.tpl';
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
        return parent::isVisible()
            && is_array($this->attributes['data'])
            && $this->attributes['data'];
    }

	/**
	 * Get data 
	 * 
	 * @return array
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getData()
	{
		return $this->attributes['data'];
	}

    /**
     * Get grid item width (percent)
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getGridItemWidth()
    {
        return floor(100 / $this->gridColumns) - 6;
    }

    /**
     * Get display modes 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    static public function getDisplayModes()
    {
        return self::$displayModes;
    }
}
