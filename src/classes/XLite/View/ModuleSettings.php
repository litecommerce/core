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
 * Module settings 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_ModuleSettings extends XLite_View_Abstract
{
    /**
     * Widget param names 
     */
    
    const PARAM_SECTION = 'section';


    /**
     * Return default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return null;
    }

    /**
     * getModule 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getModule()
    {
        return $this->getParam(self::PARAM_SECTION);
    }

    /**
     * getModuleTemplate 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getModuleTemplate()
    {
        return 'modules/' . $this->getModule() . '/settings.tpl';
    }

    /**
     * Return current template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getTemplate()
    {
        $template = parent::getTemplate();

        if (!isset($template)) {
            $template = $this->getModuleTemplate();
        }

        return $template;
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
            self::PARAM_SECTION => new XLite_Model_WidgetParam_String('Module name', null),
        );
    }

    
    /**
     * Check if widget is visible
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() && file_exists($this->getTemplateFile());
    }


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'module';
    
        return $result;
    }
}
