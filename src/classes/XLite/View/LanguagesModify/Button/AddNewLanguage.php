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

namespace XLite\View\LanguagesModify\Button;

/**
 * Add new language button
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class AddNewLanguage extends \XLite\View\Button\Regular
{
    /**
     * Widget parameters
     */
    const PARAM_PAGE = 'page';


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
            self::PARAM_PAGE => new \XLite\Model\WidgetParam\Int('Page index', 1),
        );

        $this->widgetParams[self::PARAM_LABEL]->setValue('Add new language');
        $this->widgetParams[self::PARAM_STYLE]->setValue('add-new-language');
    }

    /**
     * JavaScript: default JS code to execute
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultJSCode()
    {
        return 'openAddNewLanguage(this, '
            . '\'' . $this->getParam(self::PARAM_PAGE) . '\');';
    }

    /**
     * Check widget visibility
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && \XLite\Core\Database::getRepo('\XLite\Model\Language')->findInactiveLanguages();
    }
}
