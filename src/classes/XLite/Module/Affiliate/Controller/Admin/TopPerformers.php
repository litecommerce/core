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

namespace XLite\Module\Affiliate\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class TopPerformers extends PartnerStats
{
    function getPageTemplate()
    {
        return "modules/Affiliate/top_performers.tpl";
    }

    function fillForm()
    {
        parent::fillForm();
        if (!isset($this->sort_by)) {
            $this->set('sort_by', "clicks");
        }
        if (!isset($this->report_by)) {
            $this->set('report_by', "affiliate");
        }
    }
    
    function getStats()
    {
        if (is_null($this->stats)) {
            $ss = new \XLite\Module\Affiliate\Model\BannerStats();
            $this->stats = array();
            $this->stats = $ss->searchTopPerformers(
                $this->get('startDate'),
                $this->get('endDate')+24*3600,
                $this->get('report_by'));
            usort($this->stats, array($this, "cmpStats"));
            $this->stats = array_reverse($this->stats);
            foreach ($this->stats as $sid => $stat) {
                if (isset($stat['partner_id'])) {
                    $partner = new \XLite\Model\Profile();
                    if (!$partner->find("profile_id=".$stat['partner_id'])) {
                        $partner->set('login', "Unknown");
                    }
                    $this->stats[$sid]['partner'] = $partner;
                }
            }
        }
        return $this->stats;
    }

    function cmpStats($p1, $p2)
    {
        $key = $this->sort_by;
        if ($p1[$key] == $p2[$key]) {
            return 0;
        }
        return ($p1[$key] < $p2[$key]) ? -1 : 1;
    }
}
