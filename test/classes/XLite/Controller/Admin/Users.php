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
 * Users list controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Admin_Users extends XLite_Controller_Admin_Abstract
{
    /**
     * params 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', 'mode');

    /**
     * users 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $users = null;

    /**
     * init 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function init()
    {
    	parent::init();

        if ('orders' == XLite_Core_Request::getInstance()->mode) {
            $this->search_orders();
            $this->redirect();
            exit;
        }
    }

    /**
     * Do action 'search' - save search parameters in the session
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSearch()
    {
        $searchParams = $this->session->get('admin_users_search');

        if (!is_array($searchParams)) {
            $searchParams = $this->getDefaultSearchConditions();
        }

        if (isset(XLite_Core_Request::getInstance()->substring)) {
            $searchParams['substring'] = XLite_Core_Request::getInstance()->substring;
        }

        if (isset(XLite_Core_Request::getInstance()->membership)) {
            $searchParams['membership'] = XLite_Core_Request::getInstance()->membership;
        }

        if (isset(XLite_Core_Request::getInstance()->user_type)) {
            $searchParams['user_type'] = XLite_Core_Request::getInstance()->user_type;
        }

        $this->session->set('admin_users_search', $searchParams);

        $this->set('returnUrl', $this->buildUrl('users', '', array('mode' => 'search')));
    }

    /**
     * Do action 'search' - save search parameters in the session
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionList() {
        $this->set('returnUrl', $this->buildUrl('users', '', array('mode' => 'list')));
    }

    /**
     * getSearchQuery 
     * 
     * @param array $field_values
     * @param array $keywords
     * @param string $logic
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSearchQuery($field_values, $keywords, $logic)
    {
        $search = array();
        $logic = ' ' . trim($logic) . ' ';

        foreach($field_values as $field_value => $condition) {

            if ($condition) {

                $query = array();

                foreach ($keywords as $keyword) {
                    $query[] = $field_value . " LIKE '%" . addslashes($keyword) . "%' ";
                }

                $search[] = (count($keywords) > 1 ? '(' . implode($logic, $query) . ')' :  implode('', $query));
            }
        }

        $search_query = implode(' OR ',$search);

        return $search_query;
    }

    /**
     * getUsers 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getUsers()
    {
        $mode = (isset(XLite_Core_Request::getInstance()->mode) ? XLite_Core_Request::getInstance()->mode : '');

        if ('' == $mode || 'orders' == $mode) {
            return array();
        }

        if (is_null($this->users)) {

            $where = array();

            if ('list' == $mode) {
                $searchParams = $this->getDefaultSearchConditions();

            } else {
                $searchParams = $this->getConditions();
            }

            // build WHERE condition for profile info
            if (!empty($searchParams['substring'])) {

                $substring = stripslashes($searchParams['substring']);
        		$keywords = explode(' ', trim($substring));
                $field_values = array(
                    'login'              => true,
                    'billing_firstname'  => true,
                    'billing_lastname'   => true,
                    'shipping_firstname' => true,
                    'shipping_lastname'  => true
                );

                $where[] = '(' . $this->getSearchQuery($field_values, $keywords, 'OR') . ')';
            }

            if ('%' == $searchParams['membership']) { // default is ALL
                $where[] = "membership LIKE '%'";

            } elseif ('' == $searchParams['membership']) { // NO membership set
                $where[] = "membership = ''";

            } elseif ('pending_membership' == $searchParams['membership']) { // pending
                $where[] = '(pending_membership != membership AND LENGTH(pending_membership) > 0)';

            } else { // search for the specified members otherwise
                $where[] = "membership = '" . addslashes($searchParams['membership']) . "'";
            }

            // build WHERE condition for usertype
            $access_level = $this->auth->getAccessLevel($searchParams['user_type']);

            if (!is_null($access_level)) {
                $where[] = 'access_level = ' . $access_level;

            } elseif (is_null($access_level) && 'all' != $searchParams['user_type']) {
                $where[] = 'access_level = -1';
            }

            $profile = new XLite_Model_Profile();
            $profile->fetchKeysOnly = true;
            $profile->fetchObjIdxOnly = false;

            $this->users = $profile->findAll($this->_buildWhere($where), 'login');
        }

        return $this->users;
    }

    /**
     * _buildWhere 
     * 
     * @param array $where
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function _buildWhere($where)
    {
        return join(' AND ',$where);
    }

    /**
     * getCount 
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCount()
    {
        return count($this->get("users"));
    }

    /**
     * search_orders 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function search_orders()
    {
        $profile = new XLite_Model_Profile($this->profile_id);
        $profile->read();
        $login = $profile->get('login');

        if (strlen($login) > 0) {
            $login = urlencode($login);
            $year = $this->config->Company->start_year;
            $date = getdate(time());
            $urlParams = array(
                'mode'           => 'search',
                'login'          => $login,
                'startDateDay'   => 1,
                'startDateMonth' => 1,
                'startDateYear'  => $year,
                'endDateDay'     => $date['mday'],
                'endDateMonth'   => $date['mon'],
                'endDateYear'    => $date['year']
            );

            $this->set('returnUrl', $this->buildUrl('order_list', '', $urlParams));

        } else {
        	$this->set('returnUrl', $this->backUrl);
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
        $searchParams = $this->session->get('admin_users_search');

        if (!is_array($searchParams)) {
            $searchParams = $this->getDefaultSearchConditions();
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

    /**
     * Get the default search conditions array
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultSearchConditions()
    {
        return array(
            'substring'  => '',
            'membership' => '%',
            'user_type'  => 'all'
        );
    }

}

