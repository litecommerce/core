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

namespace XLite\Module\CDev\Affiliate\Controller\Customer;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PartnerBannerStats extends \XLite\Module\CDev\Affiliate\Controller\Partner
{
    public $statsTotal = array("views" => 0, "click" => 0, "rate" => 0);

    /**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0 
     */
    protected function getLocation()
    {
        return 'Banner statistics';
    }

    function fillForm()
    {
        parent::fillForm();
        if (!isset($this->sort_by)) {
            $this->set('sort_by', "views");
        }
        if (!isset($this->default_banner) && !isset($this->search)) {
            $this->set('default_banner', "1");
        }
    }

    function getStats()
    {
        if (!$this->auth->isAuthorized($this)) {
        	return null;
        }

        if (is_null($this->stats)) {
            $stats = new \XLite\Module\CDev\Affiliate\Model\BannerStats();
            $this->stats = $stats->search(
                    $this->getComplex('auth.profile.profile_id'),
                    $this->get('startDate'),
                    $this->get('endDate')+24*3600,
                    $this->get('sort_by'),
                    $this->get('default_banner'),
                    $this->get('product_banner'),
                    $this->get('direct_link'));
            // calculate stats total using callback
            array_map(array($this, 'sum'), $st = $this->stats);
        }
        return $this->stats;
    }

    function sum($rec)
    {
        $this->statsTotal['views'] += $rec['views'];
        $this->statsTotal['clicks'] += $rec['clicks'];
        if ($this->statsTotal['views'] != 0) {
            $this->statsTotal['rate'] = sprintf("%.02f", doubleval($this->statsTotal['clicks'] / $this->statsTotal['views']));
        }
    }

}
