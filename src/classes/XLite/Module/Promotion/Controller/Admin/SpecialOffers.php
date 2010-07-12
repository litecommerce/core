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

namespace XLite\Module\Promotion\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class SpecialOffers extends \XLite\Controller\Admin\AAdmin
{
    public $specialOffers = null;

    function getSpecialOffers()
    {
        switch ($this->get('sort')) {
            case "date_asc":
                $s_order = "date ASC";
                break;
            case "date_desc":
                $s_order = "date DESC";
                break;
            case "title_asc":
                $s_order = "title ASC";
                break;
            case "title_desc":
                $s_order = "title DESC";
                break;
            case "active_asc":
                $s_order = "enabled ASC";
                break;
            case "active_desc":
                $s_order = "enabled DESC";
                break;
            case "s_date_asc":
                $s_order = "start_date ASC";
                break;
            case "s_date_desc":
                $s_order = "start_date DESC";
                break;
            case "e_date_asc":
                $s_order = "end_date ASC";
                break;
            case "e_date_desc":
                $s_order = "end_date DESC";
                break;
            default:
                $s_order = "date ASC";
                break;
        }
        if (is_null($this->specialOffers)) {
            $sp = new \XLite\Module\Promotion\Model\SpecialOffer();
            $sp->collectGarbage();
            $this->specialOffers = $sp->findAll("status <> 'Trash'", $s_order);
        }
        foreach ($this->specialOffers as $key => $offer) {
            if ($this->specialOffers[$key]->get('end_date') < time())
                $this->specialOffers[$key]->set('status',"Expired");
            if ($this->specialOffers[$key]->get('status') == "Expired")
                $this->specialOffers[$key]->set('enabled',0);
                $this->specialOffers[$key]->update();
        }
        return $this->specialOffers;
    }

    function action_update()
    {
        // set 'active' fields
        $so = new \XLite\Module\Promotion\Model\SpecialOffer();
        foreach ($so->findAll() as $specialOffer) {
            if (isset($_POST['active']) && $_POST['active'][$specialOffer->get('offer_id')]) {
                $specialOffer->set('enabled', 1);
            } else {
                $specialOffer->set('enabled', 0);
            }
            $specialOffer->update();
        }
    }

    function action_delete()
    {
        if (!empty($this->offer_ids)) {
            foreach ($this->offer_ids as $key => $value) {
               $so = new \XLite\Module\Promotion\Model\SpecialOffer($key);
               $so->delete();
            }
        }
    }

    function action_clone()
    {
        if (!empty($this->offer_ids)) {
            foreach ($this->offer_ids as $key => $value) {
               	$so = new \XLite\Module\Promotion\Model\SpecialOffer($key);
                if ( function_exists('func_is_clone_deprecated') && func_is_clone_deprecated() ) {
                   	$clone = $so->cloneObject();
                } else {
                    $clone = $so->clone();
                }
             	$clone->set('title',$so->get('title')." (CLONE)");
                $clone->update();
            }
        }
    }

}
