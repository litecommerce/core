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
class XLite_Controller_Admin_States extends XLite_Controller_Admin_AAdmin
{
    function init()
    {
        if (!in_array('country_code', $this->params)) {
            $this->params[] = "country_code";
        }
        parent::init();
        $this->fillForm();
    }

    function obligatorySetStatus($status)
    {
        if (!in_array('status', $this->params)) {
            $this->params[] = "status";
        }
        $this->set('status', $status);
    }

    function fillForm()
    {
        if (isset(XLite_Core_Request::getInstance()->country_code)) {
            $this->set('country_code', XLite_Core_Request::getInstance()->country_code);
        } else {
            $this->set('country_code', $this->config->General->default_country);
        }
    }
    
    function getStates()
    {
        if (is_null($this->states)) {
            $this->states = XLite_Core_Database::getQB()
                ->select('s')
                ->from('XLite_Model_State', 's')
                ->where('s.country_code = :code')
                ->setParameter('code', $this->get('country_code'))
                ->getQuery()
                ->getResult();
        }

        return $this->states;
    }

    function action_add()
    {

        $fields = array('country_code', "code", "state");
        $postData = XLite_Core_Request::getInstance()->getData();

        foreach ($postData as $k=>$v) {
            if (in_array($k, $fields)) {
                $postData[$k] = trim($v);
            }
        }

        $country = XLite_Core_Database::getRepo('XLite_Model_Country')->find($postData['country_code']);

        if (!$country) {
            $this->set('valid', false);
            $this->obligatorySetStatus('country_code');
            return;
        }

        if (empty($postData['code'])) {
            $this->set('valid', false);
            $this->obligatorySetStatus('code');
            return;
        }

        if (empty($postData['state'])) {
            $this->set('valid', false);
            $this->obligatorySetStatus('state');
            return;
        }

        $state = XLite_Core_Database::getQB()
            ->select('COUNT(s.state_id)')
            ->from('XLite_Model_State', 's')
            ->where('s.state = :state AND s.code = :code')
            ->setParameters(array('state' => $postData['state'], 'code' => $postData['code']))
            ->getQuery()
            ->getSingleScalarResult();

        if ($state) {
            $this->set('valid', false);
            $this->obligatorySetStatus('exists');
            return;
        }

        $state = new XLite_Model_State();
        $state->map($postData);
        $state->country = $country;
        XLite_Core_Database::getEM()->persist($state);
        XLite_Core_Database::getEM()->flush();

        $this->obligatorySetStatus('added');
    }

    function action_update()
    {
        $stateData = array();
        if (isset(XLite_Core_Request::getInstance()->state_data)) {
            $stateData = XLite_Core_Request::getInstance()->state_data;
        }

        // use POST'ed data to modify state properties
        foreach ($stateData as $state_id => $state_data) {
            $state = XLite_Core_Database::getEM()->find('XLite_Model_State', $state_id);
            $state->map($state_data);
            XLite_Core_Database::getEM()->persist($state);
        }
        XLite_Core_Database::getEM()->flush();

        $this->obligatorySetStatus('updated');
    }

    function action_delete()
    {
        $states = array();
        if (isset(XLite_Core_Request::getInstance()->delete_states)) {
            $states = XLite_Core_Request::getInstance()->delete_states;
        }
        foreach ($states as $id => $state_id) {
            $state = XLite_Core_Database::getEM()->find('XLite_Model_State', $state_id);
            if ($state) {
                XLite_Core_Database::getEM()->remove($state);
            }
        }
        XLite_Core_Database::getEM()->flush();

        $this->obligatorySetStatus('deleted');
    }
}
