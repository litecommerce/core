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
 * @subpackage Module
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Module head class
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GiftCertificates_Main extends XLite_Module_Abstract
{
    /**
     * Module type
     *
     * @return int
     * @access public
     * @since  3.0.0
     */
    public static function getType()
    {
        return self::MODULE_GENERAL;
    }

    /**
     * Module version
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public static function getVersion()
    {
        return '2.12.RC10';
    }

    /**
     * Module description
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public static function getDescription()
    {
        return 'Gift certificates';
    }    

    /**
     * Determines if we need to show settings form link
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public static function showSettingsForm()
    {
        return true;
    }

    /**
     * Perform some actions at startup
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function init()
    {
        parent::init();

        $this->registerPaymentMethod('gift_certificate');

        $img = XLite_Model_Image::getInstance();
        $img->registerImageClass('ecard_thumbnail', 'e-Card thumbnails', 'ecards', 'thumbnail', 'ecard_id');
        $img->registerImageClass('ecard_image', 'e-Card images', 'ecards', 'image', 'ecard_id');

        $this->xlite->set('GiftCertificatesEnabled', true);
    }
}
