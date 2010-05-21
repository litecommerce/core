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
class XLite_Module_Affiliate_Controller_Admin_AffiliatePlans extends XLite_Controller_Admin_Abstract
{
    function action_delete()
    {
        $ap = $this->get('affiliatePlan');
        if ($ap->get('plan_id') == $this->config->getComplex('Affiliate.default_plan')) {
            $cfg = new XLite_Model_Config();
            $cfg->createOption('Affiliate', "default_plan", "");
        }
        $ap->delete();
    }
    
    function action_update()
    {
        $ap = $this->get('affiliatePlan');
        $ap->update();
        if ($ap->get('plan_id') == $this->config->getComplex('Affiliate.default_plan') && !$ap->get('enabled')) {
            $cfg = new XLite_Model_Config();
            $cfg->createOption('Affiliate', "default_plan", "");
        }
    }
    
    function action_add()
    {
        $ap = $this->get('affiliatePlan');
        $ap->create();
        if (!is_null($this->get('returnUrl'))) {
            $this->set('returnUrl', $this->get('returnUrl') . $ap->get('plan_id'));
        }
    }

    function getAffiliatePlan()
    {
        $ap = new XLite_Module_Affiliate_Model_AffiliatePlan(isset($_REQUEST['plan_id']) ? $_REQUEST['plan_id'] : null);
        $ap->set('properties', $_REQUEST);
        return $ap;
    }

    function getAffiliatePlans()
    {
        if (is_null($this->affiliatePlans)) {
            $ap = new XLite_Module_Affiliate_Model_AffiliatePlan();
            $this->affiliatePlans = $ap->findAll();
        }
        return $this->affiliatePlans;
    }
}
