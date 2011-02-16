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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\Menu\Customer;

/**
 * Footer menu 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Footer extends \XLite\View\Menu\AMenu
{
    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'footer_menu.tpl';
    }

    /**
     * Define menu items 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineItems()
    {
        return array(
            array(
                'url'    => $this->buildURL('help', '', array('mode' => 'contactus')),
                'label'  => $this->t('Contact us'),
            ),
            array(
                'url'    => $this->buildURL('help', '', array('mode' => 'privacy_statement')),
                'label'  => $this->t('Privacy statement'),
            ),
            array(
                'url'    => $this->buildURL('help', '', array('mode' => 'terms_conditions')),
                'label'  => $this->t('Terms and conditions'),
            ),
        );
    }
}
