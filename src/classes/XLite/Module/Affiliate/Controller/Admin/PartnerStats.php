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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Affiliate_Controller_Admin_PartnerStats extends XLite_Controller_Admin_AAdmin
{
    public $params = array('target');
    public $page = "banner_stats";
    public $pages = array('banner_stats' => 'Banner statistics',
                       'sales_stats' => 'Referred sales',
                       'top_performers' => 'Top performers',
                       'partners_tree' => 'Affiliate tree',
                       );

    function fillForm() 
    {
        if (!isset($this->startDate)) {
            $date = getdate(time());
            $this->set('startDate', mktime(0,0,0,$date['mon'],1,$date['year']));
        }
        if (!isset($this->partner_id)) {
            $this->set('partner_id', "");
        }
        parent::fillForm();
    }
}
