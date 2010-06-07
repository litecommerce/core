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
class XLite_Controller_Admin_Countries extends XLite_Controller_Admin_Abstract
{
    function obligatorySetStatus($status)
    {
        if (!in_array('status', $this->params)) {
            $this->params[] = "status";
        }
        $this->set('status', $status);
    }

    function action_update()
    {
        // parse POST'ed data, modify country properties
        list($keys, $parameters) = XLite_Core_Database::prepareArray(
            array_keys(XLite_Core_Request::getInstance()->countries)
        );
        $list = XLite_Core_Database::getQB()
            ->select('c')
            ->from('XLite_Model_Country', 'c')
            ->where('c.code IN (' . implode(', ', $keys) . ')')
            ->setParameters($parameters)
            ->getQuery()
            ->getResult();

        if ($list) {
            foreach ($list as $country) {
                $data = XLite_Core_Request::getInstance()->countries[$country->code];
                $data['eu_member'] = isset($data['eu_member']);
                $data['enabled'] = isset($data['enabled']);
                $country->map($data);
                XLite_Core_Database::getEM()->persist($country);
            }

            XLite_Core_Database::getEM()->flush();
        }

        $this->obligatorySetStatus('updated');
    }

    function action_add()
    {
        if ( empty(XLite_Core_Request::getInstance()->code) ) {
            $this->set('valid', false);
            $this->obligatorySetStatus('code');
            return;
        }

        $country = XLite_Core_Database::getRepo('XLite_Model_Country')->find(XLite_Core_Request::getInstance()->code);
        if ($country) {
            $this->set('valid', false);
            $this->obligatorySetStatus('exists');
            return;
        }

        if (empty(XLite_Core_Request::getInstance()->country)) {
            $this->set('valid', false);
            $this->obligatorySetStatus('country');
            return;
        }

        if (empty(XLite_Core_Request::getInstance()->charset)) {
            $this->set('valid', false);
            $this->obligatorySetStatus('charset');
            return;
        }

        $country = new XLite_Model_Country();

        $country->map(XLite_Core_Request::getInstance()->getData());
        $country->eu_member = isset(XLite_Core_Request::getInstance()->eu_member);
        $country->enabled = isset(XLite_Core_Request::getInstance()->enabled);

        XLite_Core_Database::getEM()->persist($country);
        XLite_Core_Database::getEM()->flush();

        $this->obligatorySetStatus('added');
    }

    function action_delete()
    {
        $countries = XLite_Core_Request::getInstance()->delete_countries;

        if ( is_array($countries) && count($countries) > 0 ) {
            foreach ($countries as $code) {
                $country = XLite_Core_Database::getEM()->find('XLite_Model_Country', $code);
                if ($country) {
                    XLite_Core_Database::getEM()->remove($country);
                }
            }
            XLite_Core_Database::getEM()->flush();
        }

        $this->obligatorySetStatus('deleted');
    }

    /**
     * Get all countries 
     * TODO - move to widget
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCountries()
    {
        return XLite_Core_Database::getRepo('XLite_Model_Country')->findAllCountries();
    }
}
