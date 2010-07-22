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

namespace XLite\View\FormField\Select;

/**
 * \XLite\View\FormField\Select\Country 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Country extends Regular
{
    /**
     * Widget param names
     */

    const PARAM_ALL               = 'all';
    const PARAM_STATE_SELECTOR_ID = 'stateSelectorId';
    const PARAM_STATE_INPUT_ID    = 'stateInputId';


    /**
     * Display only enabled countries
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $onlyEnabled = false;


    /**
     * Return field template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFieldTemplate()
    {
        return 'select_country.tpl';
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
            self::PARAM_ALL               => new \XLite\Model\WidgetParam\Bool('All', false),
            self::PARAM_STATE_SELECTOR_ID => new \XLite\Model\WidgetParam\String('State select ID', null),
            self::PARAM_STATE_INPUT_ID    => new \XLite\Model\WidgetParam\String('State input ID', null),
        );
    }

    /**
     * Get selector default options list
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultOptions()
    {
        return $this->onlyEnabled
            ? \XLite\Core\Database::getRepo('XLite\Model\Country')->findByEnabled(true)
            : \XLite\Core\Database::getRepo('XLite\Model\Country')->findAll();
    }

    /**
     * getDefaultValue 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultValue()
    {
        return \XLite\Core\Config::getInstance()->General->default_country;
    }

    /**
     * Some JavaScript code to insert
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getInlineJSCode()
    {
        return '$(document).ready(function() { '
            . 'stateSelectors[\'' . $this->getFieldId() . '\'] = new StateSelector('
            . '\'' . $this->getFieldId() . '\', '
            . '\'' . $this->getParam(self::PARAM_STATE_SELECTOR_ID) . '\', '
            . '\'' . $this->getParam(self::PARAM_STATE_INPUT_ID) . '\'); });';
    }

    /**
     * Save current form reference and sections list, and initialize the cache
     *
     * @param array $params widget params
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $params = array())
    {
        if (!empty($params[self::PARAM_ALL])) {
            $this->onlyEnabled = true;
        }

        parent::__construct($params);
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
        $list[] = $this->getDir() . '/select_country.js';

        return $list;
    }

    /**
     * Pass the DOM Id fo the "States" selectbox
     * NOTE: this function is public since it's called from the View_Model_Profile_Base_Abstract class
     * 
     * @param string $selectorId DOM Id of the "States" selectbox
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setStateSelectorIds($selectorId, $inputId)
    {
        $this->getWidgetParams(self::PARAM_STATE_SELECTOR_ID)->setValue($selectorId);
        $this->getWidgetParams(self::PARAM_STATE_INPUT_ID)->setValue($inputId);
    }
}

