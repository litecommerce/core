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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\LanguagesModify\Button;

/**
 * Add new label button
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class AddNewLabel extends \XLite\View\Button\Regular
{
    /**
     * Widget parameters
     */
    const PARAM_LANGUAGE = 'language';
    const PARAM_PAGE = 'page';


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
            self::PARAM_LANGUAGE => new \XLite\Model\WidgetParam\String('Language code', null),
            self::PARAM_PAGE     => new \XLite\Model\WidgetParam\Int('Page index', 1),
        );

        $this->widgetParams[self::PARAM_LABEL]->setValue('Add new label');
        $this->widgetParams[self::PARAM_STYLE]->setValue('add-new-label');
    }

    /**
     * JavaScript: default JS code to execute
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultJSCode()
    {
        return 'openAddNewLabel(this, '
            . '\'' . $this->getParam(self::PARAM_LANGUAGE) . '\', '
            . '\'' . $this->getParam(self::PARAM_PAGE) . '\');';
    }
}
