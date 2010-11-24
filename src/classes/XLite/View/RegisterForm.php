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

namespace XLite\View;

/**
 * Registration form widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class RegisterForm extends \XLite\View\Dialog
{
    /*
     * Widget parameters names
     */

    const PARAM_HEAD       = 'head';
    const PARAM_PROFILE_ID = 'profile_id';


    /**
     * Get directory where template is located (body.tpl)
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'register_form';
    }

    /**
     * Get dialog title
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHead()
    {
        return $this->getParam(self::PARAM_HEAD);
    }

    /**
     * Return current profile object 
     * 
     * @return \XLite\Model\Profile
     * @access protected
     * @since  3.0.0
     */
    protected function getProfile()
    {
        return $this->widgetParams[self::PARAM_PROFILE_ID]->getObject();
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
            self::PARAM_HEAD => new \XLite\Model\WidgetParam\String(
                'Title', 'Profile details'
            ),
            self::PARAM_PROFILE_ID => new \XLite\Model\WidgetParam\ObjectId\Profile(
                'Profile Id', \XLite\Core\Request::getInstance()->profile_id
            ),
        );
    }


    /**
     * Return list of register form fields 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getFormFields()
    {
        $result = array();

        // TODO - add decalarations here

        return $result;
    }
}

