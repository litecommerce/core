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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\Model\Profile;

/**
 * \XLite\View\Model\Profile\Addresses
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Addresses extends \XLite\View\Model\Profile\AProfile
{
    /**
     * Widget parameter name
     */
    const PARAM_USE_BODY_TEMPLATE = 'useBodyTemplate';


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'address_book';

        return $result;
    }


    /**
     * Return name of web form widget class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormClass()
    {
        return '\XLite\View\Form\Profile\Addresses';
    }

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return 'Addresses';
    }

    /**
     * Check if current form is accessible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkAccess()
    {
        return \XLite\Core\Auth::getInstance()->isLogged();
    }

    /**
     * Return text for the "Submit" button
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSubmitButtonLabel()
    {
        return 'Apply addresses';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_USE_BODY_TEMPLATE => new \XLite\Model\WidgetParam\Bool(
                'Use body template only', false, false
            )
        );
    }

    /**
     * Determines if need to display only a widget body
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function useBodyTemplate()
    {
        $result = true;

        if (!$this->getParam(self::PARAM_USE_BODY_TEMPLATE)) {
            $result = parent::useBodyTemplate();
        }

        return $result;
    }
}
