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
 * Wishlists admin controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WishList_Controller_Admin_Wishlists extends XLite_Controller_Admin_Abstract
{

    /**
     * Controller params
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $params = array('target', 'mode');

    /**
     * Wishlists array
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $wishlists = null;

    /**
     * do action 'search' - save search parameters in the session
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSearch()
    {
        $searchParams = $this->session->get('wishlist_search');

        if (!is_array($searchParams)) {
            $searchParams = XLite_Module_WishList_Model_WishList::getDefaultSearchConditions();
        }

        if (isset(XLite_Core_Request::getInstance()->startId)) {
            $searchParams['startId'] = intval(XLite_Core_Request::getInstance()->startId);
            if (0 === $searchParams['startId']) {
                $searchParams['startId'] = '';
            }
        }

        if (isset(XLite_Core_Request::getInstance()->endId)) {
            $searchParams['endId'] = intval(XLite_Core_Request::getInstance()->endId);
            if (0 === $searchParams['endId']) {
                $searchParams['endId'] = '';
            }
        }

        if (isset(XLite_Core_Request::getInstance()->email)) {
            $searchParams['email'] = XLite_Core_Request::getInstance()->email;
        }

        if (isset(XLite_Core_Request::getInstance()->sku)) {
            $searchParams['sku'] = XLite_Core_Request::getInstance()->sku;
        }

        if (isset(XLite_Core_Request::getInstance()->productTitle)) {
            $searchParams['productTitle'] = XLite_Core_Request::getInstance()->productTitle;
        }

        // Validate startDate and endDate
        // TODO: need to move to the unified place
        if (
            isset(XLite_Core_Request::getInstance()->startDateMonth)
            && isset(XLite_Core_Request::getInstance()->startDateDay)
            && isset(XLite_Core_Request::getInstance()->startDateYear)
        ) {
            $searchParams['startDate'] = mktime(
                0, 0, 0,
                intval(XLite_Core_Request::getInstance()->startDateMonth),
                intval(XLite_Core_Request::getInstance()->startDateDay),
                intval(XLite_Core_Request::getInstance()->startDateYear)
            );

        } elseif (isset(XLite_Core_Request::getInstance()->startDate)) {
            $time = strtotime(XLite_Core_Request::getInstance()->startDate);
            if (false !== $time && -1 !== $time) {
                $searchParams['startDate'] = mktime(
                    0, 0, 0,
                    date('m', $time),
                    date('d', $time),
                    date('Y', $time)
                );

            } elseif (0 == strlen(XLite_Core_Request::getInstance()->startDate)) {
                $searchParams['startDate'] = '';
            }
        }

        if (
            isset(XLite_Core_Request::getInstance()->endDateMonth)
            && isset(XLite_Core_Request::getInstance()->endDateDay)
            && isset(XLite_Core_Request::getInstance()->endDateYear)
        ) {
            $searchParams['endDate'] = mktime(
                23, 59, 59,
                intval(XLite_Core_Request::getInstance()->endDateMonth),
                intval(XLite_Core_Request::getInstance()->endDateDay),
                intval(XLite_Core_Request::getInstance()->endDateYear)
            );

        } elseif (isset(XLite_Core_Request::getInstance()->endDate)) {
            $time = strtotime(XLite_Core_Request::getInstance()->endDate);
            if (false !== $time && -1 !== $time) {
                $searchParams['endDate'] = mktime(
                    23, 59, 59,
                    date('m', $time),
                    date('d', $time),
                    date('Y', $time)
                );
                
            } elseif (0 == strlen(XLite_Core_Request::getInstance()->endDate)) {
                $searchParams['endDate'] = '';
            }
        }

        $this->session->set('wishlist_search', $searchParams);

        $this->set('returnUrl', $this->buildUrl('wishlists', '', array('mode' => 'search')));
    }

    /**
     * Get wishlists
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWishlists()
    {
        if (is_null($this->wishlists)) {

            $wishlist = new XLite_Module_WishList_Model_WishList();
            $wishlist->collectGarbage();

            $profile = false;

            $searchParams = $this->getConditions();

            if (!empty($searchParams['email'])) {
                $profile = new XLite_Model_Profile();
                $profile->find("login='" . addslashes($searchParams['email']) . "'");
            }

            $this->wishlists = $wishlist->search(
                $searchParams['startId'],
                $searchParams['endId'],
                $profile, 
                $searchParams['sku'],
                $searchParams['productTitle'],
                $searchParams['startDate'],
                $searchParams['endDate'] + 24 * 3600
            );
        }

        return $this->wishlists;

    }

    /**
     * Get count of wishlists found
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCount()
    {
        return count($this->getWishLists());
    }

    /**
     * Do action 'delete'
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        if (isset(XLite_Core_Request::getInstance()->wishlistIds)) {

            $wishlists = XLite_Core_Request::getInstance()->wishlistIds;

            foreach ($wishlists as $id) {

                $wishlist = new XLite_Module_WishList_Model_WishList($id);
                $wishlistProducts = $wishlist->get("products");

                foreach($wishlistProducts as $product) {
                    $product->delete();
                }

                $wishlist->delete();
            }
        }
    }

    /**
     * Get search conditions
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getConditions()
    {
        $searchParams = $this->session->get('wishlist_search');

        if (!is_array($searchParams)) {
            $searchParams = XLite_Module_WishList_Model_WishList::getDefaultSearchConditions();
            $this->session->set('searchParams', $searchParams);
        }

        return $searchParams;
    }

    /**
     * Get search condition parameter by name
     * 
     * @param string $paramName 
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCondition($paramName)
    {
        $return = null;
        $searchParams = $this->getConditions();

        if (isset($searchParams[$paramName])) {
            $return = $searchParams[$paramName];
        }

        return $return;
    }

}

