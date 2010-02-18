<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Date selector
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
 * XLite_View_Date 
 * 
 * @package View
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_Date extends XLite_View_FormField
{	
    /**
     * params 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array();	

    /**
     * Lower year 
     * 
     * @var    integer
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $lowerYear = 2000;	

    /**
     * Higher year
     * 
     * @var    integer
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $higherYear = 2035;	

    /**
     * Widget tempalte
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $template = "common/date.tpl";

    /**
     * Initialization
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    function initView()
    {
        $dayField   = $this->get('field') . 'Day';
        $monthField = $this->get('field') . 'Month';
        $yearField  = $this->get('field') . 'Year';
        $this->params = array_merge(
			array($dayField, $monthField, $yearField),
			$this->params
		);

		parent::initView();

        if (
			!is_null($this->get($dayField))
			&& !is_null($this->get($monthField))
			&& !is_null($this->get($yearField))
		) {
            // read form fields
            $date = mktime(0, 0, 0, $this->get($monthField), $this->get($dayField), $this->get($yearField));
			$this->xlite->getController()->set($this->get('field'), $date); 
        }
    }

    /**
     * Get days list
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getDays()
    {
        return array_keys(array_fill(1, 31, 1));
    }

    /**
     * Get years list
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getYears()
    {
        $yearsRange = $this->get('yearsRange');
        if (isset($yearsRange) && intval($yearsRange) > 0) {
        	$this->set('higherYear', $this->get('lowerYear') + intval($yearsRange));
        }

		return array_keys(array_fill($this->get('lowerYear'), $this->get('higherYear'), 1));
    }
    
    /**
     * prefill form 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    function fillForm()
    {
        parent::fillForm();

        $value = $this->get('value');
        if (is_null($value)) {
            $value = time();
        }

        $dayField   = $this->get("field") . 'Day';
        $monthField = $this->get("field") . 'Month';
        $yearField  = $this->get("field") . 'Year';

        $date = getdate($value);

        $this->setComplex($dayField, $date['mday']);
        $this->setComplex($monthField, $date['mon']);
        $this->setComplex($yearField, $date['year']);
    }

    /**
     * Get month 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getMonth()
    {
        return $this->get($this->get('field') . 'Month');
    }

    /**
     * Get day 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getDay()
    {
        return $this->get($this->get('field') . 'Day');
    }

    /**
     * Get year 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getYear()
    {
        return $this->get($this->get('field') . 'Year');
    }
}
