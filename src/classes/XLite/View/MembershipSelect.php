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
 * Membership selection widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class MembershipSelect extends FormField
{
     /*
     * Widget parameters names
     */
    const PARAM_FIELD_NAME = 'field';
    const PARAM_VALUE      = 'value';
    const PARAM_ALL_OPTION = 'allOption';
    const PARAM_PENDING_OPTION = 'pendingOption';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/select_membership.tpl';
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
            self::PARAM_FIELD_NAME     => new \XLite\Model\WidgetParam\String('Field', 'membership', false),
            self::PARAM_VALUE          => new \XLite\Model\WidgetParam\String('Value', '%', false),
            self::PARAM_ALL_OPTION     => new \XLite\Model\WidgetParam\Bool('Display All option', false, false),
            self::PARAM_PENDING_OPTION => new \XLite\Model\WidgetParam\Bool('Display Pending option', false, false)
        );
    }

    /**
     * Get active memberships 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMemberships()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Membership')->findActiveMemberships();
    }

}

