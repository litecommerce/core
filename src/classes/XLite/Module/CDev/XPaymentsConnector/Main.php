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
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace XLite\Module\CDev\XPaymentsConnector;

/**
 * X-Payments connector module
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @var    string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAuthorName()
    {
        return 'Creative Development LLC';
    }

    /**
     * Module version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getMinorVersion()
    {
        return '0';
    }

    /**
     * Module name
     *
     * @var    string
     * @see    ____func_see____
     * @since  1.0
     */
    public static function getModuleName()
    {
        return 'X-Payments connector';
    }

    /**
     * Module description
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getDescription()
    {
        return 'X-Payments connector';
    }

    /**
     * Determines if we need to show settings form link
     *
     * @return boolean 
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function showSettingsForm()
    {
        return true;
    }

    /**
     * Perform some actions at startup
     * FIXME: to revise completely, registerPaymentMethod() method no longer exists
     *
     * @return void
     * @since  1.0.0
     */
/*
    public static function init()
    {
        parent::init();

        $conf = new \XLite\Module\CDev\XPaymentsConnector\Model\Configuration();
        foreach ($conf->findAll() as $c) {
            $this->registerPaymentMethod(
                $c->get('method_name'),
                'Module_XPaymentsConnector_Model_PaymentMethod_XPayment'
            );
        }

        \XLite::getInstance()->set('X-Payments connector', true);
    }
*/
    
}
